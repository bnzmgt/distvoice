<?php

namespace App\Filament\Resources\InvoiceResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $title = 'Payments';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\DatePicker::make('paid_at')
                ->label('Payment Date')
                ->required(),

            Forms\Components\TextInput::make('amount')
                ->label('Amount')
                ->numeric()
                ->required()
                ->minValue(1),

            Forms\Components\Select::make('method')
                ->options([
                    'transfer' => 'Transfer',
                    'cash'     => 'Cash',
                    'other'    => 'Other',
                ])
                ->required(),

            Forms\Components\TextInput::make('reference')
                ->label('Reference / Proof')
                ->nullable(),

            Forms\Components\Textarea::make('notes')
                ->columnSpanFull()
                ->nullable(),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('paid_at')
            ->columns([
                Tables\Columns\TextColumn::make('paid_at')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->money('IDR', true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('method')
                    ->badge(),

                Tables\Columns\TextColumn::make('reference'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
