<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Antrian;
use App\Models\Counter;
use Illuminate\Http\Request;
use Carbon\Carbon;

class QueueTicketController extends Controller
{
    public function index()
    {
        // Load active services with their counters and waiting counts
        $activeServices = Service::with(['counters'])
            ->where('is_active', true)
            ->withCount(['antrians as waiting_count' => function($query) {
                $query->where('status', 'waiting')
                      ->whereDate('created_at', today());
            }])
            ->orderBy('name')
            ->get();

        // Load inactive services
        $inactiveServices = Service::where('is_active', false)
            ->orderBy('name')
            ->get();

        return view('queue.ticket', [
            'activeServices' => $activeServices,
            'inactiveServices' => $inactiveServices
        ]);
    }

    public function takeTicket(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'counter_id' => 'required|exists:counters,id',
        ]);

        try {
            $service = Service::findOrFail($request->service_id);
            $counter = Counter::findOrFail($request->counter_id);

            // Check if the counter is valid for this service
            if (!$service->counters->contains($counter->id)) {
                return back()->with('error', 'Loket tidak valid untuk layanan ini.');
            }

            // Generate queue number
            $lastAntrian = Antrian::where('service_id', $service->id)
                ->whereDate('created_at', today())
                ->orderBy('queue_number', 'desc')
                ->first();

            $nextNumber = $lastAntrian ? $lastAntrian->queue_number + 1 : 1;
            $formattedNumber = $service->code . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            // Create new ticket
            $antrian = Antrian::create([
                'service_id' => $service->id,
                'counter_id' => $counter->id,
                'queue_number' => $nextNumber,
                'formatted_number' => $formattedNumber,
                'status' => 'waiting',
                'called_at' => null,
                'finished_at' => null,
            ]);

            return redirect()->route('queue.ticket.success', $antrian->id);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat tiket: ' . $e->getMessage());
        }
    }

    public function success(Antrian $antrian)
    {
        if (!$antrian->exists) {
            return redirect()->route('queue.ticket');
        }

        return view('queue.success', compact('antrian'));
    }
}
