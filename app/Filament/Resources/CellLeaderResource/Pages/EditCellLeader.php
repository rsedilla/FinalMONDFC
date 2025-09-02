<?php

namespace App\Filament\Resources\CellLeaderResource\Pages;

use App\Filament\Resources\CellLeaderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCellLeader extends EditRecord
{
    protected static string $resource = CellLeaderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
