<?php
Route::get('/school-admin/dashboard', 'DashboardController@index')->name('schoolAdmin.dashboard');
Route::post('/school/mark-notice-read/{noticeId}', 'DashboardController@markNoticeAsRead')->name('school.markNoticeAsRead');