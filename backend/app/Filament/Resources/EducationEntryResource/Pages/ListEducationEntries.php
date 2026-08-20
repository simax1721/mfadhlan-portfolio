<?php

namespace App\Filament\Resources\EducationEntryResource\Pages;

use App\Filament\Resources\EducationEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEducationEntries extends ListRecords
{
    protected static string $resource = EducationEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
