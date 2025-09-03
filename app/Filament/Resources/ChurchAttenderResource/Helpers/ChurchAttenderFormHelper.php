<?php

namespace App\Filament\Resources\ChurchAttenderResource\Helpers;

use App\Models\NetworkLeader;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;

class ChurchAttenderFormHelper
{
    public static function getNetworkOptions(): array
    {
        return NetworkLeader::all()->pluck('leader_name', 'leader_name')->toArray();
    }

    public static function isEditPage($livewire): bool
    {
        return $livewire instanceof \App\Filament\Resources\ChurchAttenderResource\Pages\EditChurchAttender;
    }

    public static function getPersonalInformationSchema(): array
    {
        return [
            Grid::make(3)
                ->schema([
                    TextInput::make('first_name')
                        ->label('First Name')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Juan')
                        ->disabled(fn ($livewire) => !self::isEditPage($livewire)),
                    TextInput::make('middle_name')
                        ->label('Middle Name')
                        ->maxLength(255)
                        ->placeholder('Dela Cruz')
                        ->disabled(fn ($livewire) => !self::isEditPage($livewire)),
                    TextInput::make('last_name')
                        ->label('Last Name')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Santos')
                        ->disabled(fn ($livewire) => !self::isEditPage($livewire)),
                ]),
            Grid::make(2)
                ->schema([
                    DatePicker::make('birthday')
                        ->label('Birthday')
                        ->required()
                        ->disabled(fn ($livewire) => !self::isEditPage($livewire)),
                    Select::make('civil_status_id')
                        ->label('Civil Status')
                        ->relationship('civilStatus', 'name')
                        ->required()
                        ->disabled(fn ($livewire) => !self::isEditPage($livewire)),
                ]),
            Grid::make(2)
                ->schema([
                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->placeholder('juan@email.com')
                        ->disabled(fn ($livewire) => !self::isEditPage($livewire)),
                    TextInput::make('phone_number')
                        ->label('Phone Number')
                        ->tel()
                        ->placeholder('0917-871-6509')
                        ->maxLength(255)
                        ->disabled(fn ($livewire) => !self::isEditPage($livewire)),
                ]),
            Grid::make(2)
                ->schema([
                    TextInput::make('social_media_account')
                        ->label('Social Media Account')
                        ->maxLength(255)
                        ->placeholder('Facebook, Instagram, etc.')
                        ->disabled(fn ($livewire) => !self::isEditPage($livewire)),
                    Select::make('network')
                        ->label('Network')
                        ->options(fn() => self::getNetworkOptions())
                        ->searchable()
                        ->required(),
                ]),
            Textarea::make('present_address')
                ->label('Present Address')
                ->required()
                ->maxLength(500)
                ->rows(2)
                ->placeholder('Current address')
                ->disabled(fn ($livewire) => !self::isEditPage($livewire)),
            Textarea::make('permanent_address')
                ->label('Permanent Address')
                ->required()
                ->maxLength(500)
                ->rows(2)
                ->placeholder('Permanent address')
                ->disabled(fn ($livewire) => !self::isEditPage($livewire)),
        ];
    }

    public static function getSuynlLessonSchema(): array
    {
        return [
            Repeater::make('suynlLessonCompletions')
                ->relationship()
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextInput::make('lesson_number')
                                ->required()
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(10)
                                ->placeholder('Lesson # (1-10)'),
                            DatePicker::make('completion_date')
                                ->required()
                                ->native(false),
                        ])
                ])
                ->addActionLabel('+ Add Lesson')
                ->defaultItems(0)
                ->maxItems(10)
                ->reorderable(false)
                ->itemLabel(fn (array $state): ?string => isset($state['lesson_number']) ? "Lesson #{$state['lesson_number']}" : null),
        ];
    }

    public static function getSundayServiceSchema(): array
    {
        return [
            Repeater::make('sundayServiceCompletions')
                ->relationship()
                ->disabled(fn ($livewire) => !self::isEditPage($livewire))
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextInput::make('service_number')
                                ->required()
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(4)
                                ->placeholder('1-4')
                                ->disabled(fn ($livewire) => !self::isEditPage($livewire)),
                            DatePicker::make('attendance_date')
                                ->required()
                                ->native(false)
                                ->disabled(fn ($livewire) => !self::isEditPage($livewire)),
                        ])
                ])
                ->addActionLabel('+ Add Service')
                ->defaultItems(0)
                ->maxItems(4)
                ->itemLabel(fn (array $state): ?string => isset($state['service_number']) ? "Service #{$state['service_number']}" : 'New Service'),
        ];
    }

    public static function getCellGroupLessonSchema(): array
    {
        return [
            Repeater::make('cellGroupLessonCompletions')
                ->relationship()
                ->disabled(fn ($livewire) => !self::isEditPage($livewire))
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextInput::make('lesson_number')
                                ->required()
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(4)
                                ->placeholder('1-4')
                                ->disabled(fn ($livewire) => !self::isEditPage($livewire)),
                            DatePicker::make('completion_date')
                                ->required()
                                ->native(false)
                                ->disabled(fn ($livewire) => !self::isEditPage($livewire)),
                        ])
                ])
                ->addActionLabel('+ Add Cell Group Lesson')
                ->defaultItems(0)
                ->maxItems(4)
                ->itemLabel(fn (array $state): ?string => isset($state['lesson_number']) ? "Lesson #{$state['lesson_number']}" : 'New Lesson'),
        ];
    }
}
