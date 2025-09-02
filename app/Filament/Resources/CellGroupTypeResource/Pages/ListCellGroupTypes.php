<?php

namespace App\Filament\Resources\CellGroupTypeResource\Pages;

use App\Filament\Resources\CellGroupTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCellGroupTypes extends ListRecords
{
    protected static string $resource = CellGroupTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
