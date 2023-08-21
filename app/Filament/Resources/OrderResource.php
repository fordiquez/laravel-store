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
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
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
                Forms\Components\Section::make()->schema([
                    Forms\Components\Select::make('user_id')
                        ->relationship('user', 'email')
                        ->required(),
                    Forms\Components\Select::make('user_address_id')
                        ->relationship('userAddress', 'title')
                        ->required(),
                    Forms\Components\Select::make('promo_code_id')
                        ->relationship('promoCode', 'key'),
                    Forms\Components\TextInput::make('uuid')
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
                    Forms\Components\Hidden::make('goods_cost')->required(),
                    Forms\Components\TextInput::make('delivery_cost'),
                    Forms\Components\Hidden::make('total_cost')->required(),
                    Forms\Components\Select::make('status')
                        ->required()
                        ->options(OrderStatus::asSelectArray())
                        ->default(OrderStatus::UNPAID),
                ])->columns(),
                Forms\Components\Section::make()->schema([
                    Forms\Components\Grid::make()->schema([
                        Forms\Components\Placeholder::make('Goods cost')
                            ->content(fn (callable $get) => self::getMoneyFormat($get('goods_cost'))),
                        Forms\Components\Placeholder::make('Total cost')
                            ->content(fn (callable $get) => self::getMoneyFormat($get('total_cost'))),
                    ]),
                ]),
                Forms\Components\Section::make()->schema([
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
                                        $goodsCost = collect($get('../../orderItems'))->reduce(fn (?float $carry, array $item) => $carry + (floatval($item['unit_price'])) * $item['quantity']);
                                        $set('../../goods_cost', $goodsCost);
                                        $set('../../total_cost', floatval($get('../../delivery_cost')) + $goodsCost);
                                    }
                                })
                                ->columnSpan(4),
                            Forms\Components\TextInput::make('quantity')
                                ->numeric()
                                ->default(1)
                                ->disabled(fn (callable $get) => !$get('good_id'))
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                    $set('price', number_format($get('unit_price') * intval($state), 2));
                                    $goodsCost = collect($get('../../orderItems'))->reduce(fn (?float $carry, array $item) => $carry + (floatval($item['unit_price'])) * $item['quantity']);
                                    $set('../../goods_cost', $goodsCost);
                                    $set('../../total_cost', floatval($get('../../delivery_cost')) + $goodsCost);
                                })
                                ->columnSpan(2),
                            Forms\Components\TextInput::make('unit_price')
                                ->disabled()
                                ->postfix(Setting::where('key', 'currency')->value('value'))
                                ->columnSpan(2),
                            Forms\Components\TextInput::make('price')
                                ->disabled()
                                ->dehydrated(false)
                                ->postfix(Setting::where('key', 'currency')->value('value'))
                                ->columnSpan(2),
                        ])->defaultItems(1)->columnSpanFull()->columns([
                            'md' => 10,
                        ]),
                ]),
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
                Tables\Columns\TextColumn::make('uuid')->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.email')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('delivery_method')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Str::of($state)->upper())
                    ->color(fn (string $state): string => match ($state) {
                        OrderDelivery::COURIER => 'info',
                        OrderDelivery::MEEST => 'gray',
                        OrderDelivery::UKRPOSHTA => 'warning',
                        OrderDelivery::NOVA_POSHTA => 'danger',
                    })->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Str::of($state)->upper())
                    ->color(fn (string $state): string => match ($state) {
                        OrderPayment::CASH => 'info',
                        OrderPayment::STRIPE => 'primary',
                        OrderPayment::BANK_TRANSFER => 'gray',
                    })->sortable(),
                Tables\Columns\TextColumn::make('goods_cost')->money()->sortable(),
                Tables\Columns\TextColumn::make('delivery_cost')->money()->sortable(),
                Tables\Columns\TextColumn::make('total_cost')->money()->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Str::of($state)->upper())
                    ->color(fn (string $state): string => match ($state) {
                        OrderStatus::UNPAID, OrderStatus::PAID => 'gray',
                        OrderStatus::UNDER_PROCESS, OrderStatus::PROCESSING, OrderStatus::REFUNDED, OrderStatus::RETURNED => 'info',
                        OrderStatus::FINISHED => 'success',
                        OrderStatus::REJECTED, OrderStatus::CANCELED => 'danger',
                        OrderStatus::REFUNDED_REQUEST => 'warning',
                    })->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
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

    public static function getNavigationBadge(): ?string
    {
        return static::$model::count();
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

    public static function getMoneyFormat(float $value): string
    {
        return number_format($value, 2) . ' ' . Setting::where('key', 'currency')->value('value');
    }
}
