<?php

namespace App\Filament\Resources\CellGroupResource\Helpers;

use App\Models\CellLeader;
use App\Models\G12Leader;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Grid;

class CellGroupFormHelper
{
    public static function getLeaderOptions(): array
    {
        $options = [];
        
        // Cache the query results to avoid multiple DB calls
        $cellLeaders = CellLeader::with('churchAttender')->get();
        $g12Leaders = G12Leader::with('churchAttender')->get();
        
        // Add Cell Leaders
        foreach ($cellLeaders as $leader) {
            if ($leader->churchAttender) {
                $options["cell_leader_{$leader->id}"] = $leader->churchAttender->getFullNameAttribute() . ' (Cell Leader)';
            }
        }
        
        // Add G12 Leaders
        foreach ($g12Leaders as $leader) {
            if ($leader->churchAttender) {
                $options["g12_leader_{$leader->id}"] = $leader->churchAttender->getFullNameAttribute() . ' (G12 Leader)';
            }
        }
        
        return $options;
    }

    public static function getDaysOfWeek(): array
    {
        return [
            'Monday' => 'Monday',
            'Tuesday' => 'Tuesday',
            'Wednesday' => 'Wednesday',
            'Thursday' => 'Thursday',
            'Friday' => 'Friday',
            'Saturday' => 'Saturday',
            'Sunday' => 'Sunday',
        ];
    }

    public static function handleLeaderSelection($state, $set): void
    {
        if (!$state) {
            $set('leader_id', null);
            $set('leader_type', null);
            return;
        }
        
        $parts = explode('_', $state);
        if (count($parts) >= 3) {
            $type = $parts[0] . '_' . $parts[1]; // cell_leader or g12_leader
            $id = $parts[2];
            
            if ($type === 'cell_leader') {
                $set('leader_id', $id);
                $set('leader_type', CellLeader::class);
            } elseif ($type === 'g12_leader') {
                $set('leader_id', $id);
                $set('leader_type', G12Leader::class);
            }
        }
    }

    public static function getPersonalInformationSchema(): array
    {
        return [
            Grid::make(2)
                ->schema([
                    Select::make('leader_selection')
                        ->label('Cell Leader Name')
                        ->placeholder('Select a leader')
                        ->searchable()
                        ->options(fn() => self::getLeaderOptions())
                        ->afterStateUpdated(fn($state, $set) => self::handleLeaderSelection($state, $set))
                        ->dehydrated(false),
                    
                    Hidden::make('leader_id'),
                    Hidden::make('leader_type'),
                    Select::make('cell_group_type_id')
                        ->relationship('cellGroupType', 'name')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            TextInput::make('name')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('e.g., Men\'s Group, Women\'s Group, Youth Group'),
                        ]),
                ]),
            Grid::make(3)
                ->schema([
                    Select::make('meeting_day')
                        ->options(self::getDaysOfWeek())
                        ->required()
                        ->searchable(),
                    TimePicker::make('meeting_time')
                        ->required()
                        ->seconds(false),
                    Textarea::make('location')
                        ->required()
                        ->maxLength(500)
                        ->rows(3)
                        ->placeholder('Enter full address or meeting location details'),
                ]),
        ];
    }
}
