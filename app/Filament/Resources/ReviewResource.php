<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Components\Rating;
use App\Filament\Resources\ReviewResource\Pages;
use App\Filament\Tables\Components\RatingColumn;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Mohammadhprp\IPToCountryFlagColumn\Columns\IPToCountryFlagColumn;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-oval-left-ellipsis';

    protected static ?string $navigationGroup = 'Goods Management';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\Select::make('user_id')
                        ->relationship('user', 'email')
                        ->required()
                        ->searchable()
                        ->preload(),
                    Forms\Components\Select::make('good_id')
                        ->relationship('good', 'title')
                        ->required()
                        ->searchable()
                        ->preload(),
                    Forms\Components\Toggle::make('is_buyer')->default(true)->required(),
                    Rating::make('rating')->required(),
                    Forms\Components\Textarea::make('content')
                        ->required()
                        ->maxLength(65535)
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('advantages')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('disadvantages')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('video_src')->maxLength(255)->url()->columnSpanFull(),
                    Forms\Components\TextInput::make('ip_address')->maxLength(45)->ipv4(),
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
                Tables\Columns\TextColumn::make('user.email')
                    ->url(fn (Review $record) => route('filament.admin.resources.users.edit', $record->user_id), true)
                    ->limit(25)
                    ->badge()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('good.title')
                    ->url(fn (Review $record) => route('filament.admin.resources.goods.edit', $record->good_id), true)
                    ->limit(25)
                    ->badge()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_buyer')->boolean()->toggleable(),
                RatingColumn::make('rating')->color('primary')->sortable(),
                Tables\Columns\TextColumn::make('video_src')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->copyable(!app()->isLocal())
                    ->tooltip(!app()->isLocal() ? 'Copy to clipboard' : null),
                IPToCountryFlagColumn::make('ip_address')->flagPosition()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user')
                    ->multiple()
                    ->relationship('user', 'email'),
                Tables\Filters\SelectFilter::make('good')
                    ->multiple()
                    ->relationship('good', 'title'),
                Tables\Filters\SelectFilter::make('rating')->options([
                    1 => '★',
                    2 => '★★',
                    3 => '★★★',
                    4 => '★★★★',
                    5 => '★★★★★',
                ]),
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
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
