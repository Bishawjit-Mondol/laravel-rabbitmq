<?php

use Illuminate\Support\Facades\Route;
use App\Jobs\SendEmailJob;

Route::get('/send', function () {
    $startTime = microtime(true);

    for ($i = 1; $i <= 100; $i++) {
        SendEmailJob::dispatch("user{$i}@example.com");
    }

    $endTime = microtime(true);
    $executionTime = round(($endTime - $startTime) * 1000, 2);

    return "100 jobs pushed to RabbitMQ in {$executionTime}ms!";
});

Route::get('/logs', function () {
    $logFile = storage_path('logs/laravel.log');

    if (!file_exists($logFile)) {
        return "No log file found.";
    }

    $logs = file_get_contents($logFile);
    $emailLogs = collect(explode("\n", $logs))
        ->filter(fn($line) => str_contains($line, 'Sending email to:'))
        ->take(100)
        ->values();

    return response()->json([
        'total_emails_logged' => $emailLogs->count(),
        'logs' => $emailLogs
    ]);
});

Route::get('/', function () {
    return view('welcome');
});
