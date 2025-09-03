<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChurchAttenderResource\Pages;
use App\Filament\Resources\ChurchAttenderResource\RelationManagers;
use App\Filament\Resources\ChurchAttenderResource\Traits\ChurchAttenderFormTrait;
use App\Filament\Resources\ChurchAttenderResource\Traits\ChurchAttenderTableTrait;
use App\Models\ChurchAttender;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ChurchAttenderResource extends Resource
{
    use ChurchAttenderFormTrait, ChurchAttenderTableTrait;

    protected static ?string $model = ChurchAttender::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            static::getPersonalInformationSection(),
            static::getSuynlLessonSection(),
            static::getSundayServiceSection(),
            static::getCellGroupLessonSection(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(ChurchAttender::query()->notPromoted())
            ->columns(static::getTableColumns())
            ->filters(static::getTableFilters())
            ->actions(static::getTableActions())
            ->recordUrl(
                fn (Model $record): string => Pages\ViewChurchAttender::getUrl([$record->id])
            );
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
            'index' => Pages\ListChurchAttenders::route('/'),
            'create' => Pages\CreateChurchAttender::route('/create'),
            'view' => Pages\ViewChurchAttender::route('/{record}'),
            'edit' => Pages\EditChurchAttender::route('/{record}/edit'),
        ];
    }
}
