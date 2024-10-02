<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

public function municipality()
{
    return $this->belongsTo(Municipality::class);
}

    public function views()
    {
        return $this->hasMany(NoticeView::class);
    }

    public function setNoticeWhoToSendAttribute($value)
    {
        $this->attributes['notice_who_to_send'] = json_encode($value);
    }

    public function getNoticeWhoToSendAttribute($value)
    {
        return json_decode($value, true);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function sentTo()
    {
        return $this->belongsToMany(Role::class, 'notice_role', 'notice_id', 'role_id');
    }

//     public static function getUnreadNoticesForUser($userId)
// {
//     Log::info("Fetching unread notices for user ID: {$userId}");
    
//     $user = User::findOrFail($userId);
//     $userTypeId = $user->user_type_id;
    
//     Log::info("User type ID: {$userTypeId}");

//     $userType = DB::table('user_types')->where('id', $userTypeId)->value('title');
//     Log::info("User type: {$userType}");

//     $query = self::where(function ($query) use ($userType) {
//             $query->whereJsonContains('notice_who_to_send', $userType)
//                   ->orWhereJsonContains('notice_who_to_send', 'school');
//         })
//         ->whereDoesntHave('views', function ($query) use ($userId) {
//             $query->where('user_id', $userId);
//         })
//         ->latest();

//     Log::info("SQL Query: " . $query->toSql());
//     Log::info("Query Bindings: " . json_encode($query->getBindings()));

//     $unreadNotices = $query->get();

//     Log::info("Unread notices count: " . $unreadNotices->count());
//     foreach ($unreadNotices as $notice) {
//         Log::info("Unread notice: ID {$notice->id}, Title: {$notice->title}, Who to send: " . $notice->notice_who_to_send);
//     }

//     return $unreadNotices;
// }
}
