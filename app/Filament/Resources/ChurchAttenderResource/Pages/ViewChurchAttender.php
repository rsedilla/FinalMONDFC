<?php

namespace App\Filament\Resources\ChurchAttenderResource\Pages;

use App\Filament\Resources\ChurchAttenderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewChurchAttender extends ViewRecord
{
    protected static string $resource = ChurchAttenderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
