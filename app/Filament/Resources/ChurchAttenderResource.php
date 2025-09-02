<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChurchAttenderResource\Pages;
use App\Filament\Resources\ChurchAttenderResource\RelationManagers;
use App\Models\ChurchAttender;
use App\Models\NetworkLeader;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
use App\Services\ChurchMemberPromotionService;
use App\Services\PromotionRequirementsChecker;

class ChurchAttenderResource extends Resource
{
    protected static ?string $model = ChurchAttender::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?int $navigationSort = 1;

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
                                    ->options(function () {
                                        return NetworkLeader::all()->pluck('leader_name', 'leader_name')->toArray();
                                    })
                                    ->searchable()
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
            ->query(ChurchAttender::query()->notPromoted()) // Only show non-promoted members
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
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
                    ->getStateUsing(fn ($record) => $record->suynlLessonCompletions()->count() . '/10')
                    ->badge()
                    ->color(fn ($record) => $record->suynlLessonCompletions()->count() >= 10 ? 'success' : 'warning'),
                Tables\Columns\TextColumn::make('sunday_service_progress')
                    ->label('DCC')
                    ->getStateUsing(fn ($record) => $record->sundayServiceCompletions()->count() . '/4')
                    ->badge()
                    ->color(fn ($record) => $record->sundayServiceCompletions()->count() >= 4 ? 'success' : 'warning'),
                Tables\Columns\TextColumn::make('cell_group_progress')
                    ->label('CG')
                    ->getStateUsing(fn ($record) => $record->cellGroupLessonCompletions()->count() . '/4')
                    ->badge()
                    ->color(fn ($record) => $record->cellGroupLessonCompletions()->count() >= 4 ? 'success' : 'warning'),
                Tables\Columns\TextColumn::make('promotion_status')
                    ->label('Status')
                    ->getStateUsing(function ($record) {
                        $checker = app(PromotionRequirementsChecker::class);
                        $requirements = $checker->checkCellMemberRequirements($record);
                        return $checker->getRequirementsSummary($requirements);
                    })
                    ->html()
                    ->wrap(),
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
                Tables\Actions\Action::make('promote_to_cell_member')
                    ->label('Promote to Cell Member')
                    ->icon('heroicon-o-arrow-up-circle')
                    ->color('success')
                    ->visible(function ($record) {
                        $promotionService = app(ChurchMemberPromotionService::class);
                        return $promotionService->canPromoteToCellMember($record);
                    })
                    ->form([
                        Forms\Components\Select::make('cell_group_id')
                            ->options(\App\Models\CellGroup::with('cellGroupType')->get()->pluck('display_name', 'id'))
                            ->required()
                            ->searchable()
                            ->placeholder('Select a cell group...'),
                    ])
                    ->action(function ($record, array $data) {
                        $promotionService = app(ChurchMemberPromotionService::class);
                        
                        try {
                            $promotionService->promoteToCellMember($record, $data['cell_group_id']);
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Promotion Successful!')
                                ->body($record->full_name . ' has been promoted to Cell Member.')
                                ->success()
                                ->send();
                                
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Promotion Failed')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Promote to Cell Member')
                    ->modalDescription(fn ($record) => 'Are you sure you want to promote ' . $record->full_name . ' to Cell Member? This action will move them from the Church Attenders list to the Cell Members list.')
                    ->modalSubmitActionLabel('Promote'),
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
