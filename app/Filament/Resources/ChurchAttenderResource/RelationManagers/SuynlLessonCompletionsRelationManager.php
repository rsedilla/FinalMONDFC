<?php

namespace App\Filament\Resources\ChurchAttenderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SuynlLessonCompletionsRelationManager extends RelationManager
{
    protected static string $relationship = 'suynlLessonCompletions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('lesson_number')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(12),
                Forms\Components\DatePicker::make('completion_date')
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->maxLength(500)
                    ->rows(3),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('lesson_number')
            ->columns([
                Tables\Columns\TextColumn::make('lesson_number')
                    ->label('Lesson #')
                    ->sortable(),
                Tables\Columns\TextColumn::make('completion_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('notes')
                    ->limit(50),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->using(function (array $data): \App\Models\SuynlLessonCompletion {
                        // Use updateOrCreate to prevent duplicates
                        return \App\Models\SuynlLessonCompletion::updateOrCreate(
                            [
                                'church_attender_id' => $this->ownerRecord->id,
                                'lesson_number' => $data['lesson_number'],
                            ],
                            [
                                'completion_date' => $data['completion_date'],
                                'notes' => $data['notes'] ?? null,
                            ]
                        );
                    })
                    ->successNotificationTitle('Lesson completion saved successfully'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
