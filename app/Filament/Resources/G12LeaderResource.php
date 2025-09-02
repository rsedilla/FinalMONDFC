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
            'index' => Pages\ListG12Leaders::route('/'),
            'create' => Pages\CreateG12Leader::route('/create'),
            'edit' => Pages\EditG12Leader::route('/{record}/edit'),
        ];
    }
}
