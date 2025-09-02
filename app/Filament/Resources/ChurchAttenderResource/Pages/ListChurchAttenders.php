<?php

namespace App\Filament\Resources\ChurchAttenderResource\Pages;

use App\Filament\Resources\ChurchAttenderResource;
use Filament\Actions;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListChurchAttenders extends ListRecords
{
    protected static string $resource = ChurchAttenderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
