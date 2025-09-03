<?php

namespace App\Filament\Resources;

use App\Filament\Resources\G12LeaderResource\Pages;
use App\Filament\Resources\G12LeaderResource\RelationManagers;
use App\Models\G12Leader;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class G12LeaderResource extends Resource
{
    protected static ?string $model = G12Leader::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('churchAttender.first_name')
                    ->label('First Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('churchAttender.last_name')
                    ->label('Last Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Full Name')
                    ->getStateUsing(fn ($record) => $record->getFullNameAttribute())
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('churchAttender.email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('cellGroup.CellGroupID')
                    ->label('Cell Group ID')
                    ->searchable()
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('cellGroup.cellGroupType.name')
                    ->label('Cell Group Type')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('trainingProgress.trainingProgressType.name')
                    ->label('Training Progress')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'SUYNL' => 'warning',
                        'LIFE CLASS' => 'info',
                        'ENCOUNTER' => 'primary',
                        'SOL 1' => 'secondary',
                        'SOL 2' => 'secondary',
                        'SOL 3' => 'secondary',
                        'SOL GRAD' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Added')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('cell_group_id')
                    ->label('Cell Group')
                    ->relationship('cellGroup', 'CellGroupID')
                    ->preload(),
                Tables\Filters\SelectFilter::make('training_progress_id')
                    ->label('Training Progress')
                    ->relationship('trainingProgress.trainingProgressType', 'name')
                    ->preload(),
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
            ->defaultSort('churchAttender.first_name');
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
            'index' => Pages\ListG12Leaders::route('/'),
            'create' => Pages\CreateG12Leader::route('/create'),
            'edit' => Pages\EditG12Leader::route('/{record}/edit'),
        ];
    }
}
