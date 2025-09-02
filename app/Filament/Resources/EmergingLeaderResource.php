<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmergingLeaderResource\Pages;
use App\Filament\Resources\EmergingLeaderResource\RelationManagers;
use App\Models\EmergingLeader;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmergingLeaderResource extends Resource
{
    protected static ?string $model = EmergingLeader::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    
    protected static ?int $navigationSort = 3;

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
            'index' => Pages\ListEmergingLeaders::route('/'),
            'create' => Pages\CreateEmergingLeader::route('/create'),
            'edit' => Pages\EditEmergingLeader::route('/{record}/edit'),
        ];
    }
}
