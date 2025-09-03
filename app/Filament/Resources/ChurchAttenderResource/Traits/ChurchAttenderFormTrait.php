<?php

namespace App\Filament\Resources\ChurchAttenderResource\Traits;

use App\Models\NetworkLeader;
use Filament\Forms;

trait ChurchAttenderFormTrait
{
    /**
     * Personal Information form section
     */
    protected static function getPersonalInformationSection(): Forms\Components\Section
    {
        return Forms\Components\Section::make('Personal Information')
            ->schema([
                static::getNameFieldsGrid(),
                static::getPersonalDetailsGrid(),
                static::getContactInfoGrid(),
                static::getNetworkAndSocialGrid(),
                static::getAddressFields(),
            ]);
    }

    /**
     * Name fields grid
     */
    protected static function getNameFieldsGrid(): Forms\Components\Grid
    {
        return Forms\Components\Grid::make(3)
            ->schema([
                Forms\Components\TextInput::make('first_name', 'First Name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Juan')
                    ->disabled(fn ($livewire) => !($livewire instanceof \App\Filament\Resources\ChurchAttenderResource\Pages\EditChurchAttender)),
                Forms\Components\TextInput::make('middle_name', 'Middle Name')
                    ->maxLength(255)
                    ->placeholder('Dela Cruz')
                    ->disabled(fn ($livewire) => !($livewire instanceof \App\Filament\Resources\ChurchAttenderResource\Pages\EditChurchAttender)),
                Forms\Components\TextInput::make('last_name', 'Last Name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Santos')
                    ->disabled(fn ($livewire) => !($livewire instanceof \App\Filament\Resources\ChurchAttenderResource\Pages\EditChurchAttender)),
            ]);
    }

    /**
     * Personal details grid
     */
    protected static function getPersonalDetailsGrid(): Forms\Components\Grid
    {
        return Forms\Components\Grid::make(2)
            ->schema([
                Forms\Components\DatePicker::make('birthday', 'Birthday')
                    ->required()
                    ->disabled(fn ($livewire) => !($livewire instanceof \App\Filament\Resources\ChurchAttenderResource\Pages\EditChurchAttender)),
                Forms\Components\Select::make('civil_status_id', 'Civil Status')
                    ->relationship('civilStatus', 'name')
                    ->required()
                    ->disabled(fn ($livewire) => !($livewire instanceof \App\Filament\Resources\ChurchAttenderResource\Pages\EditChurchAttender)),
            ]);
    }

    /**
     * Contact information grid
     */
    protected static function getContactInfoGrid(): Forms\Components\Grid
    {
        return Forms\Components\Grid::make(2)
            ->schema([
                Forms\Components\TextInput::make('email', 'Email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->placeholder('juan@email.com')
                    ->disabled(fn ($livewire) => !($livewire instanceof \App\Filament\Resources\ChurchAttenderResource\Pages\EditChurchAttender)),
                Forms\Components\TextInput::make('phone_number', 'Phone Number')
                    ->tel()
                    ->placeholder('0917-871-6509')
                    ->maxLength(255)
                    ->disabled(fn ($livewire) => !($livewire instanceof \App\Filament\Resources\ChurchAttenderResource\Pages\EditChurchAttender)),
            ]);
    }

    /**
     * Network and social media grid
     */
    protected static function getNetworkAndSocialGrid(): Forms\Components\Grid
    {
        return Forms\Components\Grid::make(2)
            ->schema([
                Forms\Components\TextInput::make('social_media_account', 'Social Media Account')
                    ->maxLength(255)
                    ->placeholder('Facebook, Instagram, etc.')
                    ->disabled(fn ($livewire) => !($livewire instanceof \App\Filament\Resources\ChurchAttenderResource\Pages\EditChurchAttender)),
                Forms\Components\Select::make('network', 'Network')
                    ->options(function () {
                        return NetworkLeader::all()->pluck('leader_name', 'leader_name')->toArray();
                    })
                    ->searchable()
                    ->required(),
            ]);
    }

    /**
     * Address fields
     */
    protected static function getAddressFields(): array
    {
        return [
            Forms\Components\Textarea::make('present_address', 'Present Address')
                ->required()
                ->maxLength(500)
                ->rows(2)
                ->placeholder('Current address')
                ->disabled(fn ($livewire) => !($livewire instanceof \App\Filament\Resources\ChurchAttenderResource\Pages\EditChurchAttender)),
            Forms\Components\Textarea::make('permanent_address', 'Permanent Address')
                ->required()
                ->maxLength(500)
                ->rows(2)
                ->placeholder('Permanent address')
                ->disabled(fn ($livewire) => !($livewire instanceof \App\Filament\Resources\ChurchAttenderResource\Pages\EditChurchAttender)),
        ];
    }

    /**
     * SUYNL Lesson section
     */
    protected static function getSuynlLessonSection(): Forms\Components\Section
    {
        return Forms\Components\Section::make('SUYNL Lesson Completions')
            ->description('Track progress through 10 SUYNL lessons')
            ->icon('heroicon-o-academic-cap')
            ->collapsible()
            ->schema([
                Forms\Components\Repeater::make('suynlLessonCompletions')
                    ->relationship()
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('lesson_number')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(10)
                                    ->placeholder('Lesson # (1-10)'),
                                Forms\Components\DatePicker::make('completion_date')
                                    ->required()
                                    ->native(false),
                            ])
                    ])
                    ->addActionLabel('+ Add Lesson')
                    ->defaultItems(0)
                    ->maxItems(10)
                    ->reorderable(false)
                    ->itemLabel(fn (array $state): ?string => isset($state['lesson_number']) ? "Lesson #{$state['lesson_number']}" : null),
            ]);
    }

    /**
     * Sunday Service section
     */
    protected static function getSundayServiceSection(): Forms\Components\Section
    {
        return Forms\Components\Section::make('Sunday Service Completions')
            ->description('Track attendance for 4 required Sunday services')
            ->icon('heroicon-o-building-library')
            ->collapsible()
            ->schema([
                Forms\Components\Repeater::make('sundayServiceCompletions')
                    ->relationship()
                    ->disabled(fn ($livewire) => !($livewire instanceof \App\Filament\Resources\ChurchAttenderResource\Pages\EditChurchAttender))
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('service_number')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(4)
                                    ->placeholder('1-4')
                                    ->disabled(fn ($livewire) => !($livewire instanceof \App\Filament\Resources\ChurchAttenderResource\Pages\EditChurchAttender)),
                                Forms\Components\DatePicker::make('attendance_date')
                                    ->required()
                                    ->native(false)
                                    ->disabled(fn ($livewire) => !($livewire instanceof \App\Filament\Resources\ChurchAttenderResource\Pages\EditChurchAttender)),
                            ])
                    ])
                    ->addActionLabel('+ Add Service')
                    ->defaultItems(0)
                    ->maxItems(4)
                    ->itemLabel(fn (array $state): ?string => isset($state['service_number']) ? "Service #{$state['service_number']}" : 'New Service'),
            ]);
    }

    /**
     * Cell Group Lesson section
     */
    protected static function getCellGroupLessonSection(): Forms\Components\Section
    {
        return Forms\Components\Section::make('Cell Group Lesson Completions')
            ->description('Track progress through 4 cell group lessons')
            ->icon('heroicon-o-user-group')
            ->collapsible()
            ->schema([
                Forms\Components\Repeater::make('cellGroupLessonCompletions')
                    ->relationship()
                    ->disabled(fn ($livewire) => !($livewire instanceof \App\Filament\Resources\ChurchAttenderResource\Pages\EditChurchAttender))
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('lesson_number')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(4)
                                    ->placeholder('1-4')
                                    ->disabled(fn ($livewire) => !($livewire instanceof \App\Filament\Resources\ChurchAttenderResource\Pages\EditChurchAttender)),
                                Forms\Components\DatePicker::make('completion_date')
                                    ->required()
                                    ->native(false)
                                    ->disabled(fn ($livewire) => !($livewire instanceof \App\Filament\Resources\ChurchAttenderResource\Pages\EditChurchAttender)),
                            ])
                    ])
                    ->addActionLabel('+ Add Cell Group Lesson')
                    ->defaultItems(0)
                    ->maxItems(4)
                    ->itemLabel(fn (array $state): ?string => isset($state['lesson_number']) ? "Lesson #{$state['lesson_number']}" : 'New Lesson'),
            ]);
    }
}
