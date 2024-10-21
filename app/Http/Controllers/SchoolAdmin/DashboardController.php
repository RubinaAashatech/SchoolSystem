<?php


namespace App\Http\Controllers\SchoolAdmin;


use App\Models\Unit;
use App\Models\User;
use App\Models\Staff;
use App\Models\Stock;
use App\Models\School;
use App\Models\Product;
use App\Models\Classg;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\StudentSession;
use App\Models\StaffAttendance;
use App\Models\StudentAttendance;
use App\Http\Controllers\Controller;
use App\Http\Services\SchoolService;
use App\Http\Services\DashboardService;
use Carbon\Carbon;
use Anuzpandey\LaravelNepaliDate\LaravelNepaliDate;
use Illuminate\Support\Facades\DB;
use App\Models\Notice;
use App\Models\NoticeView;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $dashboardService;
    protected $schoolService;


    public function __construct(DashboardService $dashboardService, SchoolService $schoolService)
    {
        $this->dashboardService = $dashboardService;
        $this->schoolService = $schoolService;
    }


 public function index(Request $request)
{
    $user = Auth::user();
    $schoolName = $user->f_name;


    // Calculate initials from the school name
    $words = explode(' ', $schoolName);
    $initials = '';
    foreach ($words as $word) {
        $initials .= strtoupper(substr($word, 0, 1));
    }


    $schoolId = Auth::user()->school_id;


    // Count total students, boys, and girls
        // Count total students, boys, and girls using StudentSession
    $totalStudents = Student::where('school_id', $schoolId)
       
        ->count();


    $totalGirls = StudentSession::where('school_id', $schoolId)
        ->where('is_active', 1)
        ->whereHas('student.user', function ($query) {
            $query->where('gender', 'female');
        })
        ->count();


    $totalBoys = StudentSession::where('school_id', $schoolId)
        ->where('is_active', 1)
        ->whereHas('student.user', function ($query) {
            $query->where('gender', 'male');
        })
        ->count();


    // Convert today's date to Nepali date
    $today = Carbon::today()->format('Y-m-d');
    $nepaliDateToday = LaravelNepaliDate::from($today)->toNepaliDate();


    // Get class-wise attendance data
    $classWiseData = StudentSession::where('school_id', $schoolId)
        ->where('is_active', 1)
        ->with([
            'studentAttendances' => function ($query) use ($nepaliDateToday) {
                $query->whereDate('date', $nepaliDateToday);
            },
            'student.user'
        ])
        ->get();


    // Initialize total present and absent counts for boys and girls
    $totalPresentBoys = 0;
    $totalPresentGirls = 0;
    $totalAbsentBoys = 0;
    $totalAbsentGirls = 0;


    // Process class-wise attendance data
    foreach ($classWiseData as $session) {
        $attendance = $session->studentAttendances->first();
        if ($attendance) {
            $gender = strtolower($session->student->user->gender ?? 'unknown');
            if ($attendance->attendance_type_id == 1) { // Present
                if ($gender == 'male') {
                    $totalPresentBoys++;
                } elseif ($gender == 'female') {
                    $totalPresentGirls++;
                }
            } elseif ($attendance->attendance_type_id == 2) { // Absent
                if ($gender == 'male') {
                    $totalAbsentBoys++;
                } elseif ($gender == 'female') {
                    $totalAbsentGirls++;
                }
            }
        }
    }


    // Calculate total present and absent students
    $presentStudents = $totalPresentBoys + $totalPresentGirls;
    $absentStudents = $totalAbsentBoys + $totalAbsentGirls;


    // Calculate staff attendance
    $totalStaffs = Staff::where('school_id', $schoolId)->count();
    $presentStaffs = StaffAttendance::where('attendance_type_id', 1)
        ->whereHas('staff', function ($query) use ($schoolId) {
            $query->where('school_id', $schoolId);
        })
        ->whereDate('date', $nepaliDateToday)->count();
    $absentStaffs = StaffAttendance::where('attendance_type_id', 2)
        ->whereHas('staff', function ($query) use ($schoolId) {
            $query->where('school_id', $schoolId);
        })
        ->whereDate('date', $nepaliDateToday)->count();


    $page_title = Auth::user()->getRoleNames()[0] . ' ' . "Dashboard";


    // Additional data fetches (if any services are being used)
    $class_wise_students = $this->getClassWiseStudents();
    $class_wise_student_attendances = $this->getClassWiseStudentAttendance();
    $staff_data = $this->getStaffData();
    $staff_attendance = $this->getStaffAttendanceData();
    $noticeData = $this->fetchMunicipalityNoticeData();
    // $userId = Auth::id();
    // Log::info("Logged in user ID: {$userId}");
   
    // $unreadNotices = Notice::getUnreadNoticesForUser($userId);
    // Log::info("Unread notices fetched: " . $unreadNotices->count());


    $noticeCount = $noticeData['count'];

    $classesWithIncompleteAttendance = $this->getClassesWithIncompleteAttendance();

    $isHoliday = StudentAttendance::where('date', $nepaliDateToday)
    ->where('attendance_type_id', 4)
    ->exists();


    // Return view with compacted data
    return view('backend.school_admin.dashboard.dashboard', compact(
        'page_title', 'totalStudents', 'totalGirls', 'totalBoys',
        'totalPresentBoys', 'totalPresentGirls', 'totalAbsentBoys',
        'totalAbsentGirls', 'presentStudents', 'absentStudents',
        'totalStaffs', 'presentStaffs', 'absentStaffs', 'initials',
        'staff_attendance', 'noticeCount', 'staff_data', 'class_wise_student_attendances', 'class_wise_students','classesWithIncompleteAttendance','isHoliday'
    ));
}

public function schoolDashboard() {
    // Get today's Nepali date
    $currentDate = LaravelNepaliDate::from(Carbon::now()->toDateString())->toNepaliDate();
    // Check if today is marked as a holiday in the StudentAttendance table
    $isHoliday = StudentAttendance::where('date', $currentDate)
        ->where('attendance_type_id', 4)
        ->exists();
    // Return the dashboard view with the holiday info
    return view('backend.school_admin.dashboard.dashboard', [
        'isHoliday' => $isHoliday,
        'currentDate' => $currentDate
    ]);
}

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




public function markNoticeAsRead($noticeId)
{
    $userId = Auth::id();
    NoticeView::create([
        'notice_id' => $noticeId,
        'user_id' => $userId,
        'viewed_at' => now(),
    ]);




    return response()->json(['success' => true]);
}




    private function getClassWiseStudents()
{
    $schoolId = Auth::user()->school_id;


    // Join the students table with the classes table and count the students per class
    $classWiseStudents = Student::join('classes', 'students.class_id', '=', 'classes.id')
    ->where('students.school_id', $schoolId)
    ->select('classes.class as class_name', DB::raw('count(students.id) as total_students'))
    ->groupBy('classes.class')
    ->get()
    ->toArray();


    return $this->formatChartData($classWiseStudents, 'class_name', 'total_students', 'Class wise Student Count');
}


private function getClassWiseStudentAttendance()
{
    $schoolId = Auth::user()->school_id;
    $today = LaravelNepaliDate::from(Carbon::today()->format('Y-m-d'))->toNepaliDate();


    $classWiseAttendance = DB::table('student_sessions')
        ->join('users', 'student_sessions.user_id', '=', 'users.id')
        ->join('classes', 'student_sessions.class_id', '=', 'classes.id')
        ->leftJoin('student_attendances', function ($join) use ($today) {
            $join->on('student_sessions.id', '=', 'student_attendances.student_session_id')
                 ->where('student_attendances.date', $today);
        })
        ->where('student_sessions.school_id', $schoolId)
        ->select(
            'classes.class as class_name',
            DB::raw('count(student_sessions.id) as total_students'),
            DB::raw('count(case when student_attendances.attendance_type_id = 1 then 1 end) as present_students'),
            DB::raw('count(case when student_attendances.attendance_type_id = 2 then 1 end) as absent_students')
        )
        ->groupBy('classes.class')
        ->get();


    $labels = $classWiseAttendance->pluck('class_name');
    $totalStudents = $classWiseAttendance->pluck('total_students');
    $presentStudents = $classWiseAttendance->pluck('present_students');
    $absentStudents = $classWiseAttendance->pluck('absent_students');


    return [
        'labels' => $labels,
        'datasets' => [
            [
                'label' => 'Total Students',
                'data' => $totalStudents,
                'backgroundColor' => 'rgba(0, 0, 200, 0.5)',
                'borderWidth' => 1
            ],
            [
                'label' => 'Present Students',
                'data' => $presentStudents,
                'backgroundColor' => 'rgba(50, 200, 50, 0.5)',
                'borderWidth' => 1
            ],
            [
                'label' => 'Absent Students',
                'data' => $absentStudents,
                'backgroundColor' => 'rgba(255, 99, 132, 0.5)',
                'borderWidth' => 1
            ]
        ]
    ];
}




private function getClassesWithIncompleteAttendance() {
    $schoolId = Auth::user()->school_id;
    $today = LaravelNepaliDate::from(Carbon::today()->format('Y-m-d'))->toNepaliDate();


    $classesWithIncompleteAttendance = DB::table('classes')
        ->join('student_sessions', 'classes.id', '=', 'student_sessions.class_id')
        ->join('sections', 'student_sessions.section_id', '=', 'sections.id')
        ->leftJoin('student_attendances', function ($join) use ($today) {
            $join->on('student_sessions.id', '=', 'student_attendances.student_session_id')
                 ->where('student_attendances.date', $today);
        })
        ->where('classes.school_id', $schoolId)
        ->where('student_sessions.is_active', 1)
        ->groupBy('classes.id', 'classes.class', 'sections.id', 'sections.section_name')
        ->havingRaw('COUNT(DISTINCT student_sessions.id) > COUNT(student_attendances.id)')
        ->select(
            'classes.id as class_id',
            'classes.class as class_name',
            'sections.id as section_id',
            'sections.section_name',
            DB::raw('COUNT(DISTINCT student_sessions.id) as total_students'),
            DB::raw('COUNT(student_attendances.id) as attendance_count')
        )
        ->get();


    return $classesWithIncompleteAttendance;
}



    private function getStaffData()
    {
        $schoolId = Auth::user()->school_id;
   
        $staffRoles = Staff::join('roles', 'staffs.role', '=', 'roles.id')
            ->where('school_id', $schoolId)
            ->select('roles.name as role', DB::raw('count(*) as count'))
            ->groupBy('roles.name')
            ->get()
            ->toArray();


        return $this->formatChartData($staffRoles, 'role', 'count', 'Staff Count by Role');
    }


    private function getStaffAttendanceData()
    {
        $schoolId = Auth::user()->school_id;
        $today = LaravelNepaliDate::from(Carbon::today()->format('Y-m-d'))->toNepaliDate();
   
        // Fetch attendance data and join with roles to get role names
        $staffAttendanceData = DB::table('staff_attendances')
            ->join('staffs', 'staff_attendances.staff_id', '=', 'staffs.id')
            ->join('roles', 'staffs.role', '=', 'roles.id')
            ->where('staffs.school_id', $schoolId)
            ->whereDate('staff_attendances.date', $today)
            ->select('roles.name as role', DB::raw('count(case when staff_attendances.attendance_type_id = 1 then 1 end) as present_staffs'),
                DB::raw('count(case when staff_attendances.attendance_type_id = 2 then 1 end) as absent_staffs'))
            ->groupBy('roles.name')
            ->get();
   
        $roles = $staffAttendanceData->pluck('role');
        $presentStaffs = $staffAttendanceData->pluck('present_staffs');
        $absentStaffs = $staffAttendanceData->pluck('absent_staffs');
   
        return [
            'labels' => $roles,
            'datasets' => [
                [
                    'label' => 'Present Staffs',
                    'data' => $presentStaffs,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.8)',
                    'borderWidth' => 1
                ],
                [
                    'label' => 'Absent Staffs',
                    'data' => $absentStaffs,
                    'backgroundColor' => 'rgba(255, 99, 132, 0.8)',
                    'borderWidth' => 1
                ]
            ]
        ];
    }


    private function formatChartData($data, $labelKey, $dataKey, $label)
    {
        $labels = array_column($data, $labelKey);
        $values = array_column($data, $dataKey);


        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => $label,
                    'data' => $values,
                    'borderWidth' => 1
                ]
            ]
        ];
    }
   
}



