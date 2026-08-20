<?php

namespace App\Filament\Resources\EducationEntryResource\Pages;

use App\Filament\Resources\EducationEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEducationEntry extends EditRecord
{
    protected static string $resource = EducationEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
