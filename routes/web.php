<?php

use Illuminate\Support\Facades\Route;
use App\Jobs\SendEmailJob;

Route::get('/send', function () {
    SendEmailJob::dispatch('user@example.com');
    return "Job pushed to RabbitMQ!";
});

Route::get('/', function () {
    return view('welcome');
});
