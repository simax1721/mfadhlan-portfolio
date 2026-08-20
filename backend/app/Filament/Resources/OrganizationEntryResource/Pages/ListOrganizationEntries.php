<?php

namespace App\Filament\Resources\OrganizationEntryResource\Pages;

use App\Filament\Resources\OrganizationEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrganizationEntries extends ListRecords
{
    protected static string $resource = OrganizationEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
