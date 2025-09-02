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
            'index' => Pages\ListCellMembers::route('/'),
            'create' => Pages\CreateCellMember::route('/create'),
            'edit' => Pages\EditCellMember::route('/{record}/edit'),
        ];
    }
}
