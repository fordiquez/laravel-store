<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TagResource\Pages;
use App\Models\Tag;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static ?string $navigationIcon = 'heroicon-o-bookmark';

    protected static ?string $navigationGroup = 'Goods Management';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(100)
                        ->debounce()
                        ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $state, ?Tag $record) {
                            if (!$get('is_slug_changed_manually') && filled($state) && blank($record)) {
                                $set('slug', Str::slug($state));
                            }
                        }),

                    Forms\Components\Hidden::make('is_slug_changed_manually')->default(false)->dehydrated(false),

                    Forms\Components\TextInput::make('slug')
                        ->unique('tags', 'slug', ignoreRecord: true)
                        ->required()
                        ->maxLength(100)
                        ->afterStateUpdated(fn (Forms\Set $set) => $set('is_slug_changed_manually', true))
                        ->rules([
                            'alpha_dash:ascii',
                            function ($state) {
                                return function (string $attribute, $value, Closure $fail) use ($state) {
                                    if ($state !== '/' && (Str::startsWith($value, '/') || Str::endsWith($value, '/'))) {
                                        $fail('Slug cannot starts or ends with slash.');
                                    }
                                };
                            },
                        ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('slug'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            'index' => Pages\ListTags::route('/'),
            'create' => Pages\CreateTag::route('/create'),
            'edit' => Pages\EditTag::route('/{record}/edit'),
        ];
    }
}
