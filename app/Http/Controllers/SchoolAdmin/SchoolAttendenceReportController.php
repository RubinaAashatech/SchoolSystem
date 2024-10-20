<?php
namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\StudentAttendance;
use App\Models\School;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Anuzpandey\LaravelNepaliDate\LaravelNepaliDate;
use Yajra\DataTables\DataTables;

class SchoolAttendenceReportController extends Controller
{
    public function index()
    {
        $schools = School::all(); 
        return view('backend.school_admin.report.index', compact('schools'));
    }

    public function report(Request $request)
    {
        $inputDate = $request->input('date', Carbon::today()->format('Y-m-d')); 
        $date = LaravelNepaliDate::from($inputDate)->toEnglishDate();
        $schools = School::all(); 
        $studentAttendances = StudentAttendance::whereDate('created_at', $date)->get();
        return view('backend.school_admin.report.index', compact('studentAttendances', 'date','schools'));
    }

    public function getData(Request $request)
{
    $inputDate = $request->input('date', Carbon::today()->format('Y-m-d'));
    $schoolId = auth()->user()->school_id; 

    $date = LaravelNepaliDate::from($inputDate)->toEnglishDate();

    $query = StudentAttendance::with(['student.user', 'studentSession.classg', 'studentSession.section'])
        ->whereDate('created_at', $date)
        ->whereHas('studentSession', function ($q) use ($schoolId) {
            $q->where('school_id', $schoolId);
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