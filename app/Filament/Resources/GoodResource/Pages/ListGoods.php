<?php

namespace App\Filament\Resources\GoodResource\Pages;

use App\Filament\Resources\GoodResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGoods extends ListRecords
{
    protected static string $resource = GoodResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
