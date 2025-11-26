<?php

namespace App\Filament\Clusters\Networking\Resources\EventParticipantResource\Pages;

use App\Filament\Clusters\Networking\Resources\EventParticipantResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEventParticipant extends EditRecord
{
    protected static string $resource = EventParticipantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}

