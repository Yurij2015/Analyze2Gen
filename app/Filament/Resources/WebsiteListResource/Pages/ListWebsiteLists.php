<?php

namespace App\Filament\Resources\WebsiteListResource\Pages;

use App\Filament\Resources\WebsiteListResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWebsiteLists extends ListRecords
{
    protected static string $resource = WebsiteListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
