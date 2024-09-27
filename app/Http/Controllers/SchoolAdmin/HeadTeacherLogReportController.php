<?php

namespace App\Http\Controllers\SchoolAdmin;

use DB, Auth;
use App\Models\School;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\HeadTeacherLog;
use App\Models\StudentSession;
use App\Models\StaffAttendance;
use App\Models\Staff;
use App\Models\StudentAttendance;
use App\Models\AttendanceType;
use Anuzpandey\LaravelNepaliDate\LaravelNepaliDate;

use App\Http\Controllers\Controller;

use Carbon\Carbon;

class HeadTeacherLogReportController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $inputDate = $request->input('date', Carbon::today()->format('Y-m-d'));
        $date = LaravelNepaliDate::from($inputDate)->toEnglishDate();

        $teacherLog = HeadTeacherLog::where('school_id', $schoolId)
            ->whereDate('created_at', $date)
            ->select('major_incidents', 'major_work_observation', 'assembly_management', 'miscellaneous')
            ->first();

        $totalStudents = Student::where('school_id', $schoolId)->count();

        $classWiseData = StudentSession::where('school_id', $schoolId)
            ->where('is_active', 1)
            ->with([
                'classg',
                'section',
                'studentAttendances' => function ($query) use ($date) {
                    $query->whereDate('created_at', $date);
                },
                'student.user'
            ])
            ->get()
            ->groupBy(['class_id', 'section_id']);

        $classWiseCounts = [];
        $debugInfo = [];



            // Initialize total present and absent counts for boys and girls
            $totalPresentBoys = 0;
            $totalPresentGirls = 0;
            $totalAbsentBoys = 0;
            $totalAbsentGirls = 0;

        foreach ($classWiseData as $classId => $sections) {
            foreach ($sections as $sectionId => $sessions) {
                $className = $sessions->first()->classg->class ?? 'Unknown Class';
                $sectionName = $sessions->first()->section->section_name ?? 'Unknown Section';
             
                $presentBoys = 0;
                $presentGirls = 0;
                $absentBoys = 0;
                $absentGirls = 0;
                
                $totalBoys = 0;
                $totalGirls = 0;
                $unknownGender = 0;

                $classDebugInfo = [];

                foreach ($sessions as $session) {
                    $gender = $session->student->user->gender ?? 'Unknown'; 
                
                    if ($gender == 'Male') { 
                        $totalBoys++;
                    } elseif ($gender == 'Female') { 
                        $totalGirls++;
                    } else {
                        $unknownGender++;
                    }
                
                    $attendance = $session->studentAttendances->first();
                    $attendanceStatus = 'No attendance';
                
                    if ($attendance) {
                        if ($attendance->attendance_type_id == 1) { // Present
                            $attendanceStatus = 'Present';
                            if ($gender == 'Male') {
                                $presentBoys++;
                                $totalPresentBoys++; 
                            } elseif ($gender == 'Female') {
                                $presentGirls++;
                                $totalPresentGirls++;
                            }
                        } elseif ($attendance->attendance_type_id == 2) { // Absent
                            $attendanceStatus = 'Absent';
                            if ($gender == 'Male') {
                                $absentBoys++;
                                $totalAbsentBoys++;
                            } elseif ($gender == 'Female') {
                                $absentGirls++;
                                $totalAbsentGirls++; 
                            }
                        }
                    }

                    $classDebugInfo[] = [
                        'student_id' => $session->student->id,
                        'gender' => $gender,
                        'attendance_status' => $attendanceStatus,
                    ];
                }

                $classWiseCounts[] = [
                    'class_name' => $className,
                    'section_name' => $sectionName,
                    'present_boys' => $presentBoys,
                    'present_girls' => $presentGirls,
                    'absent_boys' => $absentBoys,
                    'absent_girls' => $absentGirls,
                    'total_boys' => $totalBoys,
                    'total_girls' => $totalGirls,
                    'unknown_gender' => $unknownGender,
                ];

                $debugInfo["$className - $sectionName"] = $classDebugInfo;
            }
        }

        $presentStaffs = StaffAttendance::where('attendance_type_id', 1)
            ->whereHas('staff', function ($query) use ($schoolId) {
                $query->where('school_id', $schoolId);
            })
            ->whereDate('created_at', $date)
            ->count();

        $absentStaffs = StaffAttendance::where('attendance_type_id', 2)
            ->whereHas('staff', function ($query) use ($schoolId) {
                $query->where('school_id', $schoolId);
            })
            ->whereDate('created_at', $date)
            ->count();

        $data = [
            'totalStudents' => $totalStudents,
            'classWiseCounts' => $classWiseCounts,
            'presentStaffs' => $presentStaffs,
            'absentStaffs' => $absentStaffs,
            'majorIncident' => $teacherLog->major_incidents ?? '',
            'majorWorkObservation' => $teacherLog->major_work_observation ?? '',
            'assemblyManagement' => $teacherLog->assembly_management ?? '',
            'miscellaneous' => $teacherLog->miscellaneous ?? '',
            'debugInfo' => $debugInfo,

              // Include total counts in the response
        'totalPresentBoys' => $totalPresentBoys,
        'totalPresentGirls' => $totalPresentGirls,
        'totalAbsentBoys' => $totalAbsentBoys,
        'totalAbsentGirls' => $totalAbsentGirls,
        ];

        if ($request->ajax()) {
            return response()->json($data);
        }

        $page_title = Auth::user()->getRoleNames()[0] . ' ' . "Dashboard";

        return view('backend.school_admin.logs.head_teacher_log_reports.index', array_merge(
            compact('page_title'),
            $data
        ));
    }

    // public function getAttendanceReport(Request $request)
    // {
    //     $date = $request->input('date');
    //     $schoolId = Auth::user()->school_id;

    //     // Retrieve active student sessions for the given date
    //     $activeSessions = StudentSession::where('is_active', 1)
    //         ->get();

    //     // Extract user IDs of students from the active sessions
    //     $studentUserIds = $activeSessions->pluck('user_id')->toArray();

    //     // Fetch students associated with the active sessions
    //     $students = Student::whereIn('user_id', $studentUserIds)->get();


    //     // Fetch student attendance data for the specified date
    //     $studentAttendanceData = StudentAttendance::with('studentSession.student.user')
    //         ->where('date', $date)
    //         ->get();


    //     // Initialize variables to store counts of male and female students
    //     $presentMaleCount = 0;
    //     $presentFemaleCount = 0;
    //     $absentMaleCount = 0;
    //     $absentFemaleCount = 0;

    //     // Iterate through the attendance data to count male and female students
    //     // foreach ($studentAttendanceData as $attendance) {

    //     //     $user = optional($attendance->studentSession)->user;

    //     //     // Check if the attendance type is 'Present'  id = 1 (Present)
    //     //     if ($attendance->attendance_type_id == 1) {


    //     //         // Increment the respective count based on the user's gender
    //     //         if ($user && $user->gender) {
    //     //             if ($user->gender == 'Male') {
    //     //                 $presentMaleCount++;
    //     //             } elseif ($user->gender == 'Female') {
    //     //                 $presentFemaleCount++;
    //     //             }
    //     //         }
    //     //     } elseif ($attendance->attendance_type_id == 2) { // Absent
    //     //         if ($user->gender == 'Male') {
    //     //             $absentMaleCount++;
    //     //         } elseif ($user->gender == 'Female') {
    //     //             $absentFemaleCount++;
    //     //         }
    //     //     }
    //     // }
    //     $presentGirls = StudentAttendance::where('attendance_type_id', 1)
    //     ->whereHas('student.user', function ($query) use ($schoolId) {
    //         $query->where('school_id', $schoolId)->where('gender', 'female');
    //     })
    //     ->whereDate('created_at', today()) // Filter by today's date
    //     ->count();

    //     $presentBoys = StudentAttendance::where('attendance_type_id', 1)
    //     ->whereHas('student.user', function ($query) use ($schoolId) {
    //         $query->where('school_id', $schoolId)->where('gender', 'male');
    //     })
    //     ->whereDate('created_at', today()) // Filter by today's date
    //     ->count();


    //     $totalStaffs = Staff::where('school_id', $schoolId)->count();
    //     // Get the count of present staff
    //     $presentStaffCount = StaffAttendance::where('attendance_type_id', 1)
    //     ->whereHas('staff', function ($query) use ($schoolId) {
    //         $query->where('school_id', $schoolId);
    //     })
    //     ->whereDate('created_at', today()) // Filter by today's date
    //     ->count();
    //     $absentStaffCount = StaffAttendance::where('attendance_type_id', 2)
    //     ->whereHas('staff', function ($query) use ($schoolId) {
    //         $query->where('school_id', $schoolId);
    //     })
    //     ->whereDate('created_at', today()) // Filter by today's date
    //     ->count();


    //     // Fetch data from HeadTeacherLog model where logged_date matches the provided date
    //     $teacherLog = HeadTeacherLog::whereDate('logged_date', $date)->first();

    //     // Check if no data exists for the provided date
    //     if (!$teacherLog && $studentAttendanceData->isEmpty()) {
    //         return response()->json(['message' => 'No data found for this date']);
    //     }

    //     $majorIncident = $teacherLog->major_incidents;
    //     $majorWorkObservation = $teacherLog->major_work_observation;
    //     $assemblyManagement = $teacherLog->assembly_management;
    //     $miscellaneous = $teacherLog->miscellaneous;


    //     // Return data as JSON response
    //     return response()->json([
    //         'students' => $students,
    //         'presentMaleCount' => $presentBoys,
    //         'presentFemaleCount' => $presentGirls,
    //         'absentMaleCount' => $absentMaleCount,
    //         'absentFemaleCount' => $absentFemaleCount,
    //         'presentStaffCount' => $presentStaffCount,
    //         'absentStaffCount' => $absentStaffCount,
    //         'majorIncident' => $majorIncident,
    //         'majorWorkObservation' => $majorWorkObservation,
    //         'assemblyManagement' => $assemblyManagement,
    //         'miscellaneous' => $miscellaneous
    //     ]);
    // }

    // public function getTotalStudents()
    // {
    //     $schoolId = Auth::user()->school_id;
    //     $totalStudents = Student::where('school_id', $schoolId)->count();
    //     return response()->json(['totalStudents' => $totalStudents]);
    // }
}
