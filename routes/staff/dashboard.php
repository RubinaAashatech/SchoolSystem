<?php

use App\Http\Controllers\Staff\DashboardController;

Route::get('/teacher/dashboard', [DashboardController::class, 'index'])->name('teacher.dashboard');
// Route::get('/staff/dashboard', [TeacherDashboardController::class, 'index'])->name('staff.dashboard');
