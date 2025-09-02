<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrainingProgressResource\Pages;
use App\Models\TrainingProgress;
use App\Models\ChurchAttender;
use App\Models\TrainingProgressType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TrainingProgressResource extends Resource
{
    protected static ?string $model = TrainingProgress::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?int $navigationSort = 41;
    protected static ?string $navigationLabel = 'Training Progress';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('church_attender_id')
                ->relationship('churchAttender', 'first_name')
                ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name)
                ->searchable()
                ->required(),
            Forms\Components\Select::make('training_progress_type_id')
                ->relationship('trainingProgressType', 'name')
                ->searchable()
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('churchAttender.full_name')
                ->label('Church Member')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('trainingProgressType.name')
                ->label('Training Type')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Added On')
                ->dateTime()
                ->sortable(),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('training_progress_type_id')
                ->relationship('trainingProgressType', 'name')
                ->label('Training Type'),
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
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrainingProgresses::route('/'),
            'create' => Pages\CreateTrainingProgress::route('/create'),
            'edit' => Pages\EditTrainingProgress::route('/{record}/edit'),
        ];
    }
}
