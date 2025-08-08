<?php

namespace App\Filament\Pages;

use App\Models\Project;
use App\Models\Worklog;
use Filament\Pages\Page;
use Filament\Actions\Action;

class WorklogStatistics extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    
    protected static string $view = 'filament.pages.worklog-statistics';
    
    protected static ?string $title = 'Statistik Worklog';
    
    protected static ?string $navigationLabel = 'Statistik Worklog';
    
    protected static ?int $navigationSort = 10;
    
    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Kembali')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url('/admin'),
        ];
    }
    
    public function getViewData(): array
    {
        // Ambil semua project
        $projects = Project::all();
        
        // Ambil data worklog berdasarkan project_id dan hitung jumlahnya
        $worklogCounts = Worklog::selectRaw('project_id, COUNT(*) as total')
            ->groupBy('project_id')
            ->pluck('total', 'project_id')
            ->toArray();
        
        $chartData = [];
        $labels = [];
        $data = [];
        
        foreach ($projects as $project) {
            $count = $worklogCounts[$project->id] ?? 0;
            $labels[] = $project->name;
            $data[] = $count;
            
            $chartData[] = [
                'project_id' => $project->id,
                'project_name' => $project->name,
                'worklog_count' => $count
            ];
        }
        
        return [
            'chartData' => $chartData,
            'chartLabels' => json_encode($labels),
            'chartValues' => json_encode($data),
        ];
    }
}