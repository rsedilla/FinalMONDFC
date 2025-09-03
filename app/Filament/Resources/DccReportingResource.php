<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DccReportingResource\Pages;
use App\Models\ChurchAttender;
use App\Models\G12Leader;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DccReportingResource extends Resource
{
    protected static ?string $model = ChurchAttender::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';
    
    protected static ?string $navigationLabel = 'DCC Reporting';
    
    protected static ?string $navigationGroup = 'Reporting';
    
    protected static ?int $navigationSort = 50;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('DCC Attendance Report')
                    ->description('Record Sunday Service attendance for church attenders')
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
                                    ->placeholder('Select a G12 Leader...'),
                                
                                Forms\Components\DatePicker::make('service_date')
                                    ->label('Service Date')
                                    ->required()
                                    ->default(now())
                                    ->native(false),
                            ]),
                        
                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->placeholder('Add any additional notes about today\'s attendance...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->label('First Name')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Last Name')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('network')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'mens' => 'blue',
                        'womens' => 'pink',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('sunday_service_progress')
                    ->label('DCC Progress')
                    ->getStateUsing(fn ($record) => $record->sundayServiceCompletions()->count() . '/4')
                    ->badge()
                    ->color(fn ($record) => $record->sundayServiceCompletions()->count() >= 4 ? 'success' : 'warning'),
                
                Tables\Columns\IconColumn::make('attendance_status')
                    ->label('Present Today')
                    ->boolean()
                    ->default(true)
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('g12_leader')
                    ->label('G12 Leader')
                    ->options(function () {
                        return G12Leader::with('churchAttender')->get()
                            ->mapWithKeys(function ($g12Leader) {
                                $leaderName = $g12Leader->churchAttender ? 
                                    $g12Leader->churchAttender->full_name : 
                                    'Unknown Leader';
                                return [$g12Leader->id => $leaderName];
                            })->toArray();
                    }),
                
                Tables\Filters\Filter::make('has_completed_dcc')
                    ->label('DCC Completed')
                    ->query(function (Builder $query): Builder {
                        return $query->whereRaw('(SELECT COUNT(*) FROM sunday_service_completions WHERE sunday_service_completions.church_attender_id = church_attenders.id) >= 4');
                    }),
                
                Tables\Filters\Filter::make('needs_dcc')
                    ->label('Needs DCC')
                    ->query(function (Builder $query): Builder {
                        return $query->whereRaw('(SELECT COUNT(*) FROM sunday_service_completions WHERE sunday_service_completions.church_attender_id = church_attenders.id) < 4');
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('mark_present')
                    ->label('Mark Present')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->form([
                        Forms\Components\DatePicker::make('attendance_date')
                            ->label('Service Date')
                            ->required()
                            ->default(now())
                            ->native(false),
                        
                        Forms\Components\TextInput::make('service_number')
                            ->label('Service Number (1-4)')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(4)
                            ->default(function ($record) {
                                return $record->sundayServiceCompletions()->count() + 1;
                            }),
                        
                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->placeholder('Any notes about this attendance...')
                            ->rows(2),
                    ])
                    ->action(function ($record, array $data) {
                        // Check if this service number already exists
                        $existingService = $record->sundayServiceCompletions()
                            ->where('service_number', $data['service_number'])
                            ->first();
                        
                        if ($existingService) {
                            \Filament\Notifications\Notification::make()
                                ->title('Service Already Recorded')
                                ->body('Service #' . $data['service_number'] . ' has already been recorded for this member.')
                                ->warning()
                                ->send();
                            return;
                        }
                        
                        // Create new service completion
                        $record->sundayServiceCompletions()->create([
                            'service_number' => $data['service_number'],
                            'attendance_date' => $data['attendance_date'],
                            'notes' => $data['notes'] ?? null,
                        ]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Attendance Recorded')
                            ->body($record->full_name . ' marked present for Service #' . $data['service_number'])
                            ->success()
                            ->send();
                    })
                    ->visible(fn ($record) => $record->sundayServiceCompletions()->count() < 4),
                
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('mark_all_present')
                    ->label('Mark All Present')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->form([
                        Forms\Components\DatePicker::make('attendance_date')
                            ->label('Service Date')
                            ->required()
                            ->default(now())
                            ->native(false),
                        
                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->placeholder('Any notes about today\'s service...')
                            ->rows(2),
                    ])
                    ->action(function ($records, array $data) {
                        $recordedCount = 0;
                        $skippedCount = 0;
                        
                        foreach ($records as $record) {
                            $nextServiceNumber = $record->sundayServiceCompletions()->count() + 1;
                            
                            if ($nextServiceNumber <= 4) {
                                $record->sundayServiceCompletions()->create([
                                    'service_number' => $nextServiceNumber,
                                    'attendance_date' => $data['attendance_date'],
                                    'notes' => $data['notes'] ?? null,
                                ]);
                                $recordedCount++;
                            } else {
                                $skippedCount++;
                            }
                        }
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Bulk Attendance Recorded')
                            ->body("Recorded: {$recordedCount}, Skipped (already completed): {$skippedCount}")
                            ->success()
                            ->send();
                    }),
            ])
            ->defaultSort('first_name');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDccReporting::route('/'),
            'view' => Pages\ViewDccReporting::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $pendingCount = static::getModel()::whereRaw('(SELECT COUNT(*) FROM sunday_service_completions WHERE sunday_service_completions.church_attender_id = church_attenders.id) < 4')->count();

        return $pendingCount > 0 ? (string) $pendingCount : null;
    }

    public static function canCreate(): bool
    {
        return false; // Disable create since we're using existing ChurchAttender records
    }
}
