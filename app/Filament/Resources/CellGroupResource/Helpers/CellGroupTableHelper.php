<?php

namespace App\Filament\Resources\CellGroupResource\Helpers;

use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class CellGroupTableHelper
{
    public static function getDayColors(): array
    {
        return [
            'Sunday' => 'success',
            'Monday' => 'info',
            'Tuesday' => 'warning',
            'Wednesday' => 'danger',
            'Thursday' => 'secondary',
            'Friday' => 'primary',
            'Saturday' => 'gray',
        ];
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

    public static function getColumns(): array
    {
        return [
            TextColumn::make('leader_name')
                ->label('Cell Leader')
                ->searchable()
                ->sortable()
                ->getStateUsing(fn ($record) => $record->getLeaderNameAttribute()),
            
            TextColumn::make('cellGroupType.name')
                ->label('Type')
                ->searchable()
                ->sortable()
                ->badge()
                ->color('primary'),
            
            TextColumn::make('meeting_day')
                ->label('Day')
                ->searchable()
                ->sortable()
                ->badge()
                ->color(fn (string $state): string => self::getDayColors()[$state] ?? 'gray'),
            
            TextColumn::make('meeting_time')
                ->label('Time')
                ->time('H:i')
                ->sortable(),
            
            TextColumn::make('location')
                ->label('Location')
                ->searchable()
                ->limit(50)
                ->tooltip(function (TextColumn $column): ?string {
                    $state = $column->getState();
                    return strlen($state) > 50 ? $state : null;
                }),
            
            TextColumn::make('cell_members_count')
                ->label('Members')
                ->counts('cellMembers')
                ->badge()
                ->color('success'),
            
            TextColumn::make('cell_leaders_count')
                ->label('Leaders')
                ->counts('cellLeaders')
                ->badge()
                ->color('warning'),
            
            TextColumn::make('display_name')
                ->label('Full Name')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
            
            TextColumn::make('created_at')
                ->label('Created')
                ->dateTime('M j, Y')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function getFilters(): array
    {
        return [
            SelectFilter::make('cell_group_type_id')
                ->label('Group Type')
                ->relationship('cellGroupType', 'name')
                ->preload(),
            
            SelectFilter::make('meeting_day')
                ->label('Meeting Day')
                ->options(self::getDaysOfWeek()),
            
            Filter::make('has_members')
                ->label('Has Members')
                ->query(fn (Builder $query): Builder => $query->has('cellMembers')),
            
            Filter::make('has_leaders')
                ->label('Has Leaders')
                ->query(fn (Builder $query): Builder => $query->has('cellLeaders')),
        ];
    }

    public static function getActions(): array
    {
        return [
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ];
    }

    public static function getBulkActions(): array
    {
        return [
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ];
    }
}
