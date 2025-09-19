<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Antrian;
use App\Models\Service;
use App\Models\Counter;
use Illuminate\Support\Carbon;

class AntrianManager extends Component
{
    use WithPagination;

    public $selectedService = null;
    public $selectedCounter = null;
    public $queueNumber = '';
    public $formattedNumber = '';
    public $status = 'waiting';

    public $showModal = false;

    public $filterService = '';
    public $filterStatus = '';
    public $filterDate = '';

    // Properti untuk dashboard
    public $currentDate;

    protected $rules = [
        'selectedService' => 'required|exists:services,id',
        'selectedCounter' => 'nullable|exists:counters,id',
        'status' => 'required|in:waiting,called,finished,skipped',
    ];

    protected $messages = [
        'selectedService.required' => 'Pilih layanan terlebih dahulu',
        'selectedService.exists' => 'Layanan tidak valid',
        'selectedCounter.exists' => 'Loket tidak valid',
        'status.required' => 'Status harus dipilih',
        'status.in' => 'Status tidak valid',
    ];

    public function mount()
    {
        $this->filterDate = now()->format('Y-m-d');
        $this->currentDate = Carbon::today()->format('Y-m-d');

        // Cek apakah hari ini sudah membersihkan riwayat panggilan
        $lastCleared = session('last_call_history_cleared');
        $today = Carbon::today()->format('Y-m-d');

        // Jika belum pernah dibersihkan atau terakhir dibersihkan bukan hari ini
        if (!$lastCleared || $lastCleared !== $today) {
            // Bersihkan riwayat panggilan secara otomatis
            $this->clearCallHistory(true);

            // Simpan tanggal terakhir dibersihkan
            session(['last_call_history_cleared' => $today]);
        }
    }

    public function openModal()
    {
        $this->resetInputFields();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetInputFields();
    }

    public function resetInputFields()
    {
        $this->selectedService = null;
        $this->selectedCounter = null;
        $this->status = 'waiting';
    }

    public function create()
    {
        $this->validate();

        $service = Service::findOrFail($this->selectedService);

        // Generate queue number
        $today = now()->format('Y-m-d');
        $lastAntrian = Antrian::where('service_id', $this->selectedService)
            ->whereDate('created_at', $today)
            ->orderBy('queue_number', 'desc')
            ->first();

        $nextNumber = $lastAntrian ? $lastAntrian->queue_number + 1 : 1;
        $this->queueNumber = $nextNumber;
        $this->formattedNumber = $service->code . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        Antrian::create([
            'service_id' => $this->selectedService,
            'counter_id' => $this->selectedCounter,
            'queue_number' => $this->queueNumber,
            'formatted_number' => $this->formattedNumber,
            'status' => $this->status,
        ]);

        session()->flash('message', 'Antrian berhasil ditambahkan: ' . $this->formattedNumber);
        $this->closeModal();
    }

    // Method untuk fitur dashboard
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

    public function callNextFromDashboard($serviceId, $counterId)
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

    public function finishQueueFromDashboard($id)
    {
        $antrian = Antrian::findOrFail($id);
        $antrian->update([
            'status' => 'finished',
            'finished_at' => now(),
        ]);

        $this->dispatch('refresh-dashboard');
    }

    public function skipQueueFromDashboard($id)
    {
        $antrian = Antrian::findOrFail($id);
        $antrian->update([
            'status' => 'skipped',
        ]);

        $this->dispatch('refresh-dashboard');
    }

    public function getServicesProperty()
    {
        return Service::where('is_active', true)->orderBy('name')->get();
    }

    public function callNext($antrianId, $serviceId, $counterId = 1)
    {
        $antrian = Antrian::find($antrianId);

        if (!$antrian) {
            session()->flash('error', 'Antrian tidak ditemukan');
            return;
        }

        // Check if there's already a called antrian for this service and counter
        $existingCalled = Antrian::where('service_id', $serviceId)
            ->where('counter_id', $counterId)
            ->where('status', 'called')
            ->where('id', '!=', $antrianId)
            ->exists();

        if ($existingCalled) {
            session()->flash('error', 'Masih ada antrian yang dipanggil untuk layan ini');
            return;
        }

        $antrian->update([
            'status' => 'called',
            'called_at' => now(),
            'counter_id' => $counterId,
        ]);

        $service = $antrian->service;
        $counter = $antrian->counter;

        // Debug log
        \Log::info('CallNext triggered', [
            'number' => $antrian->formatted_number,
            'service' => $service->name,
            'counter' => $counter?->name,
            'id' => $antrian->id
        ]);

        $this->dispatch('antrian-called', [
            'number' => $antrian->formatted_number,
            'service' => $service->name,
            'counter' => $counter?->name ?? 'Loket',
            'id' => $antrian->id
        ]);

        session()->flash('message', 'Antrian ' . $antrian->formatted_number . ' telah dipanggil');
    }

    public function recall($antrianId)
    {
        $antrian = Antrian::find($antrianId);

        if (!$antrian) {
            session()->flash('error', 'Antrian tidak ditemukan');
            return;
        }

        $service = $antrian->service;
        $counter = $antrian->counter;

        // Update status and called_at
        $antrian->update([
            'status' => 'called',
            'called_at' => now(),
            'counter_id' => $counter?->id,
        ]);

        // Debug log
        \Log::info('Recall triggered', [
            'number' => $antrian->formatted_number,
            'service' => $service->name,
            'counter' => $counter?->name,
            'id' => $antrian->id,
            'type' => 'recall'
        ]);

        // Kirim event untuk pemutaran suara pada panggilan ulang
        $this->dispatch('queue-called', [
            'number' => $antrian->formatted_number,
            'service' => $service->name,
            'counter' => $counter?->name ?? 'Loket',
            'id' => $antrian->id
        ]);

        // Kirim event khusus untuk panggilan ulang
        $this->dispatch('antrian-recalled', [
            'number' => $antrian->formatted_number,
            'service' => $service->name,
            'counter' => $counter?->name ?? 'Loket',
            'id' => $antrian->id
        ]);

        session()->flash('message', 'Antrian ' . $antrian->formatted_number . ' dipanggil ulang');
    }

    public function skip($antrianId)
    {
        $antrian = Antrian::findOrFail($antrianId);
        $antrian->update([
            'status' => 'skipped',
            'finished_at' => now()
        ]);

        $this->dispatch('notify', [
            'type' => 'warning',
            'message' => 'Antrian ' . $antrian->formatted_number . ' dilewati'
        ]);
    }

    public function finish($antrianId)
    {
        $antrian = Antrian::findOrFail($antrianId);
        $antrian->update([
            'status' => 'finished',
            'finished_at' => now()
        ]);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Antrian ' . $antrian->formatted_number . ' selesai diproses'
        ]);
    }

    public function getWaitingCountProperty()
    {
        return Antrian::where('status', 'waiting')
            ->when($this->filterDate, function ($q) {
                $q->whereDate('created_at', Carbon::parse($this->filterDate));
            })
            ->count();
    }

    public function getCalledCountProperty()
    {
        return Antrian::where('status', 'called')
            ->when($this->filterDate, function ($q) {
                $q->whereDate('created_at', Carbon::parse($this->filterDate));
            })
            ->count();
    }

    public function getFinishedCountProperty()
    {
        return Antrian::where('status', 'finished')
            ->when($this->filterDate, function ($q) {
                $q->whereDate('created_at', Carbon::parse($this->filterDate));
            })
            ->count();
    }

    public function getSkippedCountProperty()
    {
        return Antrian::where('status', 'skipped')
            ->when($this->filterDate, function ($q) {
                $q->whereDate('created_at', Carbon::parse($this->filterDate));
            })
            ->count();
    }

    public function render()
    {
        $query = Antrian::with(['service', 'counter'])
            ->when($this->filterDate, function ($q) {
                $q->whereDate('created_at', Carbon::parse($this->filterDate));
            })
            ->when($this->filterService, function ($q) {
                $q->where('service_id', $this->filterService);
            })
            ->when($this->filterStatus, function ($q) {
                if ($this->filterStatus === 'finished') {
                    $q->whereIn('status', ['finished', 'skipped']);
                } else {
                    $q->where('status', $this->filterStatus);
                }
            }, function ($q) {
                // Default: only show waiting and called queues
                $q->whereIn('status', ['waiting', 'called']);
            })
            ->when(!auth()->user()->isSuperAdmin(), function ($q) {
                // Filter untuk petugas: hanya tampilkan antrian dari layanan yang dimiliki
                $user = auth()->user();
                $serviceIds = $user->services()->pluck('services.id');
                $q->whereIn('service_id', $serviceIds);
            })
            ->orderBy('created_at', 'asc');

        // Filter services untuk dropdown berdasarkan role
        $services = Service::where('is_active', true)
            ->when(!auth()->user()->isSuperAdmin(), function ($q) {
                $user = auth()->user();
                $serviceIds = $user->services()->pluck('services.id');
                $q->whereIn('id', $serviceIds);
            })
            ->get();

        return view('livewire.antrian-manager', [
            'antrians' => $query->paginate(10),
            'services' => $services,
            'counters' => Counter::all(),
            'currentQueues' => $this->currentQueues,
            'calledQueues' => $this->calledQueues,
            'waitingQueues' => $this->waitingQueues,
            'statistics' => $this->statistics,
        ]);
    }

    #[\Livewire\Attributes\On('callNext')]
    public function handleCallNext($antrianId, $serviceId = null, $counterId = null)
    {
        try {
            $this->callNext($antrianId, $serviceId, $counterId);
        } catch (\Exception $e) {
            $this->dispatch('error', message: $e->getMessage());
        }
    }

    #[\Livewire\Attributes\On('recall')]
    public function handleRecall($antrianId)
    {
        try {
            $this->recall($antrianId);
        } catch (\Exception $e) {
            $this->dispatch('error', message: $e->getMessage());
        }
    }

    #[\Livewire\Attributes\On('skip')]
    public function handleSkip($antrianId)
    {
        try {
            $this->skip($antrianId);
        } catch (\Exception $e) {
            $this->dispatch('error', message: $e->getMessage());
        }
    }

    #[\Livewire\Attributes\On('finish')]
    public function handleFinish($antrianId)
    {
        try {
            $this->finish($antrianId);
        } catch (\Exception $e) {
            $this->dispatch('error', message: $e->getMessage());
        }
    }

    #[\Livewire\Attributes\On('delete')]
    public function delete($antrianId)
    {
        $antrian = Antrian::findOrFail($antrianId);
        $antrian->delete();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Antrian berhasil dihapus'
        ]);
    }

    #[\Livewire\Attributes\On('ticket-created')]
    public function handleTicketCreated($data)
    {
        // Refresh data when new ticket is created
        $this->render();
    }

    #[\Livewire\Attributes\On('refresh-dashboard')]
    public function refreshDashboard()
    {
        // This method will trigger re-rendering for dashboard
    }

    /**
     * Reset semua antrian untuk hari ini atau tanggal tertentu
     */
    public function resetAntrian($date = null)
    {
        $date = $date ?? now()->format('Y-m-d');

        // Hapus semua antrian pada tanggal yang ditentukan
        $count = Antrian::whereDate('created_at', $date)->delete();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => "Berhasil mereset $count antrian"
        ]);

        $this->dispatch('refresh-dashboard');

        // Broadcast event untuk reload halaman display
        $this->dispatch('antrian-reset');
    }

    /**
     * Membersihkan riwayat panggilan di display
     *
     * @param bool $silent Jika true, tidak akan menampilkan notifikasi (untuk pembersihan otomatis)
     */
     public function clearCallHistory($silent = false)
     {
         if (!$silent) {
             $this->dispatch('notify', [
                 'type' => 'success',
                 'message' => "Membersihkan riwayat panggilan"
             ]);
         }
     
         // Broadcast event Livewire (untuk halaman yang berada di konteks yang sama)
         $this->dispatch('clear-call-history-event', [
             'silent' => $silent
         ]);
     
         // Pastikan storage browser benar-benar dibersihkan dari halaman ini (berlaku lintas tab)
         // Bungkus kode dalam IIFE agar valid sebagai ekspresi Alpine/Livewire ($this->js)
         $this->js("(() => {\n    try {\n        const localKeys = ['callHistory','lastCalledNumber','lastCalledCounter','tempCallHistory'];\n        const sessionKeys = ['tempCallHistory'];\n\n        // Hapus kunci di localStorage\n        localKeys.forEach(k => {\n            try { localStorage.removeItem(k); } catch (e) { console.warn('removeItem localStorage gagal untuk', k, e); }\n        });\n\n        // Hapus kunci di sessionStorage\n        sessionKeys.forEach(k => {\n            try { sessionStorage.removeItem(k); } catch (e) { console.warn('removeItem sessionStorage gagal untuk', k, e); }\n        });\n\n        // Trigger storage event DISETEL SETELAH pembersihan\n        localStorage.setItem('counter_status_changed', Date.now());\n        console.log('Riwayat panggilan dibersihkan dari storage oleh AntrianManager');\n    } catch (e) {\n        console.error('Gagal membersihkan storage:', e);\n    }\n })();");
     }

    /**
     * Handler untuk event reset-antrian
     */
    #[\Livewire\Attributes\On('reset-antrian')]
    public function handleResetAntrian($date = null)
    {
        try {
            $this->resetAntrian($date);
        } catch (\Exception $e) {
            $this->dispatch('error', message: $e->getMessage());
        }
    }
}
