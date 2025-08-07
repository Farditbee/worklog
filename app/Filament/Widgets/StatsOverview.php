<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Data Users', User::count())
                ->description('Jumlah Data User')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success')
                ->url(route('filament.admin.resources.users.index')),
            
            Stat::make('Data Proyek', Project::count())
                ->description('Jumlah Proyek')
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('success')
                ->url(route('filament.admin.resources.projects.index')),
            
            Stat::make('System Status', 'Online')
                ->description('Status sistem')
                ->descriptionIcon('heroicon-m-signal')
                ->color('success'),
        ];
    }
}