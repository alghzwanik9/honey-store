<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Actions\Action;
// use Filament\Forms\Set; // Removed to fix IDE error
// use Filament\Forms\Get; // Removed to fix IDE error

use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Form $form): Form
    {
        return $form
            ->components([
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->label('القسم')
                    ->required(),
                TextInput::make('name')
                    ->label('اسم المنتج')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, $state, $set) {
                        if ($operation !== 'create') {
                            return;
                        }

                        // تحويل الاسم العربي إلى إنجليزي (Transliteration) تلقائياً
                        $slug = Str::slug($state);
                        $set('slug', $slug);
                    })
                    ->required(),
                TextInput::make('slug')
                    ->label('الرابط (Slug)')
                    ->required()
                    ->unique(ignoreRecord: true),
                RichEditor::make('description')
                    ->label('الوصف')
                    ->hintAction(
                        Action::make('generate_ai')
                            ->label('✨ توليد بالذكاء الاصطناعي')
                            ->icon('heroicon-m-sparkles')
                            ->action(function ($set, $get) {
                                $name = $get('name');
                                $categoryId = $get('category_id');
                                
                                if (!$name) {
                                    \Filament\Notifications\Notification::make()
                                        ->title('الرجاء إدخال اسم المنتج أولاً')
                                        ->warning()
                                        ->send();
                                    return;
                                }

                                $categoryName = \App\Models\Category::find($categoryId)?->name;
                                
                                // Show loading notification
                                \Filament\Notifications\Notification::make()
                                    ->title('جاري كتابة الوصف...')
                                    ->info()
                                    ->send();

                                try {
                                    $service = app(\App\Services\AIService::class);
                                    $description = $service->generateProductDescription($name, $categoryName);
                                    
                                    if ($description) {
                                        $set('description', $description);
                                        \Filament\Notifications\Notification::make()
                                            ->title('تم توليد الوصف بنجاح! 🍯')
                                            ->success()
                                            ->send();
                                    } else {
                                         \Filament\Notifications\Notification::make()
                                            ->title('حدث خطأ أثناء التوليد')
                                            ->danger()
                                            ->send();
                                    }
                                } catch (\Exception $e) {
                                    \Filament\Notifications\Notification::make()
                                        ->title('حدث خطأ: ' . $e->getMessage())
                                        ->danger()
                                        ->send();
                                }
                            })
                    )
                    ->columnSpanFull(),
                TextInput::make('weight')
                    ->label('حجم العبوة / الوزن')
                    ->placeholder('مثال: 1 كيلو، 500 جرام')
                    ->nullable(),
                TextInput::make('price')
                    ->label('السعر')
                    ->required()
                    ->numeric()
                    ->suffix('ر.س'),
                TextInput::make('stock')
                    ->label('الكمية')
                    ->required()
                    ->numeric()
                    ->default(0),
                FileUpload::make('image_url')
                    ->label('صورة المنتج')
                    ->image()
                    ->imageEditor() // يسمح بقص الصورة في لوحة التحكم
                    ->optimize('webp') // الضغط وتحويل الصيغة لـ WebP السريعة
                    ->imageResizeMode('contain')
                    ->imageResizeTargetWidth('800')
                    ->imageResizeTargetHeight('800'),
                Toggle::make('is_active')
                    ->label('مفعل؟')
                    ->required(),
            ]);
    }
}
