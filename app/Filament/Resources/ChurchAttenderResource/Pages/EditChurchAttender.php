<?php

namespace App\Filament\Resources\ChurchAttenderResource\Pages;

use App\Filament\Resources\ChurchAttenderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChurchAttender extends EditRecord
{
    protected static string $resource = ChurchAttenderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }
}
