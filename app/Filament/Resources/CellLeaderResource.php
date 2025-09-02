<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CellLeaderResource\Pages;
use App\Filament\Resources\CellLeaderResource\RelationManagers;
use App\Models\CellLeader;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CellLeaderResource extends Resource
{
    protected static ?string $model = CellLeader::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    
    protected static ?int $navigationSort = 4;

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
                //
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
            'index' => Pages\ListCellLeaders::route('/'),
            'create' => Pages\CreateCellLeader::route('/create'),
            'edit' => Pages\EditCellLeader::route('/{record}/edit'),
        ];
    }
}
