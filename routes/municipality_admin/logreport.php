<?php
use App\Http\Controllers\MunicipalityAdmin\HeadTeacherLogController;

Route::get('municipalityhead-teacher-logs', [HeadTeacherLogController::class, 'index'])->name('municipality-headteacher-logs.index');
Route::get('municipalityhead-teacher-logs/get', [HeadTeacherLogController::class, 'getAllHeadTeacherLogs'])->name('municipality-headteacher-logs.get');
Route::get('municipalityhead-teacher-logs/{id}', [HeadTeacherLogController::class, 'show'])->name('municipality-headteacher-logs.show');

Route::get('municipality-headteacher-logs/export', [HeadTeacherLogController::class, 'exportToExcel'])->name('municipality-headteacher-logs.export');