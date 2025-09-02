<?php

namespace App\Filament\Resources\G12LeaderResource\Pages;

use App\Filament\Resources\G12LeaderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListG12Leaders extends ListRecords
{
    protected static string $resource = G12LeaderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
