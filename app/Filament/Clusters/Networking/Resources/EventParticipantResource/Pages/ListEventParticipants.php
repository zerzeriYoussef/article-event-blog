<?php

namespace App\Filament\Clusters\Networking\Resources\EventParticipantResource\Pages;

use App\Filament\Clusters\Networking\Resources\EventParticipantResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEventParticipants extends ListRecords
{
    protected static string $resource = EventParticipantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action - participants are created by users joining events
        ];
    }
}

