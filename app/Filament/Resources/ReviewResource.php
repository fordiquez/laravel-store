<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Components\Rating;
use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-alt';

    protected static ?string $navigationGroup = 'Goods Management';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
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
                    Forms\Components\TextInput::make('video_src')->maxLength(255)->url(),
                    Forms\Components\TextInput::make('ip_address')->maxLength(45)->ipv4(),
                ])->columns()
            ]);
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('user.email')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('good.title')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('rating')
                    ->sortable()
                    ->formatStateUsing(fn(string $state) => self::ratingState($state)),
                Tables\Columns\TextColumn::make('video_src')
                    ->searchable()
                    ->toggleable()
                    ->copyable()
                    ->tooltip('Click to copy'),
                Tables\Columns\TextColumn::make('ip_address')->sortable()->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(),
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
                ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function ratingState(int $state): string
    {
        return match ($state) {
            1 => '★',
            2 => '★★',
            3 => '★★★',
            4 => '★★★★',
            5 => '★★★★★',
            default => $state,
        };
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
