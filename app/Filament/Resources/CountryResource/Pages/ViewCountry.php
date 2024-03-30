<?php

namespace App\Filament\Resources\CountryResource\Pages;

use App\Filament\Resources\CountryResource;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewCountry extends ViewRecord
{
    protected static string $resource = CountryResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make()->schema([
                    Components\TextEntry::make('id')->label('ID'),
                    Components\SpatieMediaLibraryImageEntry::make('flag')->collection('flag')->circular()->size(48),
                    Components\TextEntry::make('name'),
                    Components\TextEntry::make('capital'),
                    Components\TextEntry::make('iso2'),
                    Components\TextEntry::make('iso3'),
                    Components\TextEntry::make('region'),
                    Components\TextEntry::make('subregion'),
                    Components\TextEntry::make('currency'),
                    Components\IconEntry::make('is_active')->boolean(),
                    Components\TextEntry::make('states_count')->default($this->record->states()->count())->label('States')->badge(),
                    Components\TextEntry::make('cities_count')->default($this->record->cities()->count())->label('Cities')->badge(),
                    Components\TextEntry::make('created_at')->label('Created Date')->dateTime(),
                    Components\TextEntry::make('updated_at')->label('Last Modified Date')->dateTime(),
                ])->columns(),
            ]);
    }
}
