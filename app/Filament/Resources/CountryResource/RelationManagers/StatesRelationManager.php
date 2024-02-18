<?php

namespace App\Filament\Resources\CountryResource\RelationManagers;

use App\Models\State;
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
                Components\TextEntry::make('id')->label('ID'),
                Components\TextEntry::make('name')
                    ->url(fn (State $record) => route('filament.admin.resources.states.view', $record->id), true)
                    ->badge(),
                Components\IconEntry::make('is_active')->boolean(),
                Components\TextEntry::make('cities_count')->default($this->ownerRecord->cities()->count())->label('Cities')->badge(),
                Components\TextEntry::make('created_at')->label('Created Date')->dateTime(),
                Components\TextEntry::make('updated_at')->label('Last Modified Date')->dateTime(),
            ])->columns();
    }

    /**
     * @throws \Exception
     */
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->url(fn (State $record) => route('filament.admin.resources.states.view', $record->id), true)
                    ->limit(50)
                    ->tooltip(fn (Tables\Columns\TextColumn $column) => strlen($column->getState()) <= $column->getCharacterLimit() ? null : $column->getState())
                    ->badge()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('cities_count')->counts('cities')->label('Cities')->badge()->sortable(),
                Tables\Columns\IconColumn::make('is_active')->boolean()->toggleable()
                    ->tooltip('Toggle value')
                    ->action(fn (State $record, Tables\Columns\Column $column) => $record->update([$column->getName() => !$record->is_active])),
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
