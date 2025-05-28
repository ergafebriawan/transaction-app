<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransaksiPengeluaranResource\Pages;
use App\Filament\Resources\TransaksiPengeluaranResource\RelationManagers;
use App\Models\TransaksiPengeluaran;
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

class TransaksiPengeluaranResource extends Resource
{
    protected static ?string $model = TransaksiPengeluaran::class;

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?string $navigationIcon = 'heroicon-o-arrow-right-start-on-rectangle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('user_id')
                    ->default(Auth::id())
                    ->required()
                    ->dehydrated(true),
                Select::make('master_pengeluaran_id')
                    ->relationship('masterPengeluaran', 'nama_pengeluaran')
                    ->label('Jenis Pengeluaran')
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
                TextColumn::make('masterPengeluaran.nama_pengeluaran')
                    ->label('Nama Pengeluaran')
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
                Tables\Filters\SelectFilter::make('master_pengeluaran_id')
                    ->relationship('masterPengeluaran', 'nama_pengeluaran')
                    ->label('Filter Jenis Pengeluaran'),
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
            'index' => Pages\ListTransaksiPengeluarans::route('/'),
            'create' => Pages\CreateTransaksiPengeluaran::route('/create'),
            'edit' => Pages\EditTransaksiPengeluaran::route('/{record}/edit'),
        ];
    }
}
