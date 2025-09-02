<?php

namespace App\Filament\Resources\CellGroupTypeResource\Pages;

use App\Filament\Resources\CellGroupTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCellGroupType extends EditRecord
{
    protected static string $resource = CellGroupTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
