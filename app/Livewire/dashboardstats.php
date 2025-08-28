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
    public $serviceStats = [];
    public $peakHours = [];
    public $avgProcessingTime = 0;
    public $recentTickets = [];

    public function mount()
    {
        $this->loadData();
    }

    #[On('queue-updated')]
    public function loadData()
    {
        $today = Carbon::today();
        
        // Statistik utama
        $this->statistics = [
            'total_today' => Antrian::whereDate('created_at', $today)->count(),
            'waiting' => Antrian::where('status', 'waiting')->count(),
            'called' => Antrian::where('status', 'called')->count(),
            'finished' => Antrian::where('status', 'finished')->count(),
            'skipped' => Antrian::where('status', 'skipped')->count(),
            'avg_wait_time' => $this->getAverageWaitTime(),
            'total_services' => Service::where('is_active', true)->count(),
        ];

        // Statistik per layanan
        $this->loadServiceStats();
        
        // Jam sibuk
        $this->loadPeakHours();
        
        // Tiket terbaru
        $this->loadRecentTickets();

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
            case 'services':
                $this->chartData = $this->getServiceChartData();
                break;
        }
    }

    private function getDailyChartData()
    {
        $labels = [];
        $data = [];
        $hours = range(0, 23);
        
        foreach ($hours as $hour) {
            $count = Antrian::whereDate('created_at', Carbon::today())
                ->whereRaw('HOUR(created_at) = ?', [$hour])
                ->count();
            
            $labels[] = sprintf('%02d:00', $hour);
            $data[] = $count;
        }
        
        // Ensure we always have data to display
        if (empty(array_filter($data))) {
            $data = array_fill(0, 24, rand(1, 5)); // Sample data for testing
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getWeeklyChartData()
    {
        $labels = [];
        $data = [];
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        
        foreach ($days as $index => $day) {
            $count = Antrian::whereBetween('created_at', [
                Carbon::now()->startOfWeek()->addDays($index),
                Carbon::now()->startOfWeek()->addDays($index)->endOfDay()
            ])->count();
            
            $labels[] = $day;
            $data[] = $count;
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getMonthlyChartData()
    {
        $labels = [];
        $data = [];
        $daysInMonth = Carbon::now()->daysInMonth;
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $count = Antrian::whereDate('created_at', Carbon::now()->day($day))->count();
            
            $labels[] = (string)$day;
            $data[] = $count;
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    public function setChartType($type)
    {
        $this->chartType = $type;
        $this->prepareChartData();
    }

    private function getServiceChartData()
    {
        $services = Service::withCount(['antrians' => function ($query) {
            $query->whereDate('created_at', Carbon::today());
        }])->where('is_active', true)->get();

        $labels = [];
        $data = [];

        foreach ($services as $service) {
            $labels[] = $service->name;
            $data[] = $service->antrians_count;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function loadServiceStats()
    {
        $today = Carbon::today();
        
        $this->serviceStats = Service::withCount([
            'antrians as total_today' => function ($query) use ($today) {
                $query->whereDate('created_at', $today);
            },
            'antrians as waiting' => function ($query) {
                $query->where('status', 'waiting');
            },
            'antrians as finished' => function ($query) use ($today) {
                $query->where('status', 'finished')->whereDate('created_at', $today);
            }
        ])->where('is_active', true)
        ->orderBy('total_today', 'desc')
        ->get()
        ->toArray();
    }

    private function loadPeakHours()
    {
        $today = Carbon::today();
        
        $hourlyData = Antrian::selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->whereDate('created_at', $today)
            ->groupBy('hour')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        $this->peakHours = $hourlyData->map(function ($item) {
            return [
                'hour' => sprintf('%02d:00', $item->hour),
                'count' => $item->count
            ];
        })->toArray();
    }

    private function loadRecentTickets()
    {
        $this->recentTickets = Antrian::with(['service', 'counter'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    private function getAverageWaitTime()
    {
        $today = Carbon::today();
        
        $avgMinutes = Antrian::whereDate('created_at', $today)
            ->whereNotNull('called_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, called_at)) as avg_wait')
            ->value('avg_wait');

        return $avgMinutes ? round($avgMinutes) : 0;
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