<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\NoticeView;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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
        $roles = $this->getRelevantRoles($user_type);
        return view('backend.shared.notices.index', compact('page_title', 'user_type', 'roles'));
    }

    private function getRelevantRoles($user_type)
    {
        if ($user_type === 'municipality') {
            return Role::whereIn('name', ['Head School', 'School Admin', 'Teacher', 'Student', 'Parent'])->get();
        } elseif ($user_type === 'school_admin') {
            return Role::whereIn('name', ['Teacher', 'Student', 'Parent'])->get();
        }
        return collect();
    }

    public function create()
    {
        return view('backend.shared.notices.create');
    }

    public function show($id)
    {
        $notice = Notice::findOrFail($id);
        return response()->json(['notice' => $notice]);    
    }
    
    public function store(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'release_date' => 'required|date_format:Y-m-d',
            'send_to' => 'required|array',
            'send_to.*' => 'exists:roles,id',
            'pdf_image' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $notice = Notice::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'notice_released_date' => Carbon::parse($data['release_date'])->startOfDay(),
            'notice_who_to_send' => json_encode($data['send_to']),
            'created_by' => $user->id,
        ]);

        if ($request->hasFile('pdf_image')) {
            $path = $request->file('pdf_image')->store('notices', 'public');
            $notice->pdf_image = $path;
        }

        $notice->save();

        return redirect()->route('admin.notices.index')->with('success', 'Notice created successfully.');
    }

    public function edit(Notice $notice)
    {
        $notice->notice_released_date = Carbon::parse($notice->notice_released_date)->toDateString();
        return response()->json($notice);
    }

    public function update(Request $request, Notice $notice)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'release_date' => 'required|date_format:Y-m-d',
            'send_to' => 'required|array',
            'send_to.*' => 'exists:roles,id',
            'pdf_image' => 'nullable|mimes:pdf,jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();
        $data['notice_who_to_send'] = json_encode($request->send_to);
        $data['notice_released_date'] = Carbon::parse($request->release_date)->startOfDay();

        if ($request->hasFile('pdf_image')) {
            if ($notice->pdf_image) {
                Storage::disk('public')->delete($notice->pdf_image);
            }
            $data['pdf_image'] = $request->file('pdf_image')->store('notices', 'public');
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
            $userRole = Role::find($user->role_id);
    
            $notices = Notice::select(['id', 'title', 'description', 'notice_released_date', 'notice_who_to_send', 'created_by']);
    
            if ($userRole->name === 'Municipality Admin') {
                $notices->where('created_by', $user->id);
            } 
            elseif ($userRole->name === 'School Admin') {
                $notices->where(function ($query) use ($user) {
                    $query->where('created_by', $user->id)
                        ->orWhereRaw("JSON_CONTAINS(notice_who_to_send, ?)", ['"'.$user->role_id.'"'])
                        ->orWhereIn('created_by', function ($subQuery) {
                            $subQuery->select('id')
                                ->from('users')
                                ->where('role_id', Role::where('name', 'Municipality Admin')->first()->id);
                        });
                });
            } 
            else {
                $notices->where(function ($query) use ($user) {
                    $query->where('created_by', $user->id)
                        ->orWhereRaw("JSON_CONTAINS(notice_who_to_send, ?)", ['"'.$user->role_id.'"'])
                        ->orWhereIn('created_by', function ($subQuery) {
                            $subQuery->select('id')
                                ->from('users')
                                ->where('role_id', Role::where('name', 'Municipality Admin')->first()->id);
                        })
                        ->orWhereIn('created_by', function ($subQuery) {
                            $subQuery->select('id')
                                ->from('users')
                                ->where('role_id', Role::where('name', 'School Admin')->first()->id);
                        });
                });
            }
    
            return DataTables::of($notices)
                ->addColumn('release_date', function ($notice) {
                    return $notice->notice_released_date;
                })
                ->addColumn('send_to', function ($notice) {
                    $sendTo = json_decode($notice->notice_who_to_send, true);
                    $roleNames = Role::whereIn('id', $sendTo)->pluck('name')->toArray();
                    return implode(', ', $roleNames);
                })
                ->addColumn('action', function ($notice) use ($user, $userRole) {
                    $actions = '';
                    if ($userRole->name === 'Municipality Admin' || $notice->created_by == $user->id) {
                        $actions .= '<button class="btn btn-primary btn-sm editNotice" data-id="' . $notice->id . '">Edit</button> ';
                        $actions .= '<button class="btn btn-danger btn-sm deleteNotice" data-id="' . $notice->id . '">Delete</button>';
                    } else {
                        $actions .= '<button class="btn btn-info btn-sm viewNotice" data-id="' . $notice->id . '">View</button>';
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
    
    
    private function userCanViewNotice($user, $notice)
    {
        $userType = $this->getUserType();
        $sendTo = json_decode($notice->notice_who_to_send, true);

        return in_array($user->role_id, $sendTo) || $notice->created_by == $user->id || $notice->created_by == $user->school_id;
    }

    public function markAsRead(Request $request, $noticeId)
    {
        $userId = Auth::id();
        NoticeView::firstOrCreate([
            'notice_id' => $noticeId,
            'user_id' => $userId,
        ], ['viewed_at' => now()]);

        return response()->json(['success' => true]);
    }
}
