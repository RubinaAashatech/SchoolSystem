<?php

namespace App\Http\Controllers\Shared;

use App\Models\Classg;
use App\Models\Student;
use App\Models\ExamResult;
use App\Models\StudentSession;
use App\Models\StudentAttendance;
use App\Models\EcaParticipation;
use App\Models\EcaResult;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\FormService;
use App\Http\Services\StudentUserService;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Log;

class StudentProfileController extends Controller
{
    protected $formService;
    protected $studentUserService;

    public function __construct(FormService $formService, StudentUserService $studentUserService)
    {
        $this->formService = $formService;
        $this->studentUserService = $studentUserService;
    }

    public function index(Request $request)
    {
        $schoolId = session('school_id');
        $perPage = 50;
        $currentPage = $request->input('page', 1);

        $students = Student::with(['user'])
            ->where('school_id', $schoolId)
            ->latest()
            ->get();

        $total = $students->count();
        $lastPage = ceil($total / $perPage);

        $students = $students->forPage($currentPage, $perPage);
        
        return view('backend.shared.student_profile.index', compact('students', 'currentPage', 'lastPage', 'total', 'perPage'));
    }
    
    public function profileSearch(Request $request)
    {
        $schoolId = session('school_id');
        $perPage = 50; 
        $currentPage = $request->input('page', 1);

        $query = Student::query()->with(['user'])
            ->where('school_id', $schoolId);

        if ($request->filled('search_term')) {
            $searchTerm = $request->input('search_term');
            $query->whereHas('user', function ($q) use ($searchTerm) {
                $q->whereRaw("CONCAT(f_name, ' ', l_name) LIKE ?", ["%{$searchTerm}%"])
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });            
        }

        if ($request->filled('dob')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('dob', $request->input('dob'));
            });
        }

        $students = $query->get();
        $total = $students->count();
        $lastPage = ceil($total / $perPage);

        if ($request->filled('search_term')) {
            $perPage = $total;
            $currentPage = 1;
            $lastPage = 1;
        } else {
            $students = $students->forPage($currentPage, $perPage);
        }

        $classes = Classg::where('school_id', $schoolId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('backend.shared.student_profile.index', compact('students', 'classes', 'currentPage', 'lastPage', 'total', 'perPage'));
    }


    public function profileShow($id)
    {
        $schoolId = session('school_id');
        // Fetch Student data
        $student = Student::with(['user'])->where('school_id', $schoolId)->findOrFail($id);
        $studentSession = StudentSession::where('user_id', $student->user_id)
            ->where('school_id', $schoolId)
            ->where('is_active', 1) 
            ->firstOrFail();
    
        // Fetch exam data
        $examResults = ExamResult::where('student_session_id', $studentSession->id)
            ->with(['studentSession.classg', 'studentSession.section', 'examStudent', 'examSchedule.examination', 'subject'])
            ->get();
        $groupedResults = $examResults->groupBy(function ($result) {
            return $result->examSchedule->examination->exam_type ?? 'Unknown Term';
        });
    
        $terminalResults = $groupedResults->get('terminal', collect());
        $finalResults = $groupedResults->get('final', collect());
    
        $processedTerminalResults = $this->processTerminalExam($terminalResults);
        $processedFinalResults = $this->processFinalExam($finalResults, $terminalResults);
    
        $terminalCGPA = $processedTerminalResults['gpa'] ?? 0;
        $finalCGPA = $processedFinalResults['overallGPA'] ?? 0;
    
        $class = $studentSession->classg->class ?? 'N/A'; 
        $section = $studentSession->section->section_name ?? 'N/A';
    
        $terminalExamName = $terminalResults->isNotEmpty() 
            ? $terminalResults->first()->examSchedule->examination->exam 
            : 'No Terminal Exam';
    
        $finalExamName = $finalResults->isNotEmpty() 
            ? $finalResults->first()->examSchedule->examination->exam 
            : 'No Final Exam';
    
        // Fetch attendance data
        $attendanceData = StudentAttendance::where('student_session_id', $studentSession->id)
            ->with('attendenceType')
            ->get()
            ->groupBy(function ($attendance) {
                return date('Y', strtotime($attendance->date));
            })
            ->map(function ($yearAttendance) {
                $totalDays = $yearAttendance->count();
                $presentDays = $yearAttendance->where('attendance_type_id', 1)->count(); 
                $absentDays = $totalDays - $presentDays;
                $attendancePercentage = $totalDays > 0 ? ($presentDays / $totalDays) * 100 : 0;
    
                return [
                    'total_days' => $totalDays,
                    'present_days' => $presentDays,
                    'absent_days' => $absentDays,
                    'attendance_percentage' => round($attendancePercentage, 2)
                ];
            });

            // Fetch ECA participation data
            $ecaParticipations = EcaParticipation::where('school_id', $schoolId)
            ->with('ecaActivity')
            ->whereRaw("JSON_CONTAINS(participant_name, '\"{$studentSession->id}\"')")
            ->get();

            // Prepare the results
            $awards = $ecaParticipations->flatMap(function ($participation) {
                if ($participation->ecaResults) {
                    return $participation->ecaResults->map(function ($result) {
                        return [
                            'result_type' => $result->result_type,
                            'description' => $result->description,
                        ];
                    });
                }
                return collect();
            });

        return view('backend.shared.student_profile.show', compact(
            'student', 
            'processedTerminalResults', 
            'processedFinalResults', 
            'terminalCGPA', 
            'finalCGPA',
            'class',
            'section',
            'terminalExamName',
            'finalExamName',
            'attendanceData',
            'ecaParticipations',
            'awards'
        ));
    }
    
    
    private function processExamResults($examResults, $examType, $studentSession, $examination)
    {
        return $examType === 'terminal' 
            ? $this->processTerminalExam($examResults) 
            : $this->processFinalExam($studentSession, $examination);
    }    
    
    private function processTerminalExam($examResults)
    {
        $processedResults = $examResults->map(function ($result) {
            $participantAssessment = $result->participant_assessment ?? 0;
            $practicalAssessment = $result->practical_assessment ?? 0;
            $theoryAssessment = $result->theory_assessment ?? 0;

            $convertedTheoryAssessment = ($theoryAssessment / 50) * 10;
    
            $totalMarks = $participantAssessment + $practicalAssessment + $convertedTheoryAssessment;
            $allZero = ($participantAssessment == 0 && $practicalAssessment == 0 && $theoryAssessment == 0);
            
            $gradeInfo = $this->getGradeInfo($totalMarks, $allZero);
            $subjectGPA = $allZero ? 0 : $this->getSubjectGPA($totalMarks);
    
            return [
                'subject_id' => $result->subject->id ?? $result->subject_id,
                'subject_name' => $result->subject->subject ?? 'Unknown Subject',
                'credit_hour' => $result->subject->credit_hour ?? 'Unknown Credit Hour',
                'participant_assessment' => $participantAssessment,
                'practical_assessment' => $practicalAssessment,
                'theory_assessment' => $theoryAssessment,
                'converted_theory' => $convertedTheoryAssessment,
                'total' => $totalMarks,
                'grade' => $gradeInfo,
                'grade_point' => $subjectGPA,
                'course_type' => 'theory',
            ];
        });
    
        $gpa = $this->calculateGPA($processedResults);
    
        return [
            'examResults' => $processedResults,
            'gpa' => $gpa,
            'className' => $examResults->first()->studentSession->classg->class ?? 'Unknown Class',
            'sectionName' => $examResults->first()->studentSession->section->section_name ?? 'Unknown Section',
            'subjectNames' => $processedResults->pluck('subject_name')->unique()->toArray(),
        ];
    }
    
    private function processFinalExam($finalExamResults, $terminalExamResults)
    {
        $processedResults = [];
        $totalCreditHours = 0;
        $totalGradePoints = 0;
    
        foreach ($finalExamResults as $result) {
            $subjectId = $result->subject_id;
            $terminalResult = $terminalExamResults->firstWhere('subject_id', $subjectId);
    
            $creditHours = $result->subject->credit_hour ?? 1;
            $creditHours = is_numeric($creditHours) ? (float)$creditHours : 1;

            $internalMarks = $this->calculateInternalMarks($result, $terminalResult);

            $theoryMarks = $result->theory_assessment * 0.5;
    
            $totalMarks = $internalMarks + $theoryMarks;

            $allZero = ($result->participant_assessment == 0 &&
                        $result->practical_assessment == 0 &&
                        $result->theory_assessment == 0 &&
                        ($terminalResult ? $terminalResult->theory_assessment == 0 : true));

            $gradeInfo = $this->getGradeInfoFinal($totalMarks, $allZero);
            $subjectGPA = $allZero ? 0 : $this->calculateGPAFinal($totalMarks);
    
            $totalCreditHours += $creditHours;
            $totalGradePoints += $subjectGPA * $creditHours;
    
            $processedResults[$subjectId] = [
                'subject_name' => $result->subject->subject ?? 'Unknown Subject',
                'credit_hour' => $creditHours,
                'internal_marks' => number_format($internalMarks, 2),
                'theory_marks' => number_format($theoryMarks, 2),
                'total_marks' => number_format($totalMarks, 2),
                'gpa' => number_format($subjectGPA, 2),
                'grade' => $gradeInfo,
                'all_zero' => $allZero,
            ];
        }

        $overallGPA = $totalCreditHours > 0 ? number_format($totalGradePoints / $totalCreditHours, 2) : 0;
    
        return [
            'examResults' => $processedResults,
            'overallGPA' => $overallGPA,
            'className' => $finalExamResults->first()->studentSession->classg->class ?? 'Unknown Class',
            'sectionName' => $finalExamResults->first()->studentSession->section->section_name ?? 'Unknown Section',
            'subjectNames' => array_keys($processedResults),
        ];
    }
    
    private function calculateInternalMarks($finalResult, $terminalResult)
    {
        $firstTermTotal = $terminalResult ? $terminalResult->theory_assessment * 0.05 : 0;
        $secondTermTotal = $terminalResult ? $terminalResult->theory_assessment * 0.05 : 0;
        return $firstTermTotal + $secondTermTotal +
               $finalResult->participant_assessment +
               $finalResult->practical_assessment;
    } 
    
    private function getGradeInfo($totalMarks, $allZero = false)
    {
        if ($allZero) {
            return [
                'grade_points_to' => 0,
                'grade_name' => 'NG',
                'achievement_description' => 'Not Attempted',
            ];
        }
        $gradePoint = $this->getSubjectGPA($totalMarks);
    
        $gradeName = 'NG'; 
        $achievementDescription = 'Not Graded'; 
    
        if ($totalMarks > 45) {
            $gradeName = 'A';
            $achievementDescription = 'Excellent';
        } elseif ($totalMarks > 40) {
            $gradeName = 'B';
            $achievementDescription = 'Very Good';
        } elseif ($totalMarks > 35) {
            $gradeName = 'C';
            $achievementDescription = 'Good';
        } elseif ($totalMarks > 30) {
            $gradeName = 'D';
            $achievementDescription = 'Satisfactory';
        } elseif ($totalMarks > 25) {
            $gradeName = 'E';
            $achievementDescription = 'Pass';
        } elseif ($totalMarks > 20) {
            $gradeName = 'F';
            $achievementDescription = 'Below Average';
        } elseif ($totalMarks > 18) {
            $gradeName = 'G';
            $achievementDescription = 'Poor';
        }
    
        return [
            'grade_points_to' => $gradePoint,
            'grade_name' => $gradeName,
            'achievement_description' => $achievementDescription,
        ];
    }
    
    private function getSubjectGPA($totalMarks)
    {
        if ($totalMarks > 45) {
            $gpa = 4.0;
        } elseif ($totalMarks > 40) {
            $gpa = 3.6;
        } elseif ($totalMarks > 35) {
            $gpa = 3.2;
        } elseif ($totalMarks > 30) {
            $gpa = 2.8;
        } elseif ($totalMarks > 25) {
            $gpa = 2.4;
        } elseif ($totalMarks > 20) {
            $gpa = 2.0;
        } elseif ($totalMarks > 18) {
            $gpa = 1.6;
        } else {
            $gpa = 0.0;
        }
    
        return $gpa;
    }
    private function calculateGPA($processedResults)
    {
        $totalCreditHours = 0;
        $totalWeightedGradePoints = 0;
        
        foreach ($processedResults as $result) {
            $creditHours = $result['credit_hour'] ?? 1;
            $creditHours = is_numeric($creditHours) ? (float)$creditHours : 1;
            
            $gradePoint = $result['grade_point'] ?? 0;
            
            $totalCreditHours += $creditHours;
            $totalWeightedGradePoints += $gradePoint * $creditHours;
        }
        
        return $totalCreditHours > 0 ? round($totalWeightedGradePoints / $totalCreditHours, 2) : 0;
    }

    private function getSubjectGPAFinal($totalMarks)
{
    if ($totalMarks > 90) return 4.0;
            elseif ($totalMarks > 80) return 3.6;
            elseif ($totalMarks > 70) return 3.2;
            elseif ($totalMarks > 60) return 2.8;
            elseif ($totalMarks > 50) return 2.4;
            elseif ($totalMarks > 40) return 2.0;
            elseif ($totalMarks > 32) return 1.6;
            else return 0.0;
}
private function getGradeInfoFinal($totalMarks, $allZero = false)
{
    if ($allZero) {
        return [
            'grade_name' => 'NG',
            'achievement_description' => 'Not Attempted',
        ];
    }

    if ($totalMarks > 90) {
        return ['grade_name' => 'A+', 'achievement_description' => 'Outstanding'];
    } elseif ($totalMarks > 80) {
        return ['grade_name' => 'A', 'achievement_description' => 'Excellent'];
    } elseif ($totalMarks > 70) {
        return ['grade_name' => 'B+', 'achievement_description' => 'Very Good'];
    } elseif ($totalMarks > 60) {
        return ['grade_name' => 'B', 'achievement_description' => 'Good'];
    } elseif ($totalMarks > 50) {
        return ['grade_name' => 'C+', 'achievement_description' => 'Satisfactory'];
    } elseif ($totalMarks > 40) {
        return ['grade_name' => 'C', 'achievement_description' => 'Acceptable'];
    } elseif ($totalMarks > 32) {
        return ['grade_name' => 'D', 'achievement_description' => 'Partially Acceptable'];
    } else {
        return ['grade_name' => 'NG', 'achievement_description' => 'Not Graded'];
    }
}

private function calculateGPAFinal($totalMarks)
    {
        if ($totalMarks > 90) return 4.0;
        elseif ($totalMarks > 80) return 3.6;
        elseif ($totalMarks > 70) return 3.2;
        elseif ($totalMarks > 60) return 2.8;
        elseif ($totalMarks > 50) return 2.4;
        elseif ($totalMarks > 40) return 2.0;
        elseif ($totalMarks > 32) return 1.6;
        else return 0.0;
    }
}