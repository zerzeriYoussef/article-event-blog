<?php

namespace App\Filament\Clusters\Networking\Resources\PostResource\Pages;

use App\Filament\Clusters\Networking\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPost extends ViewRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Edit Event')
                ->icon('heroicon-o-pencil')
                ->color('warning')
                ->url(fn (): string => static::getResource()::getUrl('edit', ['record' => $this->record->getKey()])),
            Actions\DeleteAction::make()
                ->label('Delete Event')
                ->icon('heroicon-o-trash')
                ->color('danger'),
        ];
    }
}

