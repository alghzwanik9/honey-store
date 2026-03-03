<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
class OrderForm
{
    public static function configure(Form $form): Form
    {
        return $form
            ->components([
                TextInput::make('customer_name')
                    ->label('اسم العميل')
                    ->required()
                    ->readonly(),
                TextInput::make('customer_phone')
                    ->label('رقم الجوال')
                    ->tel()
                    ->required()
                    ->readonly(),
                TextInput::make('user_id')
                    ->label('رقم المستخدم')
                    ->numeric()
                    ->readonly(),
                TextInput::make('total_amount')
                    ->label('الإجمالي')
                    ->required()
                    ->numeric()
                    ->suffix('ر.س')
                    ->readonly(),
                Select::make('status')
                    ->label('حالة الطلب')
                    ->options([
                        'pending' => 'قيد الانتظار',
                        'shipped' => 'تم الشحن',
                        'completed' => 'مكتمل',
                        'cancelled' => 'ملغي',
                    ])
                    ->required()
                    ->default('pending'),
            ]);
    }
}
