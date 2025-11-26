<?php

namespace App\Filament\Clusters\Networking\Resources\PostResource\Pages;

use App\Filament\Clusters\Networking\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    /*protected function getRedirectUrl(): string
    {
        // Always redirect to index page after creation, never to view page
        return static::getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure published_at is set if not provided
        if (empty($data['published_at'])) {
            $data['published_at'] = now();
        }
        
        return $data;
    }*/
}

