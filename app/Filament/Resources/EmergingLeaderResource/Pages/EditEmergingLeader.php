<?php

namespace App\Filament\Resources\EmergingLeaderResource\Pages;

use App\Filament\Resources\EmergingLeaderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmergingLeader extends EditRecord
{
    protected static string $resource = EmergingLeaderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
