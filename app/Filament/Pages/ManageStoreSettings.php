<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Action;
use App\Models\Setting;
use Filament\Notifications\Notification;

class ManageStoreSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'إعدادات المتجر';
    protected static ?string $title = 'إعدادات المنصة المتقدمة';
    protected static ?int $navigationSort = 10;
    protected static string $view = 'filament.pages.manage-store-settings';

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

    public function form(Form $form): Form
    {
        return $form
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
