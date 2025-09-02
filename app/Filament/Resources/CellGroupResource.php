<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CellGroupResource\Pages;
use App\Filament\Resources\CellGroupResource\RelationManagers;
use App\Models\CellGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Enums\FontWeight;

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
                                Forms\Components\TextInput::make('cell_leader', 'Cell Leader')
                                    ->maxLength(255)
                                    ->placeholder('Enter cell leader name'),
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
                                    ->options([
                                        'Monday' => 'Monday',
                                        'Tuesday' => 'Tuesday',
                                        'Wednesday' => 'Wednesday',
                                        'Thursday' => 'Thursday',
                                        'Friday' => 'Friday',
                                        'Saturday' => 'Saturday',
                                        'Sunday' => 'Sunday',
                                    ])
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
            ->columns([
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
                    ->color(fn (string $state): string => match ($state) {
                        'Sunday' => 'success',
                        'Monday' => 'info',
                        'Tuesday' => 'warning',
                        'Wednesday' => 'danger',
                        'Thursday' => 'secondary',
                        'Friday' => 'primary',
                        'Saturday' => 'gray',
                        default => 'gray',
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
            ])
            ->defaultSort('meeting_day')
            ->filters([
                Tables\Filters\SelectFilter::make('cell_group_type_id')
                    ->label('Group Type')
                    ->relationship('cellGroupType', 'name')
                    ->preload(),
                
                Tables\Filters\SelectFilter::make('meeting_day')
                    ->label('Meeting Day')
                    ->options([
                        'Monday' => 'Monday',
                        'Tuesday' => 'Tuesday',
                        'Wednesday' => 'Wednesday',
                        'Thursday' => 'Thursday',
                        'Friday' => 'Friday',
                        'Saturday' => 'Saturday',
                        'Sunday' => 'Sunday',
                    ]),
                
                Tables\Filters\Filter::make('has_members')
                    ->label('Has Members')
                    ->query(fn (Builder $query): Builder => $query->has('cellMembers')),
                
                Tables\Filters\Filter::make('has_leaders')
                    ->label('Has Leaders')
                    ->query(fn (Builder $query): Builder => $query->has('cellLeaders')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl(null);
    }

    public static function getRelations(): array
    {
        return [
            // TODO: Add relation managers when created
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCellGroups::route('/'),
            'create' => Pages\CreateCellGroup::route('/create'),
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
