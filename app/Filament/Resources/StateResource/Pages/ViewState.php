<?php

namespace App\Filament\Resources\StateResource\Pages;

use App\Filament\Resources\StateResource;
use App\Models\State;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewState extends ViewRecord
{
    protected static string $resource = StateResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make()->schema([
                    Components\TextEntry::make('id')->label('ID'),
                    Components\TextEntry::make('name'),
                    Components\TextEntry::make('country.name')
                        ->url(fn (State $record) => route('filament.admin.resources.countries.view', $record->country->iso2), true)
                        ->badge()
                        ->columnSpanFull(),
                    Components\IconEntry::make('is_active')->boolean(),
                    Components\TextEntry::make('cities_count')->default($this->record->cities()->count())->label('Cities')->badge(),
                    Components\TextEntry::make('created_at')->label('Created Date')->dateTime(),
                    Components\TextEntry::make('updated_at')->label('Last Modified Date')->dateTime(),
                ])->columns(),
            ]);
    }
}
