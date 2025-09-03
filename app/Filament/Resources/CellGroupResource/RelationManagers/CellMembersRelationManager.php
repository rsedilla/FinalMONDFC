<?php

namespace App\Filament\Resources\CellGroupResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CellMembersRelationManager extends RelationManager
{
    protected static string $relationship = 'cellMembers';

    protected static ?string $title = 'Cell Members';

    protected static ?string $modelLabel = 'Member';

    protected static ?string $pluralModelLabel = 'Members';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('churchAttender.first_name')
            ->columns([
                Tables\Columns\TextColumn::make('churchAttender.first_name')
                    ->label('First Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('churchAttender.last_name')
                    ->label('Last Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('churchAttender.email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-envelope'),
                Tables\Columns\TextColumn::make('churchAttender.phone_number')
                    ->label('Phone')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-phone'),
                Tables\Columns\TextColumn::make('training_progress')
                    ->label('Training Progress')
                    ->getStateUsing(function ($record) {
                        if ($record->trainingProgress) {
                            return $record->trainingProgress->current_level;
                        }
                        return 'Not Started';
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Not Started' => 'gray',
                        'Level 1' => 'warning',
                        'Level 2' => 'info',
                        'Level 3' => 'success',
                        'Completed' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Joined')
                    ->date('M j, Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('training_level')
                    ->label('Training Level')
                    ->options([
                        'Level 1' => 'Level 1',
                        'Level 2' => 'Level 2', 
                        'Level 3' => 'Level 3',
                        'Completed' => 'Completed',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (!empty($data['value'])) {
                            return $query->whereHas('trainingProgress', function ($q) use ($data) {
                                $q->where('current_level', $data['value']);
                            });
                        }
                        return $query;
                    }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalHeading('Add Member to Cell Group'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No Members Yet')
            ->emptyStateDescription('Add members to this cell group using the "Create" button above.')
            ->emptyStateIcon('heroicon-o-user-plus');
    }
}
