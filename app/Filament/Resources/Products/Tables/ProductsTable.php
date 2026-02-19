<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.name')
                    ->sortable()
                    ->label('القسم'),

                TextColumn::make('name')
                    ->searchable()
                    ->label('اسم المنتج'), // تعريب

                TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true) // نخفيه عشان ما يزحم الشاشة
                    ->label('الرابط'),

                TextColumn::make('price')
                    ->money('SAR')  // <--- هنا التغيير المهم (ريال سعودي)
                    ->sortable()
                    ->label('السعر'), // تعريب

                TextColumn::make('stock')
                    ->numeric()
                    ->sortable()
                    ->label('الكمية'), // تعريب

                ImageColumn::make('image_url')
                    ->label('الصورة'), // تعريب

                IconColumn::make('is_active')
                    ->boolean()
                    ->label('مفعل؟'), // تعريب

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('تاريخ الإضافة'),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('آخر تعديل'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
