<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    Log::info('Scheduler tick', [
        'scheduled_at' => now()->toIso8601String(),
    ]);
    Cache::forever('scheduler:last-run', now()->toIso8601String());
    Cache::forever('scheduler:count', (int) Cache::get('scheduler:count', 0) + 1);
})->everyMinute();
