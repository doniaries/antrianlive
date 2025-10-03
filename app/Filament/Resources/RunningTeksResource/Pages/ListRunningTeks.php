<?php

namespace App\Filament\Resources\RunningTeksResource\Pages;

use App\Filament\Resources\RunningTeksResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRunningTeks extends ListRecords
{
    protected static string $resource = RunningTeksResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
