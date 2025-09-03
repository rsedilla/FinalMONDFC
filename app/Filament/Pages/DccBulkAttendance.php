<?php

namespace App\Filament\Pages;

use App\Models\ChurchAttender;
use App\Models\G12Leader;
use App\Models\CellMember;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Collection;

class DccBulkAttendance extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationLabel = 'DCC Bulk Attendance';
    
    protected static ?string $navigationGroup = 'Reporting';
    
    protected static ?int $navigationSort = 51;

    protected static string $view = 'filament.pages.dcc-bulk-attendance';

    public ?string $selectedNetwork = null;
    public ?string $serviceDate = null;
    public ?string $notes = null;
    public Collection $attendees;
    public array $attendanceData = [];

    public function mount(): void
    {
        $this->serviceDate = now()->toDateString();
        $this->attendees = collect([]);
        $this->form->fill([
            'service_date' => $this->serviceDate,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('DCC Bulk Attendance Recording')
                    ->description('Select a G12 leader and record attendance for their team members at once')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('network')
                                    ->label('G12 Leader')
                                    ->options(function () {
                                        return G12Leader::with('churchAttender')->get()
                                            ->mapWithKeys(function ($g12Leader) {
                                                $leaderName = $g12Leader->churchAttender ? 
                                                    $g12Leader->churchAttender->full_name : 
                                                    'Unknown Leader';
                                                return [$g12Leader->id => $leaderName];
                                            })->toArray();
                                    })
                                    ->required()
                                    ->searchable()
                                    ->live()
                                    ->afterStateUpdated(function ($state) {
                                        $this->selectedNetwork = $state;
                                        $this->loadNetworkMembers();
                                    })
                                    ->placeholder('Select a G12 Leader...'),
                                
                                Forms\Components\DatePicker::make('service_date')
                                    ->label('Service Date')
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state) {
                                        $this->serviceDate = $state;
                                    })
                                    ->native(false),
                            ]),
                        
                        Forms\Components\Textarea::make('notes')
                            ->label('Service Notes')
                            ->placeholder('Add any notes about today\'s service...')
                            ->live()
                            ->afterStateUpdated(function ($state) {
                                $this->notes = $state;
                            })
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    public function loadNetworkMembers(): void
    {
        if (!$this->selectedNetwork) {
            $this->attendees = collect([]);
            return;
        }

        // Get the G12 Leader
        $g12Leader = G12Leader::with(['cellGroup.cellMembers.churchAttender'])->find($this->selectedNetwork);
        
        if (!$g12Leader || !$g12Leader->cellGroup) {
            $this->attendees = collect([]);
            return;
        }

        // Get all cell members under this G12 leader's cell group
        $cellMembers = $g12Leader->cellGroup->cellMembers()
            ->with(['churchAttender.sundayServiceCompletions'])
            ->get();

        // Show all team members, regardless of DCC completion status
        $this->attendees = $cellMembers->filter(function ($cellMember) {
            return $cellMember->churchAttender !== null;
        })->map(function ($cellMember) {
            return $cellMember->churchAttender;
        })->sortBy('first_name');

        // Initialize attendance data for all members as present
        $this->attendanceData = [];
        foreach ($this->attendees as $attendee) {
            $completedServices = $attendee->sundayServiceCompletions()->count();
            $this->attendanceData[$attendee->id] = [
                'present' => true,
                'service_number' => $completedServices < 4 ? $completedServices + 1 : 4,
                'is_completed' => $completedServices >= 4,
            ];
        }
    }

    public function toggleAttendance(int $attendeeId): void
    {
        if (!isset($this->attendanceData[$attendeeId])) {
            $this->attendanceData[$attendeeId] = ['present' => true, 'service_number' => 1];
        }
        
        $this->attendanceData[$attendeeId]['present'] = !$this->attendanceData[$attendeeId]['present'];
    }

    public function markAllPresent(): void
    {
        foreach ($this->attendees as $attendee) {
            $this->attendanceData[$attendee->id]['present'] = true;
        }
    }

    public function markAllAbsent(): void
    {
        foreach ($this->attendees as $attendee) {
            $this->attendanceData[$attendee->id]['present'] = false;
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('submit_attendance')
                ->label('Submit Attendance')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->disabled(fn () => empty($this->selectedNetwork) || empty($this->serviceDate))
                ->action('submitAttendance'),
        ];
    }

    public function submitAttendance(): void
    {
        try {
            $this->validate([
                'selectedNetwork' => 'required',
                'serviceDate' => 'required|date',
            ]);

            $recordedCount = 0;
            $skippedCount = 0;
            $completedCount = 0;

            foreach ($this->attendanceData as $attendeeId => $data) {
                if ($data['present']) {
                    $attendee = $this->attendees->find($attendeeId);
                    if ($attendee) {
                        $completedServices = $attendee->sundayServiceCompletions()->count();
                        
                        if ($completedServices >= 4) {
                            $completedCount++;
                            continue; // Skip members who already completed all services
                        }
                        
                        if ($data['service_number'] <= 4) {
                            // Check if this service number already exists
                            $existingService = $attendee->sundayServiceCompletions()
                                ->where('service_number', $data['service_number'])
                                ->first();

                            if (!$existingService) {
                                $attendee->sundayServiceCompletions()->create([
                                    'service_number' => $data['service_number'],
                                    'attendance_date' => $this->serviceDate,
                                    'notes' => $this->notes,
                                ]);
                                $recordedCount++;
                            } else {
                                $skippedCount++;
                            }
                        }
                    }
                }
            }

            $message = "Recorded: {$recordedCount} attendees.";
            if ($skippedCount > 0) {
                $message .= " Skipped: {$skippedCount} (already recorded).";
            }
            if ($completedCount > 0) {
                $message .= " {$completedCount} already completed all DCC services.";
            }

            \Filament\Notifications\Notification::make()
                ->title('Attendance Submitted Successfully!')
                ->body($message)
                ->success()
                ->send();

            // Refresh the members list
            $this->loadNetworkMembers();

        } catch (Halt $exception) {
            return;
        }
    }

    public function getTitle(): string
    {
        return 'DCC Bulk Attendance';
    }

    public function getHeading(): string
    {
        return 'DCC (Sunday Service) Bulk Attendance';
    }

    public function getSubheading(): ?string
    {
        return 'Record attendance for team members under G12 leaders';
    }
}
