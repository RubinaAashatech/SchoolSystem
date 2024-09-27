<?php

namespace App\Http\Controllers\MunicipalityAdmin;

use Auth;
use Carbon\Carbon;
use App\Models\Staff;
use App\Models\School;
use App\Models\Student;
use App\Models\StaffAttendance;
use App\Models\StudentAttendance;
use App\Models\StudentSession;
use App\Http\Controllers\Controller;
use App\Http\Services\SchoolService;
use App\Http\Services\DashboardService;
use App\Models\HeadTeacherLog;
use Anuzpandey\LaravelNepaliDate\LaravelNepaliDate;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $dashboardService;
    protected $schoolService;

    public function __construct(DashboardService $dashboardService, SchoolService $schoolService)
    {
        $this->dashboardService = $dashboardService;
        $this->schoolService = $schoolService;
    }

    public function index()
    {
        $user = Auth::user();
        $municipalityId = $user->municipality_id;
    
        // General Counts
        $totalStudents = Student::count();
    
        // Count the total girls across all schools
        $totalGirls = Student::whereHas('user', function ($query) {
            $query->where('gender', 'female');
        })->count();
    
        // Count the total boys across all schools
        $totalBoys = Student::whereHas('user', function ($query) {
            $query->where('gender', 'male');
        })->count();
    
        // Convert today's date to Nepali date
        $today = Carbon::today()->format('Y-m-d');
        $nepaliDateToday = LaravelNepaliDate::from($today)->toNepaliDate();
    
        // Initialize total present and absent students counters
        $totalPresentStudents = 0;
        $totalAbsentStudents = 0;
    
        // Get class-wise attendance data for all active students
        $classWiseData = StudentSession::where('is_active', 1)
            ->with([
                'studentAttendances' => function ($query) use ($nepaliDateToday) {
                    $query->whereDate('date', $nepaliDateToday);
                },
                'student.user'
            ])
            ->get();
    
        // Process attendance data to count present and absent students
        foreach ($classWiseData as $session) {
            $attendance = $session->studentAttendances->first();
            if ($attendance) {
                if ($attendance->attendance_type_id == 1) { // Present
                    $totalPresentStudents++;
                } elseif ($attendance->attendance_type_id == 2) { // Absent
                    $totalAbsentStudents++;
                }
            }
        }
    
        // Staff attendance count across all schools
        $totalStaffs = Staff::count();
        $presentStaffs = StaffAttendance::where('attendance_type_id', 1)
            ->where('date', $nepaliDateToday)
            ->count();
    
        $absentStaffs = StaffAttendance::where('attendance_type_id', 2)
            ->where('date', $nepaliDateToday)
            ->count();
    
        // Count major incidents reported today
        $majorIncidentsCount = HeadTeacherLog::where('logged_date', $nepaliDateToday)->count();
    
        // Municipality specific data - loop through schools
        $schools = School::where('municipality_id', $municipalityId)->get();
        $schoolData = [];
    
        foreach ($schools as $school) {
            $schoolId = $school->id;
    
            // Count the total students in the school
            $totalStudentsInSchool = Student::where('school_id', $schoolId)->count();
    
            // Get class-wise attendance data for the school
            $schoolClassWiseData = StudentSession::where('school_id', $schoolId)
                ->where('is_active', 1)
                ->with([
                    'studentAttendances' => function ($query) use ($nepaliDateToday) {
                        $query->whereDate('date', $nepaliDateToday);
                    },
                    'student.user'
                ])
                ->get();
    
            // Initialize present and absent counts for students in this school
            $presentStudentsInSchool = 0;
            $absentStudentsInSchool = 0;
    
            // Process class-wise attendance data for the school
            foreach ($schoolClassWiseData as $session) {
                $attendance = $session->studentAttendances->first();
                if ($attendance) {
                    if ($attendance->attendance_type_id == 1) { // Present
                        $presentStudentsInSchool++;
                    } elseif ($attendance->attendance_type_id == 2) { // Absent
                        $absentStudentsInSchool++;
                    }
                }
            }
    
            // Count the total staff in the school
            $totalStaffsInSchool = Staff::where('school_id', $schoolId)->count();
    
            // Count present and absent staff members for today
            $presentStaffsInSchool = StaffAttendance::where('attendance_type_id', 1)
                ->whereHas('staff', function ($query) use ($schoolId) {
                    $query->where('school_id', $schoolId);
                })
                ->where('date', $nepaliDateToday)
                ->count();
    
            $absentStaffsInSchool = StaffAttendance::where('attendance_type_id', 2)
                ->whereHas('staff', function ($query) use ($schoolId) {
                    $query->where('school_id', $schoolId);
                })
                ->where('date', $nepaliDateToday)
                ->count();
    
            // Add the school-specific data to the array
            $schoolData[] = [
                'school_id' => $school->id,
                'school_name' => $school->name,
                'school_address' => $school->address,
                'total_students' => $totalStudentsInSchool,
                'present_students' => $presentStudentsInSchool,
                'absent_students' => $absentStudentsInSchool,
                'total_staffs' => $totalStaffsInSchool,
                'present_staffs' => $presentStaffsInSchool,
                'absent_staffs' => $absentStaffsInSchool,
            ];
        }
    
        $totalSchools = School::count();
        $page_title = Auth::user()->getRoleNames()[0] . ' ' . "Dashboard";
    
        $todays_major_incidents = HeadTeacherLog::where('logged_date', $nepaliDateToday)
            ->get(['major_incidents', 'school_id']);
    
        $school_students = $this->schoolService->getSchoolStudent();
        $school_students_count = $this->getSchoolWiseStudents($school_students);
    
        $school_staffs = $this->schoolService->getSchoolStaff();
        $school_staffs_count = $this->schoolWiseCountOfStaff($school_staffs);
    
        $school_wise_student_attendences = $this->schoolService->getSchoolWiseStudentAttendence();
    
        return view('backend.municipality_admin.dashboard.dashboard', [
            'presentStudents' => $totalPresentStudents,
            'totalStudents' => $totalStudents,
            'absentStudents' => $totalAbsentStudents,
            'totalStaffs' => $totalStaffs,
            'presentStaffs' => $presentStaffs,
            'absentStaffs' => $absentStaffs,
            'schoolData' => $schoolData,
            'major_incidents' => $majorIncidentsCount,
            'totalSchools' => $totalSchools,
            'totalGirls' => $totalGirls,
            'totalBoys' => $totalBoys,
            'todays_major_incidents' => $todays_major_incidents,
            'school_staffs' => $school_staffs,
            'school_staffs_count' => $school_staffs_count,
            'school_students_count' => $school_students_count,
            'school_wise_student_attendences' => $school_wise_student_attendences,
        ]);
    }
    
    

    private function getSchoolWiseStudents()
    {
        // Join the students table with the schools table and count the students per school
        $schoolWiseStudents = Student::join('schools', 'students.school_id', '=', 'schools.id')
            ->select('schools.name as school_name', DB::raw('count(students.id) as total_students'))
            ->groupBy('schools.name')
            ->get()
            ->toArray();

        return $this->formatChartData($schoolWiseStudents, 'school_name', 'total_students', 'School wise Student Count');
    }

    private function formatChartData($data, $labelField, $dataField, $chartLabel)
    {
        $labels = array_column($data, $labelField);
        $dataValues = array_column($data, $dataField);

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => $chartLabel,
                    'data' => $dataValues,
                    'borderWidth' => 1
                ]
            ]
        ];
    }

    // public function schoolWiseCountOfStudent($originalData)
    // {
    //     $labels = [];
    //     $data = [];

    //     foreach ($originalData as $item) {
    //         $labels[] = $item['name'];
    //         $data[] = $item['total_student'];
    //     }

    //     return [
    //         'labels' => $labels,
    //         'datasets' => [
    //             [
    //                 'label' => 'School wise Student Count',
    //                 'data' => $data,
    //                 'borderWidth' => 1
    //             ]
    //         ]
    //     ];
    // }

    public function schoolWiseCountOfStaff($originalData)
    {
        $labels = [];
        $data = [];

        foreach ($originalData as $item) {
            $labels[] = $item['name'];
            $data[] = $item['total_staffs'];
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'School wise Staff Count',
                    'data' => $data,
                    'borderWidth' => 1
                ]
            ]
        ];
    }

    public function fetchMajorIncidents()
    {
        // Fetch major incidents reported today with school names
        $nepaliDateToday = LaravelNepaliDate::from(Carbon::today()->format('Y-m-d'))->toNepaliDate();
        $majorIncidents = HeadTeacherLog::where('logged_date', $nepaliDateToday)->get();
        
        return view('backend.municipality_admin.dashboard.major_incidents', [
            'majorIncidents' => $majorIncidents
        ]);
    }
}
