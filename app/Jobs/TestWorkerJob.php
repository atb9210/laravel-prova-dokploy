<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TestWorkerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly string $payload
    ) {
    }

    public function handle(): void
    {
        Log::info('TestWorkerJob processed', [
            'payload' => $this->payload,
            'handled_at' => now()->toIso8601String(),
        ]);

        Cache::forever('test-worker:last-run', now()->toIso8601String());
        Cache::forever('test-worker:count', (int) Cache::get('test-worker:count', 0) + 1);
    }
}
