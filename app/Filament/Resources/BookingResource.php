<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Manajemen Booking';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Penyewa')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable(),
                Select::make('field_id')
                    ->label('Lapangan')
                    ->relationship('field', 'name')
                    ->required()
                    ->searchable()
                    ->live(), // Penting! Ini akan memicu event saat field dipilih

                DatePicker::make('date')
                    ->label('Tanggal Bermain')
                    ->required(),

                TimePicker::make('start_time')
                    ->label('Jam Mulai')
                    ->required()
                    ->live(), // Penting! Ini akan memicu event saat jam mulai diubah

                TextInput::make('duration')
                    ->label('Durasi (jam)')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(12)
                    ->live(), // Penting! Ini akan memicu event saat durasi diubah

                // Jam Selesai (diisi otomatis)
                TimePicker::make('end_time')
                    ->label('Jam Selesai')
                    ->disabled() // Dinonaktifkan agar tidak bisa diedit manual
                    ->dehydrated(false), // Penting! Agar nilai dikirim ke server

                // Total Harga (diisi otomatis)
                TextInput::make('total_price')
                    ->label('Total Harga')
                    ->disabled() // Dinonaktifkan agar tidak bisa diedit manual
                    ->numeric()
                    ->dehydrated(false), // Penting! Agar nilai dikirim ke server

                Select::make('status')
                    ->label('Status Booking')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),

                Select::make('payment_id')
                    ->label('Pembayaran')
                    ->relationship('payment', 'id')
                    ->nullable(),
            ])
            ->reactive(); // Penting! Ini membuat form reaktif terhadap perubahan
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Penyewa')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('field.name')
                    ->label('Lapangan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                TextColumn::make('start_time')
                    ->label('Jam Mulai')
                    ->time()
                    ->sortable(),
                TextColumn::make('duration')
                    ->label('Durasi (jam)')
                    ->sortable(),
                TextColumn::make('end_time')
                    ->label('Jam Selesai')
                    ->time()
                    ->sortable(),
                TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'cancelled' => 'danger',
                    })
                    ->sortable(),
                TextColumn::make('payment.status')
                    ->label('Status Pembayaran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'success' => 'success',
                        'failed' => 'danger',
                        // --- Perbaikan: Tambahkan case ini ---
                        'Belum Dibayar' => 'warning', // Atau 'gray', tergantung preferensi Anda
                    })
                    ->default('Belum Dibayar'),
            ])
            ->filters([
                //
            ])
            ->actions([
                \Filament\Tables\Actions\ViewAction::make(),
                \Filament\Tables\Actions\EditAction::make(),
                \Filament\Tables\Actions\DeleteAction::make(), // Tombol Delete per baris
            ])
            ->bulkActions([
                \Filament\Tables\Actions\BulkActionGroup::make([
                    \Filament\Tables\Actions\DeleteBulkAction::make(), // Tombol Bulk Delete
                ]),
            ])
            ->headerActions([
                \Filament\Tables\Actions\CreateAction::make(), // Tombol "New booking" di header
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
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'view' => Pages\ViewBooking::route('/{record}'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}