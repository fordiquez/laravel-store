<?php

namespace App\Filament\Resources\StateResource\Pages;

use App\Filament\Resources\StateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateState extends CreateRecord
{
    protected static string $resource = StateResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
