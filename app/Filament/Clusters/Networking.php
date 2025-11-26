<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;
use App\Models\Post;

class Networking extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-share';

    protected static ?string $navigationLabel = 'Networking';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 2;

    protected static ?string $description = 'Manage events, comments, newsletters, and phone contacts';

    public static function getNavigationBadge(): ?string
    {
        $totalEvents = Post::count();
        return $totalEvents > 0 ? (string) $totalEvents : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }
}
