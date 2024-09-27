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
        Log::info("Fetching unread notices for school ID: {$schoolId}");
    
        $unreadNotice = self::where('created_by', 3)
            ->latest()
            ->first();
    
        if ($unreadNotice) {
            $alreadyViewed = NoticeView::where('notice_id', $unreadNotice->id)
                ->where('user_id', $schoolId)
                ->exists();
    
            Log::info('Already viewed by school: ' . ($alreadyViewed ? 'Yes' : 'No'));
    
            if (!$alreadyViewed) {
                Log::info('Unread notice found: ' . $unreadNotice->id);
                return $unreadNotice;
            }
        }
        Log::info('No unread notices found.');
        return null;
    }   

}
