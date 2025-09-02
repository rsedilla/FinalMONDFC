<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CellMemberResource\Pages;
use App\Filament\Resources\CellMemberResource\RelationManagers;
use App\Models\CellMember;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CellMemberResource extends Resource
{
    protected static ?string $model = CellMember::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('church_attender_id')
                    ->relationship('churchAttender', 'first_name')
                    ->required()
                    ->searchable()
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name),
                Forms\Components\Select::make('cell_group_id')
                    ->relationship('cellGroup', 'meeting_day')
                    ->required()
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->display_name ?? $record->meeting_day),
                Forms\Components\Select::make('training_progress_id')
                    ->relationship('trainingProgress', 'id')
                    ->required()
                    ->getOptionLabelFromRecordUsing(fn ($record) => 'Training Progress #' . $record->id),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(CellMember::query()->withChurchAttender()) // Eager load for performance
            ->columns([
                Tables\Columns\TextColumn::make('churchAttender.first_name')
                    ->label('First Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('churchAttender.middle_name')
                    ->label('Middle Name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('churchAttender.last_name')
                    ->label('Last Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('churchAttender.email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('churchAttender.network')
                    ->label('Network')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'mens' => 'blue',
                        'womens' => 'pink',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('cellGroup.display_name')
                    ->label('Cell Group')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('churchAttender.promoted_at')
                    ->label('Promoted On')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListCellMembers::route('/'),
            'create' => Pages\CreateCellMember::route('/create'),
            'edit' => Pages\EditCellMember::route('/{record}/edit'),
        ];
    }
}
