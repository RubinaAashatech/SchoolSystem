<?php

namespace App\Http\Controllers\MunicipalityAdmin;

use Carbon\Carbon;
use App\Models\Staff;
use App\Models\School;
use App\Models\Student;
use App\Models\Notice;
use App\Models\EcaActivity;
use App\Models\StaffAttendance;
use App\Models\StudentAttendance;
use App\Http\Controllers\Controller;
use App\Http\Services\SchoolService;
use App\Http\Services\DashboardService;
use App\Models\HeadTeacherLog;
use Anuzpandey\LaravelNepaliDate\LaravelNepaliDate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        
        $today = Carbon::today()->format('Y-m-d');

        // Convert today's date to Nepali date
        $nepaliDateToday = LaravelNepaliDate::from($today)->toNepaliDate();

        $presentStudents = StudentAttendance::where('attendance_type_id', 1)
            ->where('date', $nepaliDateToday)
            ->count();

        $absentStudents = StudentAttendance::where('attendance_type_id', 2)
            ->where('date', $nepaliDateToday)
            ->count();

        $totalStaffs = Staff::count();
        $presentStaffs = StaffAttendance::where('attendance_type_id', 1)
            ->where('date', $nepaliDateToday)
            ->count();

        $absentStaffs = StaffAttendance::where('attendance_type_id', 2)
            ->where('date', $nepaliDateToday)
            ->count();

        $majorIncidentsCount = HeadTeacherLog::where('logged_date', $nepaliDateToday)->count();

        // Municipality specific data
        $municipalityId = Auth::user()->municipality_id;
        $schools = School::where('municipality_id', $municipalityId)->get();
        $schoolData = [];

        foreach ($schools as $school) {
            $schoolId = $school->id;

            // Count the total students in the school
            $totalStudentsInSchool = Student::where('school_id', $schoolId)->count();

            // Count the present students for today
            $presentStudentsInSchool = StudentAttendance::where('attendance_type_id', 1)
                ->whereHas('student', function($query) use ($schoolId) {
                    $query->where('school_id', $schoolId);
                })
                ->where('date', $nepaliDateToday)
                ->count();

            // Count the absent students for today
            $absentStudentsInSchool = StudentAttendance::where('attendance_type_id', 2)
                ->whereHas('student', function($query) use ($schoolId) {
                    $query->where('school_id', $schoolId);
                })
                ->where('date', $nepaliDateToday)
                ->count();

            // Count the total staff in the school
            $totalStaffsInSchool = Staff::where('school_id', $schoolId)->count();

            // Count the present staff members for today
            $presentStaffsInSchool = StaffAttendance::where('attendance_type_id', 1)
                ->whereHas('staff', function($query) use ($schoolId) {
                    $query->where('school_id', $schoolId);
                })
                ->where('date', $nepaliDateToday)
                ->count();

            // Count the absent staff members for today
            $absentStaffsInSchool = StaffAttendance::where('attendance_type_id', 2)
                ->whereHas('staff', function($query) use ($schoolId) {
                    $query->where('school_id', $schoolId);
                })
                ->where('date', $nepaliDateToday)
                ->count();

            // Add the data to the array
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

        // Get school-wise student data
        $schoolWiseStudentData = $this->getSchoolWiseStudents();

        $totalSchools = School::count();
        $page_title = Auth::user()->getRoleNames()[0] . ' ' . "Dashboard";

        $todays_major_incidents = HeadTeacherLog::where('logged_date', $nepaliDateToday)
            ->get(['major_incidents', 'school_id']);
           

        $school_students = $this->schoolService->getSchoolStudent();
        $school_students_count = $this->getSchoolWiseStudents($school_students);

        $school_staffs = $this->schoolService->getSchoolStaff();
        $school_staffs_count = $this->schoolWiseCountOfStaff($school_staffs);
        
        $school_wise_student_attendences = $this->schoolService->getSchoolWiseStudentAttendence();
        $school_staffs_count = $this->schoolWiseCountOfStaff($school_staffs);
        $noticeData = $this->fetchMunicipalityNoticeData();
        $ECAData = $this->fetchMunicipalityEcaData();
        return view('backend.municipality_admin.dashboard.dashboard', [
            'presentStudents' => $presentStudents,
            'totalStudents' => $totalStudents,
            'absentStudents' => $absentStudents,
            'totalStaffs' => $totalStaffs,
            'presentStaffs' => $presentStaffs,
            'absentStaffs' => $absentStaffs,
            'schoolData' => $schoolData,
            'major_incidents' => $majorIncidentsCount,
            'totalSchools' => $totalSchools,
            'totalGirls' => $totalGirls, // Pass total girls count
            'totalBoys' => $totalBoys, // Pass total boys count
            'todays_major_incidents' => $todays_major_incidents,
            'school_staffs ' => $school_staffs,
            'school_staffs_count' => $school_staffs_count,
            'school_students_count' => $school_students_count,
            'school_wise_student_attendences' => $school_wise_student_attendences,
            'schoolWiseStudentData' => $schoolWiseStudentData, // Pass school-wise student data
            'noticeCount' => $noticeData['count'],
            'ECACount' => $ECAData['count'],
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

    private function fetchMunicipalityNoticeData()
    {
        $municipalityId = Auth::user()->municipality_id; 
        $notices = Notice::whereHas('creator', function($query) {
            $query->where('user_type_id', 3); 
        })->get();
    
        $count = $notices->count();
    
        return [
            'count' => $count,
        ];
    }
    private function fetchMunicipalityEcaData()
    {
        $municipalityId = Auth::user()->municipality_id; 
        $ecaActivities = EcaActivity::whereHas('creator', function($query) {
            $query->where('user_type_id', 3); 
        })->get();
    
        $count = $ecaActivities->count();
    
        return [
            'count' => $count,
        ];
    }
    

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
