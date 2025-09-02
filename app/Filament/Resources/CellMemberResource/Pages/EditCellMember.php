<?php

namespace App\Filament\Resources\CellMemberResource\Pages;

use App\Filament\Resources\CellMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCellMember extends EditRecord
{
    protected static string $resource = CellMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
