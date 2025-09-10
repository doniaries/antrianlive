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

    protected $listeners = ['refreshData' => 'refreshDashboard'];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $today = Carbon::today();

        // Optimized query untuk statistik utama
        $antrians = Antrian::selectRaw('status, COUNT(*) as count')
                          ->whereDate('created_at', $today)
                          ->groupBy('status')
                          ->get()
                          ->keyBy('status');

        $this->totalAntrianHariIni = $antrians->sum('count');
        $this->antrianSelesai = $antrians->get('selesai')->count ?? 0;
        $this->antrianDitunda = $antrians->get('pending')->count ?? 0;
        $this->antrianDiproses = $antrians->get('diproses')->count ?? 0;

        // Get active counters with services and current antrians (only when needed)
        $this->counters = Counter::with(['services', 'antrians' => function($query) use ($today) {
            $query->whereDate('created_at', $today)
                  ->whereIn('status', ['diproses', 'pending']);
        }])->get();

        // Get recent queue (limit to 5 latest)
        $this->recentAntrian = Antrian::with(['service', 'counter'])
                                    ->whereDate('created_at', $today)
                                    ->latest()
                                    ->take(5)
                                    ->get();

        // Prepare chart data
        $this->prepareChartData($today);
        
        // Prepare simplified statistics
        $this->prepareStatistics($today);
    }

    private function prepareChartData($today)
    {
        $labels = [];
        $data = [];

        // Get hourly data for today
        $startOfDay = $today->copy()->startOfDay();
        $endOfDay = $today->copy()->endOfDay();

        // Generate labels for each hour (00:00 to 23:00)
        for ($hour = 0; $hour < 24; $hour++) {
            $startHour = $startOfDay->copy()->addHours($hour);
            $endHour = $startHour->copy()->endOfHour();
            
            $count = Antrian::whereBetween('created_at', [$startHour, $endHour])
                           ->whereDate('created_at', $today)
                           ->count();
            
            $labels[] = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';
            $data[] = $count;
        }

        // Filter out hours with no data for cleaner display
        $filteredLabels = [];
        $filteredData = [];
        
        for ($i = 0; $i < count($labels); $i++) {
            if ($data[$i] > 0 || $i % 3 === 0) { // Show every 3rd hour if no data
                $filteredLabels[] = $labels[$i];
                $filteredData[] = $data[$i];
            }
        }

        // Ensure we have at least some data
        if (empty($filteredLabels)) {
            $filteredLabels = ['09:00', '12:00', '15:00', '18:00'];
            $filteredData = [0, 0, 0, 0];
        }

        $this->chartData = [
            'labels' => $filteredLabels,
            'data' => $filteredData
        ];
    }

    private function prepareStatistics($today)
    {
        // Statistik yang hanya menampilkan data penting
        $this->statistics = [
            'total' => $this->totalAntrianHariIni,
            'completed' => $this->antrianSelesai,
            'pending' => $this->antrianDitunda,
            'processing' => $this->antrianDiproses
        ];
    }



    public function refreshDashboard()
    {
        $this->loadData();
    }

    public function getPollingIntervalProperty()
    {
        return 3000; // 3 seconds
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