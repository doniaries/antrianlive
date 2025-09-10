<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Antrian;
use App\Models\Service;
use App\Models\Counter;
use Livewire\Attributes\On;
use Carbon\Carbon;

class AntrianDashboard extends Component
{
    public $selectedService = null;
    public $selectedCounter = null;
    public $currentDate;

    public function mount()
    {
        $this->currentDate = Carbon::today()->format('Y-m-d');
    }

    #[On('refresh-dashboard')]
    public function refreshDashboard()
    {
        // This method will trigger re-rendering
    }

    public function getServicesProperty()
    {
        return Service::where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function getCountersProperty()
    {
        return Counter::with(['services'])
            ->orderBy('name')
            ->get();
    }

    public function getCurrentQueuesProperty()
    {
        return Antrian::query()
            ->with(['service', 'counter'])
            ->whereDate('created_at', $this->currentDate)
            ->whereIn('status', ['waiting', 'called'])
            ->orderBy('service_id')
            ->orderBy('queue_number')
            ->get()
            ->groupBy('service_id');
    }

    public function getCalledQueuesProperty()
    {
        return Antrian::query()
            ->with(['service', 'counter'])
            ->whereDate('created_at', $this->currentDate)
            ->where('status', 'called')
            ->orderBy('called_at', 'desc')
            ->limit(10)
            ->get();
    }

    public function getWaitingQueuesProperty()
    {
        return Antrian::query()
            ->with(['service', 'counter'])
            ->whereDate('created_at', $this->currentDate)
            ->where('status', 'waiting')
            ->orderBy('service_id')
            ->orderBy('queue_number')
            ->limit(20)
            ->get()
            ->groupBy('service_id');
    }

    public function getStatisticsProperty()
    {
        $today = Carbon::today();
        
        return [
            'total_today' => Antrian::whereDate('created_at', $today)->count(),
            'waiting' => Antrian::whereDate('created_at', $today)->where('status', 'waiting')->count(),
            'called' => Antrian::whereDate('created_at', $today)->where('status', 'called')->count(),
            'finished' => Antrian::whereDate('created_at', $today)->where('status', 'finished')->count(),
            'skipped' => Antrian::whereDate('created_at', $today)->where('status', 'skipped')->count(),
        ];
    }

    public function getNextQueue($serviceId)
    {
        return Antrian::query()
            ->with(['service', 'counter'])
            ->where('service_id', $serviceId)
            ->whereDate('created_at', $this->currentDate)
            ->where('status', 'waiting')
            ->orderBy('queue_number')
            ->first();
    }

    public function callNext($serviceId, $counterId)
    {
        $nextAntrian = Antrian::query()
            ->where('service_id', $serviceId)
            ->whereDate('created_at', $this->currentDate)
            ->where('status', 'waiting')
            ->orderBy('queue_number')
            ->first();

        if ($nextAntrian) {
            $nextAntrian->update([
                'counter_id' => $counterId,
                'status' => 'called',
                'called_at' => now(),
            ]);

            $this->dispatch('refresh-dashboard');
            
            // Emit event for sound notification
            $this->dispatch('queue-called', [
                'number' => $nextAntrian->formatted_number,
                'counter' => $nextAntrian->counter?->name ?? 'Loket',
                'service' => $nextAntrian->service->name,
            ]);
        }
    }

    public function finishQueue($id)
    {
        $antrian = Antrian::findOrFail($id);
        $antrian->update([
            'status' => 'finished',
            'finished_at' => now(),
        ]);

        $this->dispatch('refresh-dashboard');
    }

    public function skipQueue($id)
    {
        $antrian = Antrian::findOrFail($id);
        $antrian->update([
            'status' => 'skipped',
        ]);

        $this->dispatch('refresh-dashboard');
    }

    public function render()
    {
        return view('livewire.queue-dashboard', [
            'services' => $this->services,
            'counters' => $this->counters,
            'currentQueues' => $this->currentQueues,
            'calledQueues' => $this->calledQueues,
            'waitingQueues' => $this->waitingQueues,
            'statistics' => $this->statistics,
        ]);
    }
}
