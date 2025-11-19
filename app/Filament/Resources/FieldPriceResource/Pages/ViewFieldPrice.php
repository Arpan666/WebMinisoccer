<?php

namespace App\Filament\Resources\FieldPriceResource\Pages;

use App\Filament\Resources\FieldPriceResource;
use Filament\Resources\Pages\ViewRecord;

class ViewFieldPrice extends ViewRecord
{
    protected static string $resource = FieldPriceResource::class;

    protected static ?string $title = 'Lihat Harga Lapangan';
}