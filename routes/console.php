<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// tiket yang sudah selesai dikerjakan tapi tidak dikonfirmasi customer
// dalam 7 hari akan ditutup otomatis oleh sistem setiap tengah malam
Schedule::command('app:auto-close-tickets --days=7')->dailyAt('00:00');
