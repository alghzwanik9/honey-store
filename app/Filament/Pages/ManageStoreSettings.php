<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Action;
use App\Models\Setting;
use Filament\Notifications\Notification;

class ManageStoreSettings extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'إعدادات المتجر';
    protected static ?string $title = 'إعدادات المنصة المتقدمة';
    protected static ?int $navigationSort = 10;
    
    // We intentionally removed the custom $view so it falls back to native Filament page view

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'announcement_active' => Setting::where('key', 'announcement_active')->value('value') == '1',
            'announcement_text' => Setting::where('key', 'announcement_text')->value('value') ?? '',
            'new_products_active' => Setting::where('key', 'new_products_active')->value('value') == '1',
            'new_products_count' => Setting::where('key', 'new_products_count')->value('value') ?? '4',
        ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            \Filament\Schemas\Components\Form::make([
                \Filament\Schemas\Components\EmbeddedSchema::make('form'),
            ])
            ->id('form')
            ->livewireSubmitHandler('save')
            ->footer([
                \Filament\Schemas\Components\Actions::make($this->getFormActions()),
            ]),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('الشريط الإعلاني أعلى الموقع')
                    ->description('تحكم في الشريط الذي يظهر أعلى جميع الصفحات')
                    ->schema([
                        Toggle::make('announcement_active')
                            ->label('تفعيل الشريط الإعلاني')
                            ->onColor('success')
                            ->offColor('danger'),
                        TextInput::make('announcement_text')
                            ->label('نص الإعلان')
                            ->placeholder('مثال: شحن مجاني للطلبات فوق 500 ريال')
                            ->columnSpanFull(),
                    ])->columns(1),

                Section::make('قسم أحدث المنتجات (الصفحة الرئيسية)')
                    ->description('تحكم في قسم المنتجات الجديدة في الصفحة الرئيسية')
                    ->schema([
                        Toggle::make('new_products_active')
                            ->label('تفعيل قسم أحدث المنتجات')
                            ->onColor('success')
                            ->offColor('danger'),
                        TextInput::make('new_products_count')
                            ->label('عدد المنتجات المعروضة')
                            ->numeric()
                            ->default(4)
                            ->minValue(4)
                            ->maxValue(12),
                    ])->columns(1),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('حفظ التعديلات')
                ->color('primary')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Setting::updateOrCreate(['key' => 'announcement_active'], ['value' => $data['announcement_active'] ? '1' : '0', 'type' => 'boolean']);
        Setting::updateOrCreate(['key' => 'announcement_text'], ['value' => $data['announcement_text'], 'type' => 'string']);
        Setting::updateOrCreate(['key' => 'new_products_active'], ['value' => $data['new_products_active'] ? '1' : '0', 'type' => 'boolean']);
        Setting::updateOrCreate(['key' => 'new_products_count'], ['value' => $data['new_products_count'], 'type' => 'integer']);

        Notification::make()
            ->success()
            ->title('تم الحفظ')
            ->body('تم حفظ إعدادات المتجر بنجاح.')
            ->send();
    }
}
