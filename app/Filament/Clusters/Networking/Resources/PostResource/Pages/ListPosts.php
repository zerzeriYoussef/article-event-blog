<?php

namespace App\Filament\Clusters\Networking\Resources\PostResource\Pages;

use App\Filament\Clusters\Networking\Resources\PostResource;
use App\Filament\Clusters\Networking\Resources\PostResource\Widgets\EventStatsWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Event')
                ->icon('heroicon-o-plus-circle')
                ->color('primary'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            EventStatsWidget::class,
        ];
    }

    /**
     * Ensure all posts are visible, including those without translations
     */
    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getTableQuery();
    }
}

