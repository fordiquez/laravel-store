<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromoCodeResource\Pages;
use App\Models\PromoCode;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;

class PromoCodeResource extends Resource
{
    protected static ?string $model = PromoCode::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationGroup = 'Order Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextInput::make('key')->required()->maxLength(50)->unique(ignoreRecord: true)->default(Str::random(8)),
                    Forms\Components\Radio::make('type')->required()->options(\App\Enums\PromoCode::asSelectArray()),
                    Forms\Components\TextInput::make('value')->required()->numeric(),
                    Forms\Components\TextInput::make('description')->required()->maxLength(255),
                    Forms\Components\TextInput::make('used_times')->default(0)->disabled(),
                    Forms\Components\TextInput::make('greater_than'),
                    Forms\Components\DateTimePicker::make('starts_at')->default(now())->minDate(Date::today()),
                    Forms\Components\DateTimePicker::make('expires_at')->minDate(fn (Forms\Get $get) => $get('starts_at')),
                    Forms\Components\Toggle::make('is_active')->required()->default(true),
                    Forms\Components\Toggle::make('is_public')->required(),
                ])->columns(),
            ]);
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('key')->sortable()->searchable()->limit(20),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Str::of($state)->upper())
                    ->color(fn (string $state): string => match ($state) {
                        \App\Enums\PromoCode::FIXED => 'gray',
                        \App\Enums\PromoCode::PERCENTAGE => 'info',
                    })->sortable(),
                Tables\Columns\TextColumn::make('value')->sortable(),
                Tables\Columns\TextColumn::make('used_times')->sortable(),
                Tables\Columns\TextColumn::make('starts_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('expires_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('greater_than')->money()->sortable()->toggleable(),
                Tables\Columns\IconColumn::make('is_active')->boolean()->sortable(),
                Tables\Columns\IconColumn::make('is_public')->boolean()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')->options(\App\Enums\PromoCode::asSelectArray()),
                Tables\Filters\TernaryFilter::make('is_active'),
                Tables\Filters\TernaryFilter::make('is_public'),
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
            'index' => Pages\ListPromoCodes::route('/'),
            'create' => Pages\CreatePromoCode::route('/create'),
            'edit' => Pages\EditPromoCode::route('/{record}/edit'),
        ];
    }
}
