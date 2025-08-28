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

            // Check if service is active
            if (!$service->is_active) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Layanan ini sedang tidak aktif.'
                    ], 400);
                }
                return back()->with('error', 'Layanan ini sedang tidak aktif.');
            }

            // Check if the counter is valid for this service
            if (!$service->counters->contains($counter->id)) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Loket tidak valid untuk layanan ini.'
                    ], 400);
                }
                return back()->with('error', 'Loket tidak valid untuk layanan ini.');
            }

            // Generate queue number
            $lastAntrian = Antrian::where('service_id', $service->id)
                ->whereDate('created_at', today())
                ->orderBy('queue_number', 'desc')
                ->first();

            $nextNumber = $lastAntrian ? $lastAntrian->queue_number + 1 : 1;
            $formattedNumber = $service->code . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            
            // Ensure the formatted number is generated correctly
            if (empty($formattedNumber)) {
                throw new \Exception('Gagal menghasilkan nomor antrian');
            }

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

            // Broadcast event for real-time display update
            event(new \App\Events\TicketCreatedEvent([
                'service_id' => $service->id,
                'service_code' => $service->code,
                'ticket_number' => $formattedNumber,
                'queue_number' => $nextNumber,
                'counter_name' => $counter->name
            ]));

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'ticket_number' => $formattedNumber,
                    'service_name' => $service->name,
                    'counter_name' => $counter->name,
                    'waiting_time' => 'Estimasi waktu tunggu: ' . $this->calculateWaitingTime($service->id) . ' menit'
                ]);
            }

            return redirect()->route('queue.ticket')->with('success', 'Tiket berhasil dibuat: ' . $formattedNumber);

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat tiket: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Gagal membuat tiket: ' . $e->getMessage());
        }
    }
    
    /**
     * Calculate estimated waiting time for a service
     */
    private function calculateWaitingTime($serviceId)
    {
        // Get average processing time per ticket (in minutes)
        $avgProcessingTime = 5; // Default 5 minutes per ticket
        
        // Count waiting tickets before this one
        $waitingCount = Antrian::where('service_id', $serviceId)
            ->where('status', 'waiting')
            ->whereDate('created_at', today())
            ->count();
            
        // Calculate estimated waiting time
        $waitingTime = $waitingCount * $avgProcessingTime;
        
        return max(1, $waitingTime); // At least 1 minute
    }

    public function success(Antrian $antrian)
    {
        if (!$antrian->exists) {
            return redirect()->route('queue.ticket');
        }

        return redirect()->route('queue.ticket')->with('success', 'Tiket berhasil dibuat: ' . $antrian->formatted_number);
    }
}
