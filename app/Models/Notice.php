<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'pdf_image',
        'notice_released_date',
        'notice_who_to_send',
        'municipality_id',
        'created_by'
    ];
    protected $casts = [
        'notice_released_date' => 'date',
        'notice_who_to_send' => 'array',
    ];

    public function user()
{
    return $this->belongsTo(User::class);
}

public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}

public function municipality()
{
    return $this->belongsTo(Municipality::class);
}

    public function views()
    {
        return $this->hasMany(NoticeView::class);
    }

    public static function getUnreadNoticesForSchool()
    {
        $schoolId = Auth::id();
        return self::where('created_by', 3) // Assuming 3 is the municipality's user ID
            ->whereJsonContains('notice_who_to_send', 'school')
            ->whereDoesntHave('views', function ($query) use ($schoolId) {
                $query->where('user_id', $schoolId);
            })
            ->latest()
            ->first();
    }
}
