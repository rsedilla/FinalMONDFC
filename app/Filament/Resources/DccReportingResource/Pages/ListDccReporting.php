<?php

namespace App\Filament\Resources\DccReportingResource\Pages;

use App\Filament\Resources\DccReportingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Concerns\InteractsWithHeaderActions;

class ListDccReporting extends ListRecords
{
    protected static string $resource = DccReportingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('bulk_attendance')
                ->label('Record Bulk Attendance')
                ->icon('heroicon-o-users')
                ->color('success')
                ->url('/admin/dcc-bulk-attendance'),
        ];
    }

    public function getTitle(): string
    {
        return 'DCC Reporting';
    }

    public function getHeading(): string
    {
        return 'DCC (Sunday Service) Attendance Reporting';
    }

    public function getSubheading(): ?string
    {
        return 'Track and record Sunday service attendance for church attenders';
    }
}
