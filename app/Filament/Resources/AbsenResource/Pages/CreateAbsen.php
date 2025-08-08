<?php

namespace App\Filament\Resources\AbsenResource\Pages;

use App\Filament\Resources\AbsenResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAbsen extends CreateRecord
{
    protected static string $resource = AbsenResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}