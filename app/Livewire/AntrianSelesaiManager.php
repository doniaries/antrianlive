<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Antrian;
use App\Models\Service;
use App\Models\Counter;
use Illuminate\Support\Carbon;

class AntrianSelesaiManager extends Component
{
    use WithPagination;

    public $filterService = '';
    public $filterDate = '';
    public $filterStatus = 'finished'; // Default to finished/skipped

    public function mount()
    {
        $this->filterDate = now()->format('Y-m-d');
    }

    public function getServicesProperty()
    {
        return Service::where('is_active', true)->orderBy('name')->get();
    }

    public function getFinishedCountProperty()
    {
        return Antrian::whereIn('status', ['finished', 'skipped'])
            ->when($this->filterDate, function($q) {
                $q->whereDate('created_at', Carbon::parse($this->filterDate));
            })
            ->when($this->filterService, function($q) {
                $q->where('service_id', $this->filterService);
            })
            ->count();
    }

    public function getSkippedCountProperty()
    {
        return Antrian::where('status', 'skipped')
            ->when($this->filterDate, function($q) {
                $q->whereDate('created_at', Carbon::parse($this->filterDate));
            })
            ->when($this->filterService, function($q) {
                $q->where('service_id', $this->filterService);
            })
            ->count();
    }

    public function getCountersProperty()
    {
        return Counter::all();
    }

    public function render()
    {
        $antrians = Antrian::with(['service', 'counter'])
            ->whereIn('status', ['finished', 'skipped'])
            ->when($this->filterDate, function($q) {
                $q->whereDate('created_at', Carbon::parse($this->filterDate));
            })
            ->when($this->filterService, function($q) {
                $q->where('service_id', $this->filterService);
            })
            ->orderBy('finished_at', 'desc')
            ->paginate(10);

        return view('livewire.antrian-selesai-manager', [
            'antrians' => $antrians,
            'services' => Service::where('is_active', true)->get(),
            'counters' => Counter::all(),
        ]);
    }
}