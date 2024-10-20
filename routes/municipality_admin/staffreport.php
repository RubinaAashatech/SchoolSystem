<?php
use App\Http\Controllers\MunicipalityAdmin\StaffReportController;


Route::get('admin/staff_reports', [StaffReportController::class, 'index'])->name('staff_reports.index');
Route::get('admin/staff_reports/report', [StaffReportController::class, 'report'])->name('staff_reports.report');
