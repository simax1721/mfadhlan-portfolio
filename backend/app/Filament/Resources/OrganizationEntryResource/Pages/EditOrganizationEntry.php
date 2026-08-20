<?php

namespace App\Filament\Resources\OrganizationEntryResource\Pages;

use App\Filament\Resources\OrganizationEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrganizationEntry extends EditRecord
{
    protected static string $resource = OrganizationEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
