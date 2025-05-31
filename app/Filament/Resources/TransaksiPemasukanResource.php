<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransaksiPemasukanResource\Pages;
use App\Filament\Resources\TransaksiPemasukanResource\RelationManagers;
use App\Models\TransaksiPemasukan;
use App\Models\MasterPemasukan;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class TransaksiPemasukanResource extends Resource
{
    protected static ?string $model = TransaksiPemasukan::class;

    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Transaksi Pemasukan';
    protected static ?string $title = 'Transaksi Pemasukan';

    protected static ?string $navigationIcon = 'heroicon-o-arrow-right-end-on-rectangle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('user_id')
                    ->default(Auth::id())
                    ->required()
                    ->dehydrated(true),
                Select::make('master_pemasukan_id')
                    ->relationship('masterPemasukan', 'nama_pemasukan')
                    ->label('Jenis Pemasukan')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->autofocus(),
                TextInput::make('jumlah')
                    ->numeric()
                    ->prefix('Rp')
                    ->step(0.01)
                    ->required(),
                DateTimePicker::make('tanggal_transaksi')
                    ->required()
                    ->default(now()),
                Textarea::make('catatan')
                    ->nullable()
                    ->rows(3)
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal_transaksi')
                    ->date()
                    ->sortable()
                    ->label('Tanggal'),
                TextColumn::make('masterPemasukan.nama_pemasukan')
                    ->label('Nama Pemasukan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->numeric()
                    ->prefix('Rp ')
                    ->sortable(),
                TextColumn::make('user.name') // Tetap tampilkan nama user di tabel
                    ->label('User')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('catatan')
                    ->limit(50) // Batasi panjang teks catatan
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }
                        return $state;
                    })
                    ->label('Catatan'),
            ])
            ->filters([
                Tables\Filters\Filter::make('tanggal')
                    ->form([
                        Forms\Components\DatePicker::make('from_date'),
                        Forms\Components\DatePicker::make('to_date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from_date'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_transaksi', '>=', $date),
                            )
                            ->when(
                                $data['to_date'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal_transaksi', '<=', $date),
                            );
                    }),
                Tables\Filters\SelectFilter::make('master_pemasukan_id')
                    ->relationship('masterPemasukan', 'nama_pemasukan')
                    ->label('Filter Jenis Pemasukan'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListTransaksiPemasukans::route('/'),
            'create' => Pages\CreateTransaksiPemasukan::route('/create'),
            'edit' => Pages\EditTransaksiPemasukan::route('/{record}/edit'),
        ];
    }
}
