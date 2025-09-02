<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChurchAttenderResource\Pages;
use App\Filament\Resources\ChurchAttenderResource\RelationManagers;
use App\Models\ChurchAttender;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

class ChurchAttenderResource extends Resource
{
    protected static ?string $model = ChurchAttender::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Personal Information')
                    ->schema([
                        Forms\Components\Grid::make(3)
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
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('birthday', 'Birthday')
                                    ->required()
                                    ->disabled(fn ($livewire) => !($livewire instanceof \App\Filament\Resources\ChurchAttenderResource\Pages\EditChurchAttender)),
                                Forms\Components\Select::make('civil_status_id', 'Civil Status')
                                    ->relationship('civilStatus', 'name')
                                    ->required()
                                    ->disabled(fn ($livewire) => !($livewire instanceof \App\Filament\Resources\ChurchAttenderResource\Pages\EditChurchAttender)),
                            ]),
                        Forms\Components\Grid::make(2)
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
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('social_media_account', 'Social Media Account')
                                    ->maxLength(255)
                                    ->placeholder('Facebook, Instagram, etc.')
                                    ->disabled(fn ($livewire) => !($livewire instanceof \App\Filament\Resources\ChurchAttenderResource\Pages\EditChurchAttender)),
                                Forms\Components\Select::make('network', 'Network')
                                    ->options([
                                        'mens' => 'Mens',
                                        'womens' => 'Womens'
                                    ])
                                    ->required()
                                    ->disabled(fn ($livewire) => !($livewire instanceof \App\Filament\Resources\ChurchAttenderResource\Pages\EditChurchAttender)),
                            ]),
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
                    ]),
                
                Forms\Components\Section::make('SUYNL Lesson Completions')
                    ->description('Track progress through 10 SUYNL lessons')
                    ->icon('heroicon-o-academic-cap')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Repeater::make('suynlLessonCompletions')
                            ->relationship()
                            ->disabled(fn ($livewire) => !($livewire instanceof \App\Filament\Resources\ChurchAttenderResource\Pages\EditChurchAttender))
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('lesson_number')
                                            ->required()
                                            ->numeric()
                                            ->minValue(1)
                                            ->maxValue(10)
                                            ->placeholder('1-10')
                                            ->disabled(fn ($livewire) => !($livewire instanceof \App\Filament\Resources\ChurchAttenderResource\Pages\EditChurchAttender)),
                                        Forms\Components\DatePicker::make('completion_date')
                                            ->required()
                                            ->native(false)
                                            ->disabled(fn ($livewire) => !($livewire instanceof \App\Filament\Resources\ChurchAttenderResource\Pages\EditChurchAttender)),
                                    ])
                            ])
                            ->addActionLabel('+ Add Lesson')
                            ->defaultItems(0)
                            ->maxItems(10)
                            ->itemLabel(fn (array $state): ?string => isset($state['lesson_number']) ? "Lesson #{$state['lesson_number']}" : 'New Lesson'),
                    ]),
                
                Forms\Components\Section::make('Sunday Service Completions')
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
                    ]),
                Forms\Components\Section::make('Cell Group Lesson Completions')
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
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('middle_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('network')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'mens' => 'blue',
                        'womens' => 'pink',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('suynl_progress')
                    ->label('SUYNL')
                    ->getStateUsing(fn ($record) => $record->suynlLessonCompletions()->count() . '/10'),
                    Tables\Columns\TextColumn::make('sunday_service_progress')
                        ->label('DCC')
                        ->getStateUsing(fn ($record) => $record->sundayServiceCompletions()->count() . '/4'),
                    Tables\Columns\TextColumn::make('cell_group_progress')
                        ->label('CG')
                        ->getStateUsing(fn ($record) => $record->cellGroupLessonCompletions()->count() . '/4'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('network')
                    ->options([
                        'mens' => 'Mens',
                        'womens' => 'Womens'
                    ]),
                Tables\Filters\SelectFilter::make('civil_status_id')
                    ->relationship('civilStatus', 'name')
                    ->label('Civil Status'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->recordUrl(
                fn (Model $record): string => Pages\ViewChurchAttender::getUrl([$record->id])
            );
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
            'index' => Pages\ListChurchAttenders::route('/'),
            'create' => Pages\CreateChurchAttender::route('/create'),
            'view' => Pages\ViewChurchAttender::route('/{record}'),
            'edit' => Pages\EditChurchAttender::route('/{record}/edit'),
        ];
    }
}
