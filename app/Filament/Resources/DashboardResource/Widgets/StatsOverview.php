<?php

namespace App\Filament\Resources\DashboardResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('Jumlah total pengguna')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->url(route('filament.admin.resources.users.index')),
            
            Stat::make('Admin Users', User::where('role', 'admin')->count())
                ->description('Jumlah admin')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning'),
            
            Stat::make('Regular Users', User::where('role', 'user')->count())
                ->description('Jumlah pengguna biasa')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
            
            Stat::make('Tenaga Ahli', User::whereNotNull('tenaga_ahli')->count())
                ->description('Jumlah tenaga ahli')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('info'),
            
            Stat::make('Data User', 'Kelola')
                ->description('Klik untuk mengelola data user')
                ->descriptionIcon('heroicon-m-cog-6-tooth')
                ->color('gray')
                ->url(route('filament.admin.resources.users.index')),
            
            Stat::make('System Status', 'Online')
                ->description('Status sistem')
                ->descriptionIcon('heroicon-m-signal')
                ->color('success'),
        ];
    }
}
