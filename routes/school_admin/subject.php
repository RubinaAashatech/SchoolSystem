<?php

use App\Http\Controllers\SchoolAdmin\SubjectController;


Route::resource('subjects', SubjectController::class);
Route::post('subjects/get', [SubjectController::class, 'getAllSubjects'])->name('subjects.get');

