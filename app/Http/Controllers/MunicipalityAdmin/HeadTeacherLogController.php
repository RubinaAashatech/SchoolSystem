<?php

namespace App\Http\Controllers\MunicipalityAdmin;

use App\Http\Controllers\Controller;
use App\Models\HeadTeacherLog;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

class HeadTeacherLogController extends Controller
{
    public function index()
    {
        $this->authorize('view_municipality_headteacher_logs');
        $page_title = "Municipality Head Teacher Logs";
        return view('backend.municipality_admin.report.logreport.index', compact('page_title'));
    }

    public function getAllHeadTeacherLogs(Request $request)
    {
        try {
            $query = HeadTeacherLog::join('schools', 'head_teacher_logs.school_id', '=', 'schools.id')
                ->select('head_teacher_logs.*', 'schools.name as school_name');

            return DataTables::of($query)
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && $request->get('search')['value']) {
                        $searchValue = $request->get('search')['value'];
                        $query->where(function ($q) use ($searchValue) {
                            $q->where('schools.name', 'like', "%{$searchValue}%")
                              ->orWhere('head_teacher_logs.major_incidents', 'like', "%{$searchValue}%");
                        });
                    }
                })
                ->addColumn('school_name', function ($log) {
                    return $log->school_name;
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Exception $e) {
            Log::error('Error in getAllHeadTeacherLogs: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while processing the request.'], 500);
        }
    }
}