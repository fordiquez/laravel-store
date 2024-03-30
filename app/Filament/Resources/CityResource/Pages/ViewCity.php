<?php

namespace App\Filament\Resources\CityResource\Pages;

use App\Filament\Resources\CityResource;
use App\Models\City;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewCity extends ViewRecord
{
    protected static string $resource = CityResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make()->schema([
                    Components\TextEntry::make('id')->label('ID'),
                    Components\TextEntry::make('name'),
                    Components\TextEntry::make('country.name')
                        ->url(fn (City $record) => route('filament.admin.resources.countries.view', $record->country->iso2), true)
                        ->badge(),
                    Components\TextEntry::make('state.name')
                        ->url(fn (City $record) => route('filament.admin.resources.states.view', $record->state_id), true)
                        ->badge(),
                    Components\IconEntry::make('is_active')->boolean()->columnSpanFull(),
                    Components\TextEntry::make('created_at')->label('Created Date')->dateTime(),
                    Components\TextEntry::make('updated_at')->label('Last Modified Date')->dateTime(),
                ])->columns(),
            ]);
    }
}
