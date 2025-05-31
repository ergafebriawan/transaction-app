<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterPengeluaranResource\Pages;
use App\Filament\Resources\MasterPengeluaranResource\RelationManagers;
use App\Models\MasterPengeluaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasterPengeluaranResource extends Resource
{
    protected static ?string $model = MasterPengeluaran::class;

    protected static ?string $navigationGroup = 'Master';
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-s-arrow-trending-up';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_pengeluaran')->required()->placeholder('masukan nama pengeluaran'),
                TextInput::make('deskripsi')->placeholder('masukan deskripsi'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_pengeluaran')->searchable()->sortable(),
                TextColumn::make('deskripsi')
            ])
            ->filters([
                
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
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
            'index' => Pages\ListMasterPengeluarans::route('/'),
            'create' => Pages\CreateMasterPengeluaran::route('/create'),
            'edit' => Pages\EditMasterPengeluaran::route('/{record}/edit'),
        ];
    }
}
