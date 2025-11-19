<?php

namespace App\Filament\Resources\FieldPriceResource\Pages;

use App\Filament\Resources\FieldPriceResource;
use Filament\Resources\Pages\ListRecords;

class ListFieldPrices extends ListRecords
{
    protected static string $resource = FieldPriceResource::class;

    protected static ?string $title = 'Daftar Harga Lapangan';
}