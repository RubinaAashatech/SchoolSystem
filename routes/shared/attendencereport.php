<?php

use App\Http\Controllers\MunicipalityAdmin\AttendenceReportController;
use App\Http\Controllers\SchoolAdmin\SchoolAttendenceReportController;

Route::get('/attendance-reports', [AttendenceReportController::class, 'index'])->name('attendance_reports.index');
Route::get('/attendance-reports/report', [AttendenceReportController::class, 'report'])->name('attendance_reports.report');
Route::get('admin/attendance-reports/data', [AttendenceReportController::class, 'getData'])->name('attendance_reports.data');

Route::get('/school-attendance-reports', [SchoolAttendenceReportController::class, 'index'])->name('school_attendance_reports.index');
Route::get('/school-attendance-reports/report', [SchoolAttendenceReportController::class, 'report'])->name('school_attendance_reports.report');
Route::get('school-attendance-reports/data', [SchoolAttendenceReportController::class, 'getData'])->name('school_attendance_reports.data');

Route::get('fetch-sections', [SchoolAttendenceReportController::class, 'fetchSections'])->name('fetch-sections');

Route::get('/school-attendance-reports/search', [SchoolAttendenceReportController::class, 'search'])->name('school_attendance_reports.search');
Route::get('/school-attendance-reports/load-sections', [SchoolAttendenceReportController::class, 'loadSections'])->name('school_attendance_reports.load_sections');







