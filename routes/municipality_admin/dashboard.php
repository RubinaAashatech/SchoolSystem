<?php
use App\Http\Controllers\MunicipalityAdmin\DashboardController;

Route::get('/municipality/dashboard', 'DashboardController@index')->name('municipality.dashboard');
Route::get('/dashboard/major-incidents', [DashboardController::class, 'fetchMajorIncidents']);
Route::post('/admin/mark-holiday-range', [DashboardController::class, 'markHolidayRange'])->name('admin.student.mark-holiday-range');
Route::post('/municipality/mark-holiday-range', [DashboardController::class, 'markHolidayRange'])->name('municipality.mark-holiday-range');