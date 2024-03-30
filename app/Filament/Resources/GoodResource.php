<?php

namespace App\Filament\Resources;

use App\Enums\GoodStatus;
use App\Filament\Resources\GoodResource\Pages;
use App\Filament\Resources\GoodResource\RelationManagers\PropertiesRelationManager;
use App\Filament\Resources\GoodResource\RelationManagers\ReviewsRelationManager;
use App\Models\Good;
use App\Models\Setting;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class GoodResource extends Resource
{
    protected static ?string $model = Good::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Goods Management';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\Select::make('category_id')
                        ->relationship('category', 'title')
                        ->required()
                        ->searchable(),
                    Forms\Components\Select::make('brand_id')
                        ->relationship('brand', 'name')
                        ->required()
                        ->searchable(),
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(100)
                        ->debounce()
                        ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $state, ?Good $record) {
                            if (!$get('is_slug_changed_manually') && filled($state) && blank($record)) {
                                $set('slug', Str::slug($state));
                            }
                        }),

                    Forms\Components\Hidden::make('is_slug_changed_manually')->default(false)->dehydrated(false),

                    Forms\Components\TextInput::make('slug')
                        ->unique('goods', 'slug', ignoreRecord: true)
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
                    Forms\Components\TextInput::make('vendor_code')
                        ->required()
                        ->numeric()
                        ->default(rand(1000000000, 2147483647))
                        ->unique(Good::class, 'vendor_code', ignoreRecord: true),
                    Forms\Components\SpatieMediaLibraryFileUpload::make('preview')
                        ->collection('goods')
                        ->multiple()
                        ->responsiveImages()
                        ->columnSpanFull(),
                    MarkdownEditor::make('description')
                        ->maxLength(65535)
                        ->disableToolbarButtons(['attachFiles'])
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('short_description')
                        ->maxLength(65535)
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('warning_description')
                        ->maxLength(65535)
                        ->columnSpanFull(),
                    Forms\Components\Select::make('tags')
                        ->multiple()
                        ->relationship('tags', 'name')
                        ->preload()
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('old_price')
                        ->numeric()
                        ->suffix(Setting::where('key', 'currency')->value('value')),
                    Forms\Components\TextInput::make('price')
                        ->required()
                        ->numeric()
                        ->suffix(Setting::where('key', 'currency')->value('value')),
                    Forms\Components\TextInput::make('quantity')
                        ->required()->numeric(),
                    Forms\Components\Select::make('status')
                        ->required()
                        ->options(GoodStatus::asSelectArray()),
                    Forms\Components\KeyValue::make('options')
                        ->keyPlaceholder('Option name')
                        ->valuePlaceholder('Option value')
                        ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('vendor_code')
                    ->sortable()
                    ->searchable()
                    ->copyable(!app()->isLocal())
                    ->tooltip(!app()->isLocal() ? 'Copy to clipboard' : null)
                    ->toggleable(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('preview')
                    ->collection('goods')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('category.title')
                    ->url(fn (Good $record) => route('filament.admin.resources.categories.edit', $record->category_id), true)
                    ->limit(25)
                    ->tooltip(fn (Tables\Columns\TextColumn $column) => strlen($column->getState()) <= $column->getCharacterLimit() ? null : $column->getState())
                    ->badge()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('brand.name')
                    ->url(fn (Good $record) => route('filament.admin.resources.brands.edit', $record->brand_id), true)
                    ->limit(25)
                    ->tooltip(fn (Tables\Columns\TextColumn $column) => strlen($column->getState()) <= $column->getCharacterLimit() ? null : $column->getState())
                    ->badge()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('title')
                    ->limit(25)
                    ->tooltip(fn (Tables\Columns\TextColumn $column) => strlen($column->getState()) <= $column->getCharacterLimit() ? null : $column->getState())
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('old_price')->money()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('price')->money()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('quantity')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Str::of($state)->upper())
                    ->color(fn (string $state): string => match ($state) {
                        GoodStatus::READY_FOR_DISPATCH => 'info',
                        GoodStatus::IN_STOCK => 'success',
                        GoodStatus::ENDS, GoodStatus::IS_OVER => 'danger',
                        GoodStatus::OUT_OF_STOCK => 'gray',
                        GoodStatus::DISCONTINUED => 'warning',
                    })->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('brand')
                    ->multiple()
                    ->relationship('brand', 'name'),
                Tables\Filters\SelectFilter::make('category')
                    ->multiple()
                    ->relationship('category', 'title'),
                Tables\Filters\SelectFilter::make('status')->options(GoodStatus::asSelectArray()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::$model::count();
    }

    public static function getRelations(): array
    {
        return [
            ReviewsRelationManager::class,
            PropertiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGoods::route('/'),
            'create' => Pages\CreateGood::route('/create'),
            'edit' => Pages\EditGood::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
