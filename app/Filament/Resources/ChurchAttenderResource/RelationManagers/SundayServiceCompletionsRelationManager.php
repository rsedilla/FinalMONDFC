<?php

namespace App\Filament\Resources\ChurchAttenderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SundayServiceCompletionsRelationManager extends RelationManager
{
    protected static string $relationship = 'sundayServiceCompletions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('service_number')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(4),
                Forms\Components\DatePicker::make('attendance_date')
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->maxLength(500)
                    ->rows(3),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('service_number')
            ->columns([
                Tables\Columns\TextColumn::make('service_number')
                    ->label('Service #')
                    ->sortable(),
                Tables\Columns\TextColumn::make('attendance_date')
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
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }
}
