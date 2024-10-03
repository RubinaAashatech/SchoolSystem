<?php
use App\Http\Controllers\Shared\StudentProfileController;


Route::get('student-profile', [StudentProfileController::class, 'index'])->name('student-profile.index');

Route::get('students/profile/search', [StudentProfileController::class, 'profileSearch'])->name('students.profile.search');

Route::get('/students/profile/{id}', [StudentProfileController::class, 'profileShow'])->name('students.profile.show');

Route::get('students/get-students', [StudentProfileController::class, 'getStudents'])->name('students.get-students');
Route::get('/students/list', [StudentProfileController::class, 'getStudents'])->name('students.list');





