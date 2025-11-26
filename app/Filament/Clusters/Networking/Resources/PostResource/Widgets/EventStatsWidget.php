<?php

namespace App\Filament\Clusters\Networking\Resources\PostResource\Widgets;

use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EventStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalEvents = Post::count();
        $publishedEvents = Post::where('is_published', true)->count();
        $draftEvents = Post::where('is_published', false)->count();
        $totalViews = Post::sum('views_count');
        $featuredEvents = Post::where('is_featured', true)->count();

        return [
            Stat::make('Total Events', $totalEvents)
                ->description('All events in the system')
                ->descriptionIcon('heroicon-o-calendar-days')
                ->color('primary')
                ->chart([7, 3, 4, 5, 6, 3, 5]),

            Stat::make('Published Events', $publishedEvents)
                ->description('Currently live')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->chart([3, 2, 4, 3, 5, 4, 6]),

            Stat::make('Draft Events', $draftEvents)
                ->description('Pending publication')
                ->descriptionIcon('heroicon-o-pencil-square')
                ->color('warning')
                ->chart([2, 1, 3, 2, 4, 3, 5]),

            Stat::make('Total Views', number_format($totalViews))
                ->description('All-time views')
                ->descriptionIcon('heroicon-o-eye')
                ->color('info')
                ->chart([10, 15, 12, 18, 20, 22, 25]),

            Stat::make('Featured Events', $featuredEvents)
                ->description('Highlighted content')
                ->descriptionIcon('heroicon-o-star')
                ->color('warning')
                ->chart([1, 2, 1, 3, 2, 4, 3]),
        ];
    }
}

