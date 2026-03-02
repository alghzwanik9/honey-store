<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Form $form): Form
    {
        return $form
            ->components([
                TextInput::make('name')
                    ->label('الاسم')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, $state, $set) {
                        if ($operation !== 'create') {
                            return;
                        }

                        // تحويل الاسم العربي إلى إنجليزي (Transliteration) تلقائياً
                        // مثال: عسل سمرة -> aasl-smr
                        $slug = Str::slug($state);
                        $set('slug', $slug);
                    })
                    ->required(),
                TextInput::make('slug')
                    ->label('الرابط (Slug)')
                    ->required()
                    ->unique(ignoreRecord: true),
                FileUpload::make('image')
                    ->label('الصورة')
                    ->image()
                    ->directory('categories')
                    ->preserveFilenames()
                    ->maxSize(10240)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                    ->downloadable()
                    ->openable(),

            ]);
    }
}
