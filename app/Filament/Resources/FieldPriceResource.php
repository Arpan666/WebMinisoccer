<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FieldPriceResource\Pages;
use App\Models\FieldPrice;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class FieldPriceResource extends Resource
{
    protected static ?string $model = FieldPrice::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Data Lapangan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('field_id')
                    ->label('Lapangan')
                    ->relationship('field', 'name') // Relasi ke Field
                    ->required()
                    ->searchable(),
                TimePicker::make('start_time')
                    ->label('Jam Mulai')
                    ->required(),
                TimePicker::make('end_time')
                    ->label('Jam Selesai')
                    ->required(),
                TextInput::make('price_per_hour')
                    ->label('Harga per Jam')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('field.name')
                    ->label('Lapangan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('start_time')
                    ->label('Jam Mulai')
                    ->time()
                    ->sortable(),
                TextColumn::make('end_time')
                    ->label('Jam Selesai')
                    ->time()
                    ->sortable(),
                TextColumn::make('price_per_hour')
                    ->label('Harga per Jam')
                    ->money('IDR')
                    ->sortable(),
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
                \Filament\Tables\Actions\CreateAction::make(), // Tombol "New field price" di header
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
            'index' => Pages\ListFieldPrices::route('/'),
            'create' => Pages\CreateFieldPrice::route('/create'),
            'view' => Pages\ViewFieldPrice::route('/{record}'),
            'edit' => Pages\EditFieldPrice::route('/{record}/edit'),
        ];
    }
}