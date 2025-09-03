<?php

namespace App\Filament\Resources\ChurchAttenderResource\Traits;

use App\Services\ChurchMemberPromotionService;
use App\Services\PromotionRequirementsChecker;
use Filament\Forms;
use Filament\Tables;

trait ChurchAttenderTableTrait
{
    /**
     * Get table columns
     */
    protected static function getTableColumns(): array
    {
        return [
            ...static::getNameColumns(),
            static::getNetworkColumn(),
            ...static::getProgressColumns(),
            static::getStatusColumn(),
            static::getCreatedAtColumn(),
        ];
    }

    /**
     * Name columns for the table
     */
    protected static function getNameColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('first_name')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('last_name')
                ->searchable()
                ->sortable(),
        ];
    }

    /**
     * Network column
     */
    protected static function getNetworkColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('network')
            ->badge()
            ->color(fn (string $state): string => match ($state) {
                'mens' => 'blue',
                'womens' => 'pink',
                default => 'gray',
            });
    }

    /**
     * Progress columns
     */
    protected static function getProgressColumns(): array
    {
        return [
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
        ];
    }

    /**
     * Status column
     */
    protected static function getStatusColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('promotion_status')
            ->label('Status')
            ->getStateUsing(function ($record) {
                $checker = app(PromotionRequirementsChecker::class);
                $requirements = $checker->checkCellMemberRequirements($record);
                return $checker->getRequirementsSummary($requirements);
            })
            ->html()
            ->wrap();
    }

    /**
     * Created at column
     */
    protected static function getCreatedAtColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('created_at')
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    /**
     * Table filters
     */
    protected static function getTableFilters(): array
    {
        return [
            Tables\Filters\SelectFilter::make('network')
                ->options([
                    'mens' => 'Mens',
                    'womens' => 'Womens'
                ]),
            Tables\Filters\SelectFilter::make('civil_status_id')
                ->relationship('civilStatus', 'name')
                ->label('Civil Status'),
        ];
    }

    /**
     * Table actions
     */
    protected static function getTableActions(): array
    {
        return [
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            static::getPromotionAction(),
        ];
    }

    /**
     * Promotion action
     */
    protected static function getPromotionAction(): Tables\Actions\Action
    {
        return Tables\Actions\Action::make('promote_to_cell_member')
            ->label('Promote to Cell Member')
            ->icon('heroicon-o-arrow-up-circle')
            ->color('success')
            ->visible(function ($record) {
                // Only show for non-promoted attenders who meet all requirements
                if ($record->isPromoted()) {
                    return false;
                }
                
                $promotionService = app(ChurchMemberPromotionService::class);
                return $promotionService->canPromoteToCellMember($record);
            })
            ->form([
                Forms\Components\Select::make('cell_group_id')
                    ->options(function () {
                        return \App\Models\CellGroup::with(['cellGroupType', 'leader'])->get()
                            ->mapWithKeys(function ($cellGroup) {
                                $groupType = $cellGroup->cellGroupType ? $cellGroup->cellGroupType->name : 'Unknown Type';
                                $leaderName = $cellGroup->getLeaderNameAttribute();
                                $cellGroupId = $cellGroup->CellGroupID ?: 'No ID';
                                
                                $displayText = "{$groupType} - {$leaderName} - {$cellGroupId}";
                                
                                return [$cellGroup->id => $displayText];
                            })->toArray();
                    })
                    ->required()
                    ->searchable()
                    ->placeholder('Search cell groups by type, leader name, or CellGroupID...'),
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
            ->modalSubmitActionLabel('Promote');
    }
}
