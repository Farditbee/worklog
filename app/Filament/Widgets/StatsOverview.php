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
                ->color('info')
                ->url(route('filament.admin.resources.projects.index')),

            Stat::make('Data Worklog', \App\Models\Worklog::count())
                ->description('Jumlah Worklog')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('warning')
                ->url(route('filament.admin.resources.worklogs.index')),

            Stat::make('Data Absen', \App\Models\Absen::count())
                ->description('Jumlah Absen')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('warning')
                ->url(route('filament.admin.resources.absens.index')),
            
            Stat::make('System Status', 'Online')
                ->description('Status sistem')
                ->descriptionIcon('heroicon-m-signal')
                ->color('success'),
        ];
    }
}