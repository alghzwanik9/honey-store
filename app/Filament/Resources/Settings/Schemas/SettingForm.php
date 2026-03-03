<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
class SettingForm
{
    public static function configure(Form $form): Form
    {
        return $form
            ->components([
                TextInput::make('key')
                    ->label('معرف الإعداد (Key)')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->helperText('مثال: announcement_text أو announcement_active')
                    ->maxLength(255),
                Select::make('type')
                    ->label('نوع الإعداد')
                    ->options([
                        'text' => 'نص',
                        'boolean' => 'تفعيل/تعطيل (1 أو 0)',
                    ])
                    ->required()
                    ->default('text'),
                Textarea::make('value')
                    ->label('القيمة')
                    ->helperText('النص المطلوب أو 1 للتفعيل و 0 للتعطيل')
                    ->columnSpanFull(),
            ]);
    }
}
