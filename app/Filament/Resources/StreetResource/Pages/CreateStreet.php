<?php

namespace App\Filament\Resources\StreetResource\Pages;

use App\Filament\Resources\StreetResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStreet extends CreateRecord
{
    protected static string $resource = StreetResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
