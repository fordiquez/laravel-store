<?php

namespace App\Filament\Resources;

use App\Enums\OrderDelivery;
use App\Enums\OrderPayment;
use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Models\Good;
use App\Models\Order;
use App\Models\Setting;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Str;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Order Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\Select::make('user_id')
                        ->relationship('user', 'email')
                        ->required(),
                    Forms\Components\Select::make('user_address_id')
                        ->relationship('userAddress', 'title')
                        ->required(),
                    Forms\Components\Select::make('promo_code_id')
                        ->relationship('promoCode', 'key'),
                    Forms\Components\TextInput::make('ref_id')
                        ->required()
                        ->uuid()
                        ->maxLength(36)
                        ->default(Str::uuid()),
                    Forms\Components\Select::make('delivery_method')
                        ->required()
                        ->options(OrderDelivery::asSelectArray()),
                    Forms\Components\Select::make('payment_method')
                        ->required()
                        ->options(OrderPayment::asSelectArray()),
                    Forms\Components\TextInput::make('goods_cost')
                        ->required(),
                    Forms\Components\TextInput::make('delivery_cost'),
                    Forms\Components\TextInput::make('total_cost')
                        ->required(),
                    Forms\Components\Select::make('status')
                        ->required()
                        ->options(OrderStatus::asSelectArray())
                        ->default(OrderStatus::UNPAID),
                ])->columns(),
                Forms\Components\Card::make()->schema([
                    Forms\Components\Grid::make()->schema([
                        Forms\Components\Placeholder::make('Goods cost')
                            ->label('Goods Cost')
                            ->content(fn (callable $get) => collect($get('orderItems'))->reduce(fn (?float $carry, array $item) => floatval($item['unit_price'] * intval($item['quantity'])))),
                        Forms\Components\Placeholder::make('Goods cost')
                            ->label('Goods Cost')
                            ->content(fn (callable $get) => collect($get('orderItems'))->pluck('unit_price')->count())
                    ]),
                ]),
                Forms\Components\Card::make()->schema([
                    Forms\Components\Repeater::make('orderItems')
                        ->relationship()
                        ->mutateRelationshipDataBeforeFillUsing(function (array $data) {
                            $data['price'] = number_format($data['unit_price'] * $data['quantity'], 2);

                            return $data;
                        })
                        ->schema([
                            Forms\Components\Select::make('good_id')
                                ->label('Good')
                                ->required()
                                ->reactive()
                                ->options(Good::pluck('title', 'id'))
                                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                    $good = Good::find($state);
                                    if ($good) {
                                        $set('unit_price', $good->price);
                                        $set('price', number_format($good->price * intval($get('quantity')), 2));
                                        $goodsCost = 0;
                                        foreach ($get('../../orderItems') as $item) {
                                            $goodsCost += floatval($item['unit_price']) * intval($item['quantity']);
                                        }
                                        $set('../../goods_cost', $goodsCost);
                                        $set('../../total_cost', floatval($get('../../delivery_cost')) + $goodsCost);
                                    }
                                })
                                ->columnSpan(4),
                            Forms\Components\TextInput::make('quantity')
                                ->numeric()
                                ->default(1)
                                ->disabled(fn(callable $get) => !$get('good_id'))
                                ->reactive()
                                ->afterStateUpdated(fn($state, callable $get, callable $set) => $set('price', number_format($get('unit_price') * intval($state), 2)))
                                ->columnSpan(2),
                            Forms\Components\TextInput::make('unit_price')
                                ->disabled()
                                ->postfix(Setting::where('key', 'currency')->value('value'))
                                ->columnSpan(2),
                            Forms\Components\TextInput::make('price')
                                ->disabled()
                                ->dehydrated(false)
                                ->postfix(Setting::where('key', 'currency')->value('value'))
                                ->columnSpan(2)
                        ])->defaultItems(1)->columnSpanFull()->columns([
                            'md' => 10
                        ])
                ])
            ]);
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('ref_id')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('user.email')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('delivery_method')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('payment_method')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('goods_cost')->sortable(),
                Tables\Columns\TextColumn::make('delivery_cost')->sortable(),
                Tables\Columns\TextColumn::make('total_cost')->sortable(),
                Tables\Columns\TextColumn::make('status')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user')
                    ->multiple()
                    ->relationship('user', 'email'),
                Tables\Filters\SelectFilter::make('promoCode')
                    ->multiple()
                    ->relationship('promoCode', 'key'),
                Tables\Filters\SelectFilter::make('delivery_method')
                    ->multiple()
                    ->options(OrderDelivery::asSelectArray()),
                Tables\Filters\SelectFilter::make('payment_method')
                    ->multiple()
                    ->options(OrderPayment::asSelectArray()),
                Tables\Filters\SelectFilter::make('status')
                    ->multiple()
                    ->options(OrderStatus::asSelectArray()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
