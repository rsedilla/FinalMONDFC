<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CellGroupResource\Pages;
use App\Filament\Resources\CellGroupResource\RelationManagers;
use App\Models\CellGroup;
use App\Models\CellLeader;
use App\Models\G12Leader;
use App\Services\CellGroup\CellGroupLeaderService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CellGroupResource extends Resource
{
    protected static ?string $model = CellGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Cell Groups';

    protected static ?string $modelLabel = 'Cell Group';

    protected static ?string $pluralModelLabel = 'Cell Groups';

    protected static ?int $navigationSort = 40;

    protected static ?string $navigationGroup = 'Church Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Cell Group Information')
                    ->description('Basic information about the cell group')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('leader_selection')
                                    ->placeholder('Select a leader')
                                    ->searchable()
                                    ->options(function () {
                                        $leaderService = app(CellGroupLeaderService::class);
                                        return $leaderService->getLeaderOptions();
                                    })
                                    ->afterStateUpdated(function ($state, $set) {
                                        if (!$state) {
                                            $set('leader_id', null);
                                            $set('leader_type', null);
                                            return;
                                        }
                                        
                                        $leaderService = app(CellGroupLeaderService::class);
                                        $parsed = $leaderService->parseLeaderSelection($state);
                                        $set('leader_id', $parsed['leader_id']);
                                        $set('leader_type', $parsed['leader_type']);
                                    })
                                    ->dehydrated(false),
                                
                                Forms\Components\Hidden::make('leader_id'),
                                Forms\Components\Hidden::make('leader_type'),
                                Forms\Components\Select::make('cell_group_type_id')
                                    ->relationship('cellGroupType', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('e.g., Men\'s Group, Women\'s Group, Youth Group'),
                                    ]),
                            ]),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('meeting_day')
                                    ->options(function () {
                                        $leaderService = app(CellGroupLeaderService::class);
                                        return $leaderService->getDaysOfWeek();
                                    })
                                    ->required()
                                    ->searchable(),
                                Forms\Components\TimePicker::make('meeting_time')
                                    ->required()
                                    ->seconds(false),
                                Forms\Components\Textarea::make('location')
                                    ->required()
                                    ->maxLength(500)
                                    ->rows(3)
                                    ->placeholder('Enter full address or meeting location details'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::getTableColumns())
            ->defaultSort('meeting_day')
            ->filters(self::getTableFilters())
            ->actions(self::getTableActions())
            ->bulkActions(self::getTableBulkActions())
            ->recordUrl(null);
    }

    private static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('leader_name')
                ->label('Cell Leader')
                ->searchable()
                ->sortable()
                ->getStateUsing(fn ($record) => $record->getLeaderNameAttribute()),
            
            Tables\Columns\TextColumn::make('cellGroupType.name')
                ->label('Type')
                ->searchable()
                ->sortable()
                ->badge()
                ->color('primary'),
            
            Tables\Columns\TextColumn::make('meeting_day')
                ->label('Day')
                ->searchable()
                ->sortable()
                ->badge()
                ->color(function (string $state): string {
                    $leaderService = app(CellGroupLeaderService::class);
                    return $leaderService->getDayColors()[$state] ?? 'gray';
                }),
            
            Tables\Columns\TextColumn::make('meeting_time')
                ->label('Time')
                ->time('H:i')
                ->sortable(),
            
            Tables\Columns\TextColumn::make('location')
                ->label('Location')
                ->searchable()
                ->limit(50)
                ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                    $state = $column->getState();
                    return strlen($state) > 50 ? $state : null;
                }),
            
            Tables\Columns\TextColumn::make('cell_members_count')
                ->label('Members')
                ->counts('cellMembers')
                ->badge()
                ->color('success'),
            
            Tables\Columns\TextColumn::make('cell_leaders_count')
                ->label('Leaders')
                ->counts('cellLeaders')
                ->badge()
                ->color('warning'),
            
            Tables\Columns\TextColumn::make('display_name')
                ->label('Full Name')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
            
            Tables\Columns\TextColumn::make('created_at')
                ->label('Created')
                ->dateTime('M j, Y')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    private static function getTableFilters(): array
    {
        return [
            Tables\Filters\SelectFilter::make('cell_group_type_id')
                ->label('Group Type')
                ->relationship('cellGroupType', 'name')
                ->preload(),
            
            Tables\Filters\SelectFilter::make('meeting_day')
                ->label('Meeting Day')
                ->options(function () {
                    $leaderService = app(CellGroupLeaderService::class);
                    return $leaderService->getDaysOfWeek();
                }),
            
            Tables\Filters\Filter::make('has_members')
                ->label('Has Members')
                ->query(fn (Builder $query): Builder => $query->has('cellMembers')),
            
            Tables\Filters\Filter::make('has_leaders')
                ->label('Has Leaders')
                ->query(fn (Builder $query): Builder => $query->has('cellLeaders')),
        ];
    }

    private static function getTableActions(): array
    {
        return [
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ];
    }

    private static function getTableBulkActions(): array
    {
        return [
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ];
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CellMembersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCellGroups::route('/'),
            'create' => Pages\CreateCellGroup::route('/create'),
            'view' => Pages\ViewCellGroup::route('/{record}'),
            'edit' => Pages\EditCellGroup::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['cellGroupType.name', 'meeting_day', 'location'];
    }
}
