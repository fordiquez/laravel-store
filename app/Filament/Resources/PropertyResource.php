<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropertyResource\Pages;
use App\Models\Property;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Goods Management';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(100)
                        ->debounce()
                        ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $state, ?Property $record) {
                            if (!$get('is_slug_changed_manually') && filled($state) && blank($record)) {
                                $set('slug', Str::slug($state));
                            }
                        }),

                    Forms\Components\Hidden::make('is_slug_changed_manually')->default(false)->dehydrated(false),

                    Forms\Components\TextInput::make('slug')
                        ->unique('properties', 'slug', ignoreRecord: true)
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
                    Forms\Components\Select::make('category_id')
                        ->required()
                        ->label('Category')
                        ->relationship('category', 'title')
                        ->preload()
                        ->searchable(),
                    Forms\Components\Toggle::make('filterable')->required(),
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
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('name')->limit(25)
                    ->tooltip(fn (Tables\Columns\TextColumn $column) => strlen($column->getState()) <= $column->getCharacterLimit() ? null : $column->getState())
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')->limit(25)
                    ->tooltip(fn (Tables\Columns\TextColumn $column) => strlen($column->getState()) <= $column->getCharacterLimit() ? null : $column->getState())
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('category.title')
                    ->url(fn (Property $record) => route('filament.admin.resources.categories.edit', $record->category_id), true)
                    ->limit(25)
                    ->tooltip(fn (Tables\Columns\TextColumn $column) => strlen($column->getState()) <= $column->getCharacterLimit() ? null : $column->getState())
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('filterable')->boolean()->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'title')
                    ->multiple()
                    ->searchable(),
                Tables\Filters\TernaryFilter::make('filterable'),
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
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }
}
