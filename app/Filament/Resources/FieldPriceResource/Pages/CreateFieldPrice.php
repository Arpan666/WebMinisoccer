<?php

namespace App\Filament\Resources\FieldPriceResource\Pages;

use App\Filament\Resources\FieldPriceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFieldPrice extends CreateRecord
{
    protected static string $resource = FieldPriceResource::class;

    protected static ?string $title = 'Tambah Harga Lapangan Baru';
}