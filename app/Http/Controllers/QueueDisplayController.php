<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use App\Models\Service;
use App\Models\Counter;
use App\Models\Profil;
use Illuminate\Http\Request;

class QueueDisplayController extends Controller
{
    public function index()
    {
        $profil = Profil::first();
        $services = Service::with('counters')->get();
        $counters = Counter::all();

        // Get current serving numbers for each service
        $currentQueues = [];
        foreach ($services as $service) {
            $currentServing = Antrian::where('service_id', $service->id)
                ->where('status', 'called')
                ->whereDate('created_at', now()->toDateString())
                ->orderBy('called_at', 'desc')
                ->first();

            $currentQueues[$service->code] = [
                'number' => $currentServing ? $currentServing->formatted_number : '-',
                'counter' => $currentServing && $currentServing->counter ? $currentServing->counter->name : '-',
                'service_name' => $service->name,
                'service_code' => $service->code
            ];
        }

        // Get next waiting queue for each service (only 1 for counter status)
        $waitingQueues = [];
        foreach ($services as $service) {
            $nextQueue = Antrian::where('service_id', $service->id)
                ->where('status', 'waiting')
                ->whereDate('created_at', now()->toDateString())
                ->orderBy('queue_number')
                ->first();

            $waitingQueues[$service->code] = $nextQueue ? $nextQueue->formatted_number : '-';
        }

        return view('display', [
            'profil' => $profil,
            'currentQueues' => $currentQueues,
            'nextQueues' => $waitingQueues, // Changed to show all waiting queues
            'services' => $services,
            'counters' => $counters
        ]);
    }

    public function getQueueData()
    {
        $services = Service::with('counters')->get();
        $data = [];

        foreach ($services as $service) {
            $currentServing = Antrian::where('service_id', $service->id)
                ->where('status', 'called')
                ->whereDate('created_at', now()->toDateString())
                ->orderBy('called_at', 'desc')
                ->first();

            $nextQueue = Antrian::where('service_id', $service->id)
                ->where('status', 'waiting')
                ->whereDate('created_at', now()->toDateString())
                ->orderBy('queue_number')
                ->first();

            $data[$service->code] = [
                'current' => $currentServing ? $currentServing->formatted_number : '-',
                'current_counter' => $currentServing && $currentServing->counter ? $currentServing->counter->name : '-',
                'next' => $nextQueue ? $nextQueue->formatted_number : '-',
                'service_name' => $service->name
            ];
        }

        return response()->json($data);
    }
}
