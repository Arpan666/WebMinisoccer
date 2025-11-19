<?php

namespace App\Filament\Resources\FieldPriceResource\Pages;

use App\Filament\Resources\FieldPriceResource;
use Filament\Resources\Pages\EditRecord;

class EditFieldPrice extends EditRecord
{
    protected static string $resource = FieldPriceResource::class;

    protected static ?string $title = 'Edit Harga Lapangan';
}