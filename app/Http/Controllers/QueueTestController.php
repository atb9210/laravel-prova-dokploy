<?php

namespace App\Http\Controllers;

use App\Jobs\TestWorkerJob;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class QueueTestController extends Controller
{
    public function index(): View
    {
        return view('queue-test', [
            'lastRun' => Cache::get('test-worker:last-run'),
            'count' => Cache::get('test-worker:count', 0),
        ]);
    }

    public function dispatch(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'payload' => ['nullable', 'string', 'max:255'],
        ]);

        $payload = $validated['payload'] ?? 'Default payload';

        TestWorkerJob::dispatch($payload);

        return redirect()
            ->route('queue-test.index')
            ->with('status', "Job queued with payload: {$payload}");
    }
}
