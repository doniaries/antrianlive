<?php

namespace App\Livewire\Patient;

use Livewire\Component;
use App\Livewire\WithLayout;
use App\Models\Antrian;
use Carbon\Carbon;

class Dashboard extends Component
{
    use WithLayout;

    public $antrianAktif;
    public $riwayatAntrian;
    public $totalAntrianHariIni;
    public $antrianSelesai;
    public $antrianMenunggu;

    public function mount()
    {
        $this->layout = 'components.layouts.patient';
        $this->title = 'Dashboard Pasien';
        $this->loadData();
    }

    public function loadData()
    {
        $pasienId = auth()->guard('patient')->id();
        $today = Carbon::today();
        
        // Ambil antrian aktif
        $this->antrianAktif = Antrian::where('patient_id', $pasienId)
            ->whereDate('created_at', $today)
            ->whereIn('status', ['menunggu', 'dipanggil'])
            ->orderBy('created_at', 'desc')
            ->with('layanan')
            ->first();

        // Ambil riwayat antrian (5 terbaru)
        $this->riwayatAntrian = Antrian::where('patient_id', $pasienId)
            ->with('layanan')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Statistik
        $this->totalAntrianHariIni = Antrian::whereDate('created_at', $today)->count();
        $this->antrianSelesai = Antrian::whereDate('created_at', $today)
            ->where('status', 'selesai')
            ->count();
        $this->antrianMenunggu = Antrian::whereDate('created_at', $today)
            ->whereIn('status', ['menunggu', 'dipanggil'])
            ->count();
    }


    public function render()
    {
        return view('livewire.patient.dashboard');
    }
}
