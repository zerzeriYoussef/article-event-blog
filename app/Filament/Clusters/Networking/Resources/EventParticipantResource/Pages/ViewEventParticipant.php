<?php

namespace App\Filament\Clusters\Networking\Resources\EventParticipantResource\Pages;

use App\Filament\Clusters\Networking\Resources\EventParticipantResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEventParticipant extends ViewRecord
{
    protected static string $resource = EventParticipantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}

