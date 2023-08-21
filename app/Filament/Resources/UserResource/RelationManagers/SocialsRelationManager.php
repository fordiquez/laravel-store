<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SocialsRelationManager extends RelationManager
{
    protected static string $relationship = 'socials';

    protected static ?string $recordTitleAttribute = 'provider';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextInput::make('provider'),
                    Forms\Components\TextInput::make('provider_id'),
                    Forms\Components\TextInput::make('provider_token'),
                ]),
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
                Tables\Columns\TextColumn::make('provider')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('provider_id')->searchable(),
                Tables\Columns\TextColumn::make('provider_token')->searchable()->toggleable()->limit(50),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }
}
