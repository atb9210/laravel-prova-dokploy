<?php

namespace App\Http\Controllers;

use App\Jobs\TestWorkerJob;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class QueueTestController extends Controller
{
    public function index(): View
    {
        $logPath = storage_path('logs/laravel.log');
        $logTail = '';
        if (is_file($logPath) && is_readable($logPath)) {
            $lines = @file($logPath, FILE_IGNORE_NEW_LINES);
            if (is_array($lines)) {
                $tail = array_slice($lines, -200);
                $logTail = implode("\n", $tail);
            }
        }

        return view('queue-test', [
            'lastRun' => Cache::get('test-worker:last-run'),
            'count' => Cache::get('test-worker:count', 0),
            'schedulerLastRun' => Cache::get('scheduler:last-run'),
            'schedulerCount' => Cache::get('scheduler:count', 0),
            'logTail' => $logTail,
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

    public function runSchedule(): RedirectResponse
    {
        Artisan::call('schedule:run');
        return redirect()
            ->route('queue-test.index')
            ->with('status', 'Scheduler run triggered');
    }
}
