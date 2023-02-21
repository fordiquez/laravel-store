<?php

namespace App\Filament\Resources\OptionValueResource\Pages;

use App\Filament\Resources\OptionValueResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOptionValues extends ListRecords
{
    protected static string $resource = OptionValueResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
