<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('vaccination:send-emails')->dailyAt('21:00');
