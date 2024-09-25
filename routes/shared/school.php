<?php

use App\Http\Controllers\Shared\SchoolController;

Route::resource('schools', SchoolController::class);
Route::post('schools/get', [SchoolController::class, 'getAllSchools'])->name('schools.get');

Route::get('/get-district-by-state/{state_id}', [SchoolController::class, 'getDistrict'])->name('get-districts');
Route::get('/get-municipality-by-district/{district_id}', [SchoolController::class, 'getMunicipality'])->name('get-municipalities');
Route::get('/get-ward-by-municipality/{municipality_id}', [SchoolController::class, 'getWard'])->name('get-wards');
//password reset
Route::post('/schools/reset-password/{id}', [SchoolController::class, 'resetPassword'])->name('admin.schools.reset-password');

Route::get('/get-school-id/{municipalityId}', [SchoolController::class, 'getSchoolId'])->name('get-schoolId');
