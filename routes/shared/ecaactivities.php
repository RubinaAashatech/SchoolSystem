<?php
use App\Http\Controllers\Shared\EcaParticipationController;
use App\Http\Controllers\Shared\EcaActivityController;

// Existing routes
Route::resource('eca_activities', EcaActivityController::class);
Route::post('eca_activities/get', [EcaActivityController::class, 'getEcaActivities'])->name('eca_activities.get');
Route::get('/admin/get-classes', [EcaActivityController::class, 'getClasses'])->name('get_classes');
Route::get('/admin/get-sections', [EcaActivityController::class, 'getSections'])->name('get_sections');
route::get('/get-students', [EcaActivityController::class, 'getStudents'])->name('get_students');
Route::get('admin/get-classes-for-activity', [EcaActivityController::class, 'getClassesForActivity'])->name('get_classes_for_activity');
Route::get('admin/get-schools-for-activity', [EcaActivityController::class, 'getSchoolsForActivity'])->name('get_schools_for_activity');

// Route for handling participation submission
Route::post('extraactivities_participate/store', [EcaActivityController::class, 'storeParticipation'])->name('extraactivities_participate.store');


Route::post('extraactivities_participate/store', [EcaParticipationController::class, 'store'])->name('extraactivities_participate.store');
Route::get('eca-participations/get', [EcaParticipationController::class, 'getEcaParticipations'])->name('eca_participations.get');


