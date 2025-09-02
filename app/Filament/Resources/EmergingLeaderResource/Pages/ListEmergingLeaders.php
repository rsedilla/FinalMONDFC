<?php

namespace App\Filament\Resources\EmergingLeaderResource\Pages;

use App\Filament\Resources\EmergingLeaderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmergingLeaders extends ListRecords
{
    protected static string $resource = EmergingLeaderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
