<?php

namespace App\Filament\Clusters\Networking\Resources\PostResource\Pages;

use App\Filament\Clusters\Networking\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->label('View Event')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->url(fn (): string => static::getResource()::getUrl('view', ['record' => $this->record->getKey()])),
            Actions\DeleteAction::make()
                ->label('Delete Event')
                ->icon('heroicon-o-trash')
                ->color('danger'),
            Actions\ForceDeleteAction::make()
                ->label('Force Delete')
                ->icon('heroicon-o-x-circle')
                ->color('danger'),
            Actions\RestoreAction::make()
                ->label('Restore Event')
                ->icon('heroicon-o-arrow-path')
                ->color('warning'),
        ];
    }
}

