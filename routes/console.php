<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('documents:check-expiry')->dailyAt('09:00');
