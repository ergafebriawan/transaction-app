<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterPemasukanResource\Pages;
use App\Filament\Resources\MasterPemasukanResource\RelationManagers;
use App\Models\MasterPemasukan;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasterPemasukanResource extends Resource
{
    protected static ?string $model = MasterPemasukan::class;

    protected static ?string $navigationGroup = 'Master';
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Master Pemasukan';

    protected static ?string $navigationIcon = 'heroicon-s-inbox-arrow-down';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_pemasukan')->required()->placeholder('masukan nama pemasukan'),
                TextInput::make('deskripsi')->placeholder('masukan deskripsi'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_pemasukan')->searchable()->sortable(),
                TextColumn::make('deskripsi')
            ])
            ->filters([
                //
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
            'index' => Pages\ListMasterPemasukans::route('/'),
            'create' => Pages\CreateMasterPemasukan::route('/create'),
            'edit' => Pages\EditMasterPemasukan::route('/{record}/edit'),
        ];
    }
}
