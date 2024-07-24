<?php

namespace App\Filament\Resources\WebPageResource\Pages;

use App\Filament\Resources\WebPageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWebPages extends ListRecords
{
    protected static string $resource = WebPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
