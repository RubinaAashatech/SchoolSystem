<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoticeView extends Model
{
    protected $fillable = ['notice_id', 'user_id', 'viewed_at'];

    public function notice()
    {
        return $this->belongsTo(Notice::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}