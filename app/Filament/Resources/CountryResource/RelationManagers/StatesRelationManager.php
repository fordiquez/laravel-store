<?php

namespace App\Filament\Resources\CountryResource\RelationManagers;

use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class StatesRelationManager extends RelationManager
{
    protected static string $relationship = 'states';

    protected static ?string $recordTitleAttribute = 'name';

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make()->schema([
                    Components\TextEntry::make('id')->label('ID'),
                    Components\TextEntry::make('name'),
                    Components\IconEntry::make('is_active')->boolean()->columnSpanFull(),
                    Components\TextEntry::make('created_at')->label('Created Date')->dateTime(),
                    Components\TextEntry::make('updated_at')->label('Last Modified Date')->dateTime(),
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
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable()->limit(50)->wrap(),
                Tables\Columns\TextColumn::make('cities_count')->counts('cities')->label('Cities')->badge()->sortable(),
                Tables\Columns\IconColumn::make('is_active')->boolean()->toggleable()
                    ->tooltip('Toggle value')
                    ->action(fn ($record, $column) => $record->update([$column->getName() => !$record->name])),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }
}
