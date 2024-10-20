<?php


namespace App\Http\Controllers\MunicipalityAdmin;


use App\Http\Controllers\Controller;
use App\Models\StudentAttendance;
use App\Models\StaffAttendance;
use App\Models\School;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Anuzpandey\LaravelNepaliDate\LaravelNepaliDate;
use Yajra\DataTables\DataTables;


class AttendenceReportController extends Controller
{
   
        public function index()
        {
            // Fetch all schools for the dropdown
            $schools = School::all();
           
            // Render the index view with schools data
            return view('backend.municipality_admin.report.attendencereport.index', compact('schools'));
        }
   
        // Report method to handle the form submission and generate the report
        // Report method to handle the form submission and generate the report
// Report method to handle the form submission and generate the report
public function report(Request $request)
{
    // Get the input values
    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date'); // Make this nullable in your form validation
    $schoolId = $request->input('school_id');


    // Fetch schools for the dropdown
    $schools = School::all();


    // Initialize school data array
    $schoolData = [];


    // Validate that school is selected
    if ($schoolId) {
        // Check if both dates are provided (for date range)
        if ($fromDate && $toDate) {
            // Fetch student attendance
            $studentsAttendance = StudentAttendance::whereHas('studentSession', function ($query) use ($schoolId) {
                $query->where('school_id', $schoolId);
            })
            ->whereBetween('date', [$fromDate, $toDate])
            ->get();


            // Fetch staff attendance
            $staffAttendance = StaffAttendance::where('school_id', $schoolId)
                ->whereBetween('date', [$fromDate, $toDate])
                ->get();
        }
        // If only a single date is provided
        else if ($fromDate) {
            // Fetch student attendance
            $studentsAttendance = StudentAttendance::whereHas('studentSession', function ($query) use ($schoolId) {
                $query->where('school_id', $schoolId);
            })
            ->where('date', $fromDate)
            ->get();


            // Fetch staff attendance
            $staffAttendance = StaffAttendance::where('school_id', $schoolId)
                ->where('date', $fromDate)
                ->get();
        } else {
            // If no dates are provided, return early with a message or empty data
            return view('backend.municipality_admin.report.attendencereport.index', [
                'schoolData' => [],
                'schools' => $schools,
                'message' => 'Please select a date or date range.',
            ]);
        }


        // Group student attendance by date
        $groupedStudents = $studentsAttendance->groupBy('date');
        // Group staff attendance by date
        $groupedStaff = $staffAttendance->groupBy('date');


        // Process student attendance data
        foreach ($groupedStudents as $date => $attendance) {
            $totalStudents = $attendance->count();
            $totalPresentStudents = $attendance->where('attendance_type_id', 1)->count(); // Present
            $totalAbsentStudents = $attendance->where('attendance_type_id', 2)->count(); // Absent


            // Store the results for each date
            $schoolData[] = [
                'date' => $date,
                'total_students' => $totalStudents,
                'present_students' => $totalPresentStudents,
                'absent_students' => $totalAbsentStudents,
            ];
        }


        // Process staff attendance data
        foreach ($groupedStaff as $date => $attendance) {
            $totalStaff = $attendance->count();
            $totalPresentStaff = $attendance->where('attendance_type_id', 1)->count(); // Present
            $totalAbsentStaff = $attendance->where('attendance_type_id', 2)->count(); // Absent


            // Store the results for each date
            // Ensure to add or update the staff data to the existing entries
            $index = array_search($date, array_column($schoolData, 'date'));
            if ($index !== false) {
                $schoolData[$index]['total_staff'] = $totalStaff;
                $schoolData[$index]['present_staff'] = $totalPresentStaff;
                $schoolData[$index]['absent_staff'] = $totalAbsentStaff;
            } else {
                $schoolData[] = [
                    'date' => $date,
                    'total_staff' => $totalStaff,
                    'present_staff' => $totalPresentStaff,
                    'absent_staff' => $totalAbsentStaff,
                ];
            }
        }
    }


    return view('backend.municipality_admin.report.attendencereport.index', [
        'schoolData' => $schoolData,
        'schools' => $schools,
    ]);
}




public function getData(Request $request)
{
    $schoolId = $request->input('school_id');
    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date');


    // Convert dates to Gregorian
    $fromDateGregorian = LaravelNepaliDate::from($fromDate)->toEnglishDate();
    $toDateGregorian = LaravelNepaliDate::from($toDate)->toEnglishDate();


    $query = StudentAttendance::with(['student.user', 'studentSession'])
        ->when($schoolId, function ($query) use ($schoolId) {
            return $query->whereHas('studentSession', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            });
        });


    // Check if both dates are provided for date range
    if ($fromDate && $toDate) {
        $query->whereBetween('created_at', [$fromDateGregorian, $toDateGregorian]);
    }
    // If only a single date is provided
    else if ($fromDate) {
        $query->whereDate('created_at', $fromDateGregorian);
    }


    return DataTables::of($query)
        ->addColumn('student_name', function ($attendance) {
            return $attendance->student->user->f_name . ' ' . $attendance->student->user->l_name;
        })
        ->addColumn('attendance_type', function ($attendance) {
            return $attendance->is_present ? 'Present' : 'Absent';
        })
        ->rawColumns(['student_name', 'attendance_type'])
        ->make(true);
}
}

