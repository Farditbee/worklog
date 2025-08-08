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
            ->url(route('filament.admin.resources.users.index'))
            ->extraAttributes([
                'class' => 'bg-green-500 text-white rounded-xl shadow-lg',
            ]),

        Stat::make('Data Proyek', Project::count())
            ->description('Jumlah Proyek')
            ->descriptionIcon('heroicon-m-archive-box')
            ->color('info')
            ->url(route('filament.admin.resources.projects.index'))
            ->extraAttributes([
                'class' => 'bg-blue-500 text-white rounded-xl shadow-lg',
            ]),

        Stat::make('Data Worklog', \App\Models\Worklog::count())
            ->description('Jumlah Worklog')
            ->descriptionIcon('heroicon-m-document-text')
            ->color('warning')
            ->url(route('filament.admin.resources.worklogs.index'))
            ->extraAttributes([
                'class' => 'bg-yellow-500 text-gray-900 rounded-xl shadow-lg',
            ]),

        Stat::make('Data Absen', \App\Models\Absen::count())
            ->description('Jumlah Absen')
            ->descriptionIcon('heroicon-m-document-text')
            ->color('warning')
            ->url(route('filament.admin.resources.absens.index'))
            ->extraAttributes([
                'class' => 'bg-orange-500 text-white rounded-xl shadow-lg',
            ]),

        Stat::make('System Status', 'Online')
            ->description('Status sistem')
            ->descriptionIcon('heroicon-m-signal')
            ->color('success')
            ->extraAttributes([
                'class' => 'bg-purple-500 text-white rounded-xl shadow-lg',
            ]),
    ];
}

}
