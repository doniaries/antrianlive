<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Antrian;
use App\Models\Service;
use App\Models\Counter;
use Carbon\Carbon;
// Using error_log instead of Log facade for better compatibility

class DashboardStats extends Component
{
    public $statistics = [];
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

        // Tiket terbaru
        $this->loadRecentTickets();
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
