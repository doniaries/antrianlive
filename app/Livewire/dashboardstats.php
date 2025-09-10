<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Antrian;
use App\Models\Counter;
use App\Models\Service;
use Carbon\Carbon;

class DashboardStats extends Component
{
    public $totalAntrianHariIni = 0;
    public $antrianSelesai = 0;
    public $antrianDitunda = 0;
    public $antrianDiproses = 0;
    public $counters = [];
    public $services = [];
    public $recentAntrian = [];
    public $chartType = 'line';
    public $chartData = [];
    public $statistics = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $today = Carbon::today();

        // Get today's queue statistics
        $this->totalAntrianHariIni = Antrian::whereDate('created_at', $today)->count();
        $this->antrianSelesai = Antrian::whereDate('created_at', $today)
                                        ->where('status', 'selesai')
                                        ->count();
        $this->antrianDitunda = Antrian::whereDate('created_at', $today)
                                        ->where('status', 'pending')
                                        ->count();
        $this->antrianDiproses = Antrian::whereDate('created_at', $today)
                                        ->where('status', 'diproses')
                                        ->count();

        // Get active counters with services and current antrians
        $this->counters = Counter::with(['services', 'antrians' => function($query) use ($today) {
            $query->whereDate('created_at', $today)
                  ->whereIn('status', ['diproses', 'pending']);
        }])->get();

        // Get services with queue count
        $this->services = Service::withCount(['antrians' => function($query) use ($today) {
            $query->whereDate('created_at', $today);
        }])->get();

        // Get recent queue
        $this->recentAntrian = Antrian::with(['service', 'counter'])
                                    ->whereDate('created_at', $today)
                                    ->latest()
                                    ->take(5)
                                    ->get();

        // Prepare chart data
        $this->prepareChartData($today);
        
        // Prepare statistics
        $this->prepareStatistics($today);
    }

    private function prepareChartData($today)
    {
        $labels = [];
        $data = [];

        // Get data for the last 8 hours
        for ($i = 7; $i >= 0; $i--) {
            $hour = Carbon::now()->subHours($i);
            $startHour = $hour->copy()->startOfHour();
            $endHour = $hour->copy()->endOfHour();
            
            $count = Antrian::whereBetween('created_at', [$startHour, $endHour])->count();
            
            $labels[] = $hour->format('H:i');
            $data[] = $count;
        }

        // Ensure arrays are not empty
        if (empty($labels)) {
            $labels = ['00:00', '03:00', '06:00', '09:00', '12:00', '15:00', '18:00', '21:00'];
            $data = [0, 0, 0, 0, 0, 0, 0, 0];
        }

        $this->chartData = [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function prepareStatistics($today)
    {
        $this->statistics = [
            'total' => $this->totalAntrianHariIni,
            'completed' => $this->antrianSelesai,
            'pending' => $this->antrianDitunda,
            'processing' => $this->antrianDiproses,
            'average_wait_time' => $this->calculateAverageWaitTime($today),
            'peak_hour' => $this->getPeakHour($today)
        ];
    }

    private function calculateAverageWaitTime($today)
    {
        $completedAntrians = Antrian::whereDate('created_at', $today)
                                   ->where('status', 'selesai')
                                   ->whereNotNull('called_at')
                                   ->whereNotNull('finished_at')
                                   ->get();

        if ($completedAntrians->isEmpty()) {
            return 0;
        }

        $totalWaitTime = 0;
        foreach ($completedAntrians as $antrian) {
            $totalWaitTime += $antrian->finished_at->diffInMinutes($antrian->called_at);
        }

        return round($totalWaitTime / $completedAntrians->count());
    }

    private function getPeakHour($today)
    {
        $peakHour = Antrian::selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
                          ->whereDate('created_at', $today)
                          ->groupBy('hour')
                          ->orderBy('count', 'desc')
                          ->first();

        return $peakHour ? str_pad($peakHour->hour, 2, '0', STR_PAD_LEFT) . ':00' : 'N/A';
    }

    public function render()
    {
        return view('livewire.dashboard-stats', [
            'totalAntrianHariIni' => $this->totalAntrianHariIni,
            'antrianSelesai' => $this->antrianSelesai,
            'antrianDiproses' => $this->antrianDiproses,
            'antrianDitunda' => $this->antrianDitunda,
            'counters' => $this->counters,
            'recentAntrian' => $this->recentAntrian,
            'chartData' => $this->chartData
        ]);
    }
}