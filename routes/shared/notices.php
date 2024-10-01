<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Shared\NoticeController;


Route::resource('notices', NoticeController::class);
Route::post('notices/get', [NoticeController::class, 'getNotices'])->name('notices.get');
Route::get('/notices/{notice}/view', [NoticeController::class, 'view'])->name('notices.view');
Route::get('/notices/{notice}', [NoticeController::class, 'show'])->name('notices.show');

Route::get('/get-new-notice', [LoginController::class, 'getNewNotice'])->name('get.new.notice');
Route::post('/notice/{id}/mark-as-read', [NoticeController::class, 'markAsRead'])->name('notice.markAsRead');
Route::get('notices/{id}/download', [NoticeController::class, 'downloadNoticeAttachment'])->name('notices.download');



