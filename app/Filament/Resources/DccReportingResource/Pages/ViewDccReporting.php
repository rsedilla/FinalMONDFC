<?php

namespace App\Filament\Resources\DccReportingResource\Pages;

use App\Filament\Resources\DccReportingResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDccReporting extends ViewRecord
{
    protected static string $resource = DccReportingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('mark_present')
                ->label('Mark Present')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => $this->record->sundayServiceCompletions()->count() < 4)
                ->action(function () {
                    $nextServiceNumber = $this->record->sundayServiceCompletions()->count() + 1;
                    
                    $this->record->sundayServiceCompletions()->create([
                        'service_number' => $nextServiceNumber,
                        'attendance_date' => now()->toDateString(),
                        'notes' => 'Recorded via DCC Reporting',
                    ]);
                    
                    $this->refreshFormData(['sunday_service_progress']);
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Attendance Recorded')
                        ->body('Service #' . $nextServiceNumber . ' marked for ' . $this->record->full_name)
                        ->success()
                        ->send();
                }),
        ];
    }

    public function getTitle(): string
    {
        return $this->record->full_name . ' - DCC Progress';
    }
}
