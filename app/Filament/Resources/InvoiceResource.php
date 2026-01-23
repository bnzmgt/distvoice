<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Invoices';
    protected static ?string $pluralModelLabel = 'Invoices';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Invoice Info')
                    ->schema([
                        Forms\Components\TextInput::make('invoice_number')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->disabled(fn ($record) => $record?->status === 'paid'),

                        Forms\Components\Select::make('client_id')
                            ->relationship('client', 'name')
                            ->searchable()
                            ->required()
                            ->disabled(fn ($record) => $record?->status === 'paid'),

                        Forms\Components\DatePicker::make('invoice_date')
                            ->required()
                            ->disabled(fn ($record) => $record?->status === 'paid'),

                        Forms\Components\DatePicker::make('due_date')
                            ->disabled(fn ($record) => $record?->status === 'paid'),

                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'sent' => 'Sent',
                                'paid' => 'Paid',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('draft')
                            ->required(),

                        Forms\Components\Textarea::make('payment_note')
                            ->columnSpanFull()
                            ->disabled(fn ($record) => $record?->status === 'paid'),
                    ])
                    ->columns(2),

                    /* =========================
                    *  NEW SECTION — ITEMS
                    * ========================= */
                    Forms\Components\Section::make('Items')
                        ->schema([
                            Repeater::make('items')
                                ->relationship()
                                ->disabled(fn ($record) => $record?->status === 'paid')
                                ->schema([
                                    Select::make('product_id')
                                        ->label('Product')
                                        ->relationship('product', 'name')
                                        ->searchable()
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            if ($state) {
                                                $product = \App\Models\Product::find($state);
                                                $set('price', $product?->default_price ?? 0);
                                                $set('item_name', $product?->name ?? '');
                                                $set('qty', 1);
                                                $set('total', $product?->default_price ?? 0);
                                            }
                                        }),

                                    TextInput::make('item_name')
                                        ->required()
                                        ->maxLength(255),

                                    TextInput::make('qty')
                                        ->numeric()
                                        ->default(1)
                                        ->reactive()
                                        ->afterStateUpdated(fn ($state, callable $set, callable $get) =>
                                            $set('total', $state * ($get('price') ?? 0))
                                        ),

                                    TextInput::make('price')
                                        ->numeric()
                                        ->reactive()
                                        ->afterStateUpdated(fn ($state, callable $set, callable $get) =>
                                            $set('total', ($get('qty') ?? 1) * $state)
                                        ),

                                    TextInput::make('total')
                                        ->numeric()
                                        ->disabled(),

                                    Hidden::make('is_adjustment')
                                        ->default(false),
                                ])
                                ->columns(6)
                                ->defaultItems(1),
                        ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('client.name')
                    ->label('Client')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'draft',
                        'warning'   => 'sent',
                        'success'   => 'paid',
                        'danger'    => 'cancelled',
                    ]),

                TextColumn::make('total')
                    ->money('IDR', true)
                    ->sortable(),

                TextColumn::make('invoice_date')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (Invoice $record) => $record->status !== 'paid'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit'   => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
