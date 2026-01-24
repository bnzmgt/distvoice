<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuotationResource\Pages;
use App\Models\Quotation;
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
use Filament\Tables\Actions\Action;
use Illuminate\Support\Str;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Filament\Notifications\Notification;

class QuotationResource extends Resource
{
    protected static ?string $model = Quotation::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';
    protected static ?string $navigationLabel = 'Quotations';
    protected static ?string $pluralModelLabel = 'Quotations';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Quotation Info')
                    ->schema([
                        Forms\Components\TextInput::make('quotation_number')
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\Select::make('client_id')
                            ->relationship('client', 'name')
                            ->searchable()
                            ->required(),

                        Forms\Components\DatePicker::make('quotation_date')
                            ->required(),

                        Forms\Components\DatePicker::make('expired_date'),

                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'sent' => 'Sent',
                                'accepted' => 'Accepted',
                                'rejected' => 'Rejected',
                            ])
                            ->default('draft')
                            ->required(),

                        Forms\Components\Textarea::make('notes')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Items')
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
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
                TextColumn::make('quotation_number')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('client.name')
                    ->label('Client')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'draft',
                        'warning'   => 'sent',
                        'success'   => 'accepted',
                        'danger'    => 'rejected',
                    ]),

                TextColumn::make('total')
                    ->money('IDR', true)
                    ->sortable(),

                TextColumn::make('quotation_date')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Action::make('convert_to_invoice')
                ->label('Convert to Invoice')
                ->icon('heroicon-o-arrow-right-circle')
                ->visible(fn ($record) => $record->status === 'accepted')
                ->requiresConfirmation()
                ->action(function ($record) {

                    // 1. Buat invoice
                    $invoice = Invoice::create([
                        'invoice_number' => 'INV-' . now()->format('Ymd-His'),
                        'client_id'      => $record->client_id,
                        'quotation_id'   => $record->id,
                        'invoice_date'   => now(),
                        'subtotal'       => $record->subtotal,
                        'discount'       => $record->discount,
                        'tax'            => 0,
                        'total'          => $record->total,
                        'status'         => 'draft',
                    ]);

                    // 2. Copy items
                    foreach ($record->items as $item) {
                        InvoiceItem::create([
                            'invoice_id'   => $invoice->id,
                            'product_id'   => $item->product_id,
                            'item_name'    => $item->item_name,
                            'description'  => $item->description,
                            'qty'          => $item->qty,
                            'price'        => $item->price,
                            'total'        => 0, // dihitung server-side
                            'is_adjustment'=> $item->is_adjustment,
                        ]);
                    }

                    // 3. Update status quotation (opsional tapi rapi)
                    $record->update([
                        'status' => 'accepted',
                    ]);

                    Notification::make()
                        ->title('Quotation converted to Invoice')
                        ->success()
                        ->send();
                }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListQuotations::route('/'),
            'create' => Pages\CreateQuotation::route('/create'),
            'edit'   => Pages\EditQuotation::route('/{record}/edit'),
        ];
    }
}
