<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use App\Enums\GoodStatus;
use App\Models\Good;
use App\Models\Setting;
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
                        ->disabled()
                        ->default(fn (RelationManager $livewire) => $livewire->ownerRecord->id),
                    Forms\Components\Select::make('brand_id')
                        ->relationship('brand', 'name')
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
                        ->autofocus()
                        ->live(debounce: 500)
                        ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $state) {
                            if (!$get('is_slug_changed_manually')) {
                                $set('slug', Str::slug($state));
                            }
                        }),
                    Forms\Components\TextInput::make('slug')
                        ->rule('alpha_dash:ascii')
                        ->unique('goods', 'slug', ignoreRecord: true)
                        ->afterStateUpdated(function (Forms\Set $set) {
                            $set('is_slug_changed_manually', true);
                        })
                        ->required()
                        ->maxLength(100),
                    Forms\Components\SpatieMediaLibraryFileUpload::make('thumbnails')
                        ->collection('goods')
                        ->multiple()
                        ->responsiveImages()
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('description')
                        ->maxLength(65535)
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
                ])->columns(),
            ]);
    }

    /**
     * @throws \Exception
     */
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('vendor_code')
                    ->sortable()
                    ->searchable()
                    ->copyable(!app()->isLocal())
                    ->tooltip(!app()->isLocal() ? 'Copy to clipboard' : null)
                    ->toggleable(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('preview')
                    ->collection('goods')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('category.title')->limit(25)->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('title')->limit(25)->sortable()->searchable()->toggleable(),
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

    protected function getTableQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
