<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Antrian;
use App\Models\Service;
use App\Models\Counter;
use Carbon\Carbon;

class DashboardStats extends Component
{
    public $statistics = [];
    public $chartData = [];
    public $chartType = 'daily';

    public function mount()
    {
        $this->loadData();
    }

    #[On('queue-updated')]
    public function loadData()
    {
        $today = Carbon::today();
        
        // Statistik hari ini
        $this->statistics = [
            'total_today' => Antrian::whereDate('created_at', $today)->count(),
            'waiting' => Antrian::where('status', 'waiting')->count(),
            'called' => Antrian::where('status', 'called')->count(),
            'finished' => Antrian::where('status', 'finished')->count(),
            'skipped' => Antrian::where('status', 'skipped')->count(),
        ];

        $this->prepareChartData();
    }

    public function prepareChartData()
    {
        switch ($this->chartType) {
            case 'daily':
                $this->chartData = $this->getDailyChartData();
                break;
            case 'weekly':
                $this->chartData = $this->getWeeklyChartData();
                break;
            case 'monthly':
                $this->chartData = $this->getMonthlyChartData();
                break;
        }
    }

    private function getDailyChartData()
    {
        $data = [];
        $hours = range(0, 23);
        
        foreach ($hours as $hour) {
            $count = Antrian::whereDate('created_at', Carbon::today())
                ->whereRaw('HOUR(created_at) = ?', [$hour])
                ->count();
            
            $data[] = [
                'label' => sprintf('%02d:00', $hour),
                'value' => $count
            ];
        }
        
        return $data;
    }

    private function getWeeklyChartData()
    {
        $data = [];
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        
        foreach ($days as $index => $day) {
            $count = Antrian::whereBetween('created_at', [
                Carbon::now()->startOfWeek()->addDays($index),
                Carbon::now()->startOfWeek()->addDays($index)->endOfDay()
            ])->count();
            
            $data[] = [
                'label' => $day,
                'value' => $count
            ];
        }
        
        return $data;
    }

    private function getMonthlyChartData()
    {
        $data = [];
        $daysInMonth = Carbon::now()->daysInMonth;
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $count = Antrian::whereDate('created_at', Carbon::now()->day($day))->count();
            
            $data[] = [
                'label' => $day,
                'value' => $count
            ];
        }
        
        return $data;
    }

    public function setChartType($type)
    {
        $this->chartType = $type;
        $this->prepareChartData();
    }

    public function getRefreshRateProperty()
    {
        return 5000; // 5 detik
    }

    public function render()
    {
        return view('livewire.dashboard-stats');
    }
}