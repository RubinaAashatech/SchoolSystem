<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\NoticeView;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NoticeController extends Controller
{
    private function getUserType()
    {
        $user = Auth::user();
        if ($user->school_id) {
            return 'school_admin';
        }
        return $user->type ?? 'municipality';
    }

    public function index()
    {
        $page_title = 'Notice Listing';
        $user_type = $this->getUserType();
        return view('backend.shared.notices.index', compact('page_title',  'user_type'));
    }

    public function create()
    {
        return view('backend.shared.notices.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'release_date' => 'required|date',
            'send_to' => 'required|array',
            'pdf_image' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $notice = new Notice();
        $notice->title = $data['title'];
        $notice->description = $data['description'];
        $notice->notice_released_date = $data['release_date'];
        $notice->notice_who_to_send = json_encode($data['send_to']);
        $notice->created_by = $user->user_type === 'municipality' ? 'municipality' : $user->id;

        if ($request->hasFile('pdf_image')) {
            $path = $request->file('pdf_image')->store('notices', 'public');
            $notice->pdf_image = $path;
        }

        $notice->save();

        return redirect()->route('admin.notices.index')->with('success', 'Notice created successfully.');
    
    }

    public function edit(Notice $notice)
    {
        return response()->json($notice);
    }

    public function update(Request $request, Notice $notice)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'release_date' => 'required|date',
            'send_to' => 'required|array',
            'send_to.*' => 'in:school,teacher,parent,student,school_group_head',
            'pdf_image' => 'nullable|mimes:pdf,jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();
        $data['notice_who_to_send'] = json_encode($request->send_to);
        $data['notice_released_date'] = $request->release_date;

        if ($request->hasFile('pdf_image')) {
            $data['pdf_image'] = $request->file('pdf_image')->store('notices');
        }

        $notice->update($data);

        return redirect()->route('admin.notices.index')->with('success', 'Notice updated successfully.');
    }

    public function destroy(Notice $notice)
{
    try {
        $notice->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true], 200); 
        }

        return redirect()->route('admin.notices.index')->with('success', 'Notice deleted successfully.');

    } catch (\Exception $e) {
        if (request()->ajax()) {
            return response()->json(['error' => 'Error deleting notice'], 500); 
        }

        return redirect()->route('admin.notices.index')->with('error', 'Error deleting notice.');
    }
}

    public function getNotices(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'Not found'], 404);
        }
    
        try {
            $user = Auth::user();
            $userType = $user->user_type;
    
            $notices = Notice::select(['id', 'title', 'description', 'notice_released_date', 'notice_who_to_send', 'created_by']);
    
            if ($userType === 'municipality') {
                $notices->where('created_by', 'municipality');
            } elseif ($userType === 'school_admin') {
                $notices->where(function ($query) use ($user) {
                    $query->where('created_by', 'municipality')
                          ->orWhere('created_by', $user->id);
                });
            } else {
                $notices->where(function ($query) use ($user, $userType) {
                    $query->where('created_by', 'municipality')
                          ->orWhere('created_by', $user->school_id)
                          ->orWhereRaw("JSON_CONTAINS(notice_who_to_send, ?)", ['"'.$userType.'"']);
                });
            }
            $notices->orWhere('created_by', $user->id);
    
            return DataTables::of($notices)
                ->addColumn('release_date', function ($notice) {
                    return $notice->notice_released_date ? $notice->notice_released_date->format('Y-m-d') : '';
                })
                ->addColumn('send_to', function ($notice) {
                    $sendTo = json_decode($notice->notice_who_to_send, true);
                    return is_array($sendTo) ? implode(', ', $sendTo) : '';
                })
                ->addColumn('action', function ($notice) use ($user, $userType) {
                    $actions = '';
                    if (($userType === 'municipality' && $notice->created_by === 'municipality') ||
                        ($notice->created_by == $user->id)) {
                        $actions .= '<button class="btn btn-primary btn-sm editNotice" data-id="' . $notice->id . '">Edit</button> ';
                        $actions .= '<button class="btn btn-danger btn-sm deleteNotice" data-id="' . $notice->id . '">Delete</button>';
                    }
                    return $actions;
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Exception $e) {
            Log::error('Error in getNotices: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while processing your request.'], 500);
        }
    }
    public function markNoticeAsRead($noticeId, $userId)
{

    $existingView = NoticeView::where('notice_id', $noticeId)
        ->where('user_id', $userId)
        ->first();

    if (!$existingView) {
    
        NoticeView::create([
            'notice_id' => $noticeId,
            'user_id' => $userId,
            'viewed_at' => now(),
        ]);
    } else {
        Log::info("Notice {$noticeId} was already marked as read for user {$userId}");
    }
}      
}
