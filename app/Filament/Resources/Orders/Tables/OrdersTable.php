<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Models\Order;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('رقم الطلب')
                    ->sortable(),
                TextColumn::make('customer_name')
                    ->label('اسم العميل')
                    ->searchable(),
                TextColumn::make('customer_phone')
                    ->label('جوال العميل')
                    ->searchable(),
                TextColumn::make('total_amount')
                    ->label('الإجمالي')
                    ->money('SAR')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('حالة الطلب')
                    ->badge()
                    ->color(fn(?string $state): string => match ($state) {
                        'completed' => 'success',
                        'shipped' => 'info',
                        'pending' => 'warning',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(?string $state): string => match ($state) {
                        'pending' => 'قيد الانتظار',
                        'shipped' => 'تم الشحن',
                        'completed' => 'مكتمل',
                        'cancelled' => 'ملغي',
                        default => $state ?? '-',
                    })
                    ->searchable(),
                TextColumn::make('payment_status')
                    ->label('حالة الدفع')
                    ->badge()
                    ->color(fn(?string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(?string $state): string => match ($state) {
                        'paid' => 'مدفوع',
                        'pending' => 'غير مدفوع',
                        'failed' => 'فشل الدفع',
                        default => $state ?? 'غير محدد',
                    })
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('تاريخ الطلب')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('آخر تحديث')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('حالة الطلب')
                    ->options([
                        'pending' => 'قيد الانتظار',
                        'shipped' => 'تم الشحن',
                        'completed' => 'مكتمل',
                        'cancelled' => 'ملغي',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                \Filament\Tables\Actions\Action::make('download_invoice')
                    ->label('تحميل الفاتورة')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn (Order $record) => route('orders.invoice', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
