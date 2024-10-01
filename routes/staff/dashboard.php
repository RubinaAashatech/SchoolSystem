<?php

use App\Http\Controllers\Staff\DashboardController;

Route::get('/staff/dashboard', [DashboardController::class, 'index'])->name('staff.dashboard');
