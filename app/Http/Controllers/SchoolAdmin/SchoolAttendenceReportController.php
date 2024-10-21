<?php
namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\StudentAttendance;
use App\Models\School;
use App\Models\Section;
use App\Models\Classg;
use App\Models\ClassSection;
use App\Models\StudentSession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SchoolAttendenceReportController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        $classes = Classg::where('school_id', $schoolId)->get();
        $selectedClass = $request->input('class_id');
        $sections = $selectedClass ? Section::whereHas('classSections', function($query) use ($selectedClass) {
            $query->where('class_id', $selectedClass);
        })->get() : collect();

        // Get current date in YYYY-MM-DD format
        $currentDate = Carbon::now()->format('Y-m-d');

        return view('backend.school_admin.report.index', compact('classes', 'sections', 'currentDate'));
    }

    public function report(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        $classes = Classg::where('school_id', $schoolId)->get();
        $selectedClass = $request->input('class_id');
        $selectedSection = $request->input('section_id');
        $selectedDate = $request->input('date');

        $sections = $selectedClass ? Section::whereHas('classSections', function($query) use ($selectedClass) {
            $query->where('class_id', $selectedClass);
        })->get() : collect();

        return view('backend.school_admin.report.index', compact('classes', 'sections', 'selectedClass', 'selectedSection', 'selectedDate'));
    }

    public function getData(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        $selectedClass = $request->input('class_id');
        $selectedSection = $request->input('section_id');
        $selectedDate = $request->input('date');
    
        if (empty($selectedClass) || empty($selectedSection)) {
            return DataTables::of(collect())->make(true);
        }
    
        $query = StudentAttendance::with(['student.user', 'studentSession.classg', 'studentSession.section'])
            ->whereHas('studentSession', function ($q) use ($schoolId, $selectedClass, $selectedSection) {
                $q->where('school_id', $schoolId)
                  ->when($selectedClass, function ($query) use ($selectedClass) {
                      return $query->where('class_id', $selectedClass);
                  })
                  ->when($selectedSection, function ($query) use ($selectedSection) {
                      return $query->where('section_id', $selectedSection);
                  });
            })
            ->when($selectedDate, function ($query) use ($selectedDate) {
                return $query->whereDate('date', $selectedDate);
            });
    
        return DataTables::of($query)
            ->addColumn('student_name', function ($attendance) {
                return $attendance->student->user->f_name . ' ' . $attendance->student->user->l_name;
            })
            ->addColumn('attendance_type', function ($attendance) {
                return $attendance->attendance_type_id == 1 ? 'Present' : 'Absent';
            })
            ->addColumn('class', function ($attendance) {
                return $attendance->studentSession->classg->class ?? 'N/A';
            })        
            ->addColumn('section', function ($attendance) {
                return $attendance->studentSession->section->section_name ?? 'N/A';
            })
            ->rawColumns(['student_name', 'attendance_type', 'class', 'section'])
            ->make(true);
    }
    
}