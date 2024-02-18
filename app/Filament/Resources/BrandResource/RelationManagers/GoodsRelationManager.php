<?php

namespace App\Filament\Resources\BrandResource\RelationManagers;

use App\Enums\GoodStatus;
use App\Models\Good;
use App\Models\Setting;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class GoodsRelationManager extends RelationManager
{
    protected static string $relationship = 'goods';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\Select::make('category_id')
                        ->relationship('category', 'title')
                        ->required()
                        ->searchable(),
                    Forms\Components\TextInput::make('vendor_code')
                        ->required()
                        ->numeric()
                        ->default(rand(1000000000, 2147483647))
                        ->unique(Good::class, 'vendor_code', ignoreRecord: true),
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
                    Forms\Components\SpatieMediaLibraryFileUpload::make('thumbnails')
                        ->collection('thumbnails')
                        ->multiple()
                        ->responsiveImages()
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('description')->maxLength(65535)->columnSpanFull(),
                    Forms\Components\Textarea::make('short_description')->maxLength(65535)->columnSpanFull(),
                    Forms\Components\Textarea::make('warning_description')->maxLength(65535)->columnSpanFull(),
                    Forms\Components\Select::make('tags')->multiple()->relationship('tags', 'name')->preload()->columnSpanFull(),
                    Forms\Components\TextInput::make('old_price')->numeric()->suffix(Setting::where('key', 'currency')->value('value')),
                    Forms\Components\TextInput::make('price')->required()->numeric()->suffix(Setting::where('key', 'currency')->value('value')),
                    Forms\Components\TextInput::make('quantity')->required()->numeric(),
                    Forms\Components\Select::make('status')->required()->options(GoodStatus::asSelectArray()),
                ])->columns(),

            ]);
    }

    /**
     * @throws \Exception
     */
    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([SoftDeletingScope::class]))
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('vendor_code')
                    ->sortable()
                    ->searchable()
                    ->copyable(!app()->isLocal())
                    ->tooltip(!app()->isLocal() ? 'Copy to clipboard' : null)
                    ->toggleable(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('preview')
                    ->collection('thumbnails')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('category.title')
                    ->url(fn (Good $record) => route('filament.admin.resources.categories.edit', $record->category_id), true)
                    ->limit(25)
                    ->tooltip(fn (Tables\Columns\TextColumn $column) => strlen($column->getState()) <= $column->getCharacterLimit() ? null : $column->getState())
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('title')
                    ->limit(25)
                    ->tooltip(fn (Tables\Columns\TextColumn $column) => strlen($column->getState()) <= $column->getCharacterLimit() ? null : $column->getState())
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('old_price')->money()->sortable()->toggleable(isToggledHiddenByDefault: true),
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
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
            ]);
    }
}
