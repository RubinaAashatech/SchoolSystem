<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\Classg;
use App\Models\Section;
use App\Models\ClassSection;
use App\Models\SubjectGroup;
use App\Models\SubjectTeacher;
use App\Models\User;
use App\Models\Role;
use App\Models\UserType;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SubjectTeacherController extends Controller
{
    public function getTeachers()
    {
        $schoolId = session('school_id');
        $teachers = User::where('role_id', 6)
            ->where('school_id', $schoolId)
            ->select('id', 'f_name', 'l_name')
            ->get()
            ->map(function ($teacher) {
                return [
                    'id' => $teacher->id,
                    'name' => $teacher->f_name . ' ' . $teacher->l_name
                ];
            });
    
        return response()->json($teachers);
    }
    public function assignTeachers(Request $request, string $subjectGroupId, string $classId, string $sectionId)
    {
        $schoolId = session('school_id');
        if (!$schoolId) {
            abort(403, 'School ID not found in session');
        }
    
        $subjectGroup = SubjectGroup::findOrFail($subjectGroupId);
        $page_title = "Assign Teachers To " . $subjectGroup->subject_group_name;
    
        $class = Classg::findOrFail($classId);
        $section = Section::findOrFail($sectionId);
    
        $staffTypeId = UserType::where('title', 'staffs')->value('id');
        $teacherRole = Role::where('name', 'Teacher')->first();
        $teacherRoleId = $teacherRole ? $teacherRole->id : null;
        $teachers = User::where('user_type_id', $staffTypeId)
            ->whereHas('staff', function ($query) use ($teacherRoleId, $schoolId) {
                $query->where('role_id', $teacherRoleId)  
                      ->where('school_id', $schoolId);
            })
            ->select('id', 'f_name', 'l_name')
            ->get()
            ->mapWithKeys(function ($teacher) {
                return [$teacher->id => $teacher->f_name . ' ' . $teacher->l_name];
            });
    
        $subjects = $subjectGroup->subjects;
    
        $assignedTeachers = SubjectTeacher::with(['subject', 'class', 'section', 'user'])
            ->where('subject_group_id', $subjectGroupId)
            ->where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->paginate(10);
    
        return view('backend.school_admin.subject_group.teacher.create', 
            compact('page_title', 'subjectGroup', 'class', 'section', 'teachers', 'subjects', 'assignedTeachers'));
    }
    public function storeAssignTeachers(Request $request)
    {
        try {
            $schoolId = session('school_id');
            if (!$schoolId) {
                return redirect()->back()->with('error', 'School ID not found in session');
            }
    
            $request->validate([
                'subject_group_id' => 'required|exists:subject_groups,id',
                'subject_id' => 'required|exists:subjects,id',
                'class_id' => 'required|exists:classes,id',
                'section_id' => 'required|exists:sections,id',
                'user_id' => 'required|exists:users,id',
            ]);
    
            $class = Classg::where('id', $request->class_id)->where('school_id', $schoolId)->firstOrFail();
            $section = Section::whereHas('classSections', function ($query) use ($schoolId, $request) {
                $query->where('school_id', $schoolId)
                      ->where('class_id', $request->class_id);
            })->findOrFail($request->section_id);
            $teacher = User::where('id', $request->user_id)->where('school_id', $schoolId)->firstOrFail();
    
            SubjectTeacher::updateOrCreate(
                [
                    'subject_group_id' => $request->subject_group_id,
                    'subject_id' => $request->subject_id,
                    'class_id' => $request->class_id,
                    'section_id' => $request->section_id,
                ],
                ['user_id' => $request->user_id]
            );
    
            return redirect()->back()->with('success', 'Teacher assigned successfully');
        } catch (\Exception $e) {
            Log::error('Error storing assigned teachers: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error processing data: ' . $e->getMessage());
        }
    }

    public function getAssignedTeachers(Request $request)
    {
        $subjectTeachers = $this->getForDataTable($request->all());

        return DataTables::of($subjectTeachers)
            ->addColumn('subject', function ($subjectTeacher) {
                return $subjectTeacher->subject->subject;
            })
            ->addColumn('class', function ($subjectTeacher) {
                return $subjectTeacher->class->class;
            })
            ->addColumn('section', function ($subjectTeacher) {
                return $subjectTeacher->section->section_name;
            })
            ->addColumn('teacher', function ($subjectTeacher) {
                return $subjectTeacher->user->f_name . ' ' . $subjectTeacher->user->l_name;
            })
            ->addColumn('actions', function ($subjectTeacher) {
                return view('backend.school_admin.subject_group.teacher.partials.actions', compact('subjectTeacher'))->render();
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function getForDataTable($request)
    {
        $dataTableQuery = SubjectTeacher::with(['subject', 'class', 'section', 'user'])
            ->where(function ($query) use ($request) {
                if (isset($request['subject_group_id'])) {
                    $query->where('subject_group_id', $request['subject_group_id']);
                }
            })
            ->get();

        return $dataTableQuery;
    }

    public function edit($id)
    {
        try {
            $schoolId = session('school_id');
            if (!$schoolId) {
                abort(403, 'School ID not found in session');
            }

            $page_title = 'Edit Subject Teacher';
            $subjectTeacher = SubjectTeacher::findOrFail($id);
            $classes = Classg::where('school_id', $schoolId)->get();
            $teachers = User::where('role_id', 6)
                ->where('school_id', $schoolId)
                ->get()
                ->pluck(DB::raw("CONCAT(f_name, ' ', l_name)"), 'id')
                ->toArray();

            // Fetch sections for the current class
            $sections = Section::whereHas('classSections', function($query) use ($schoolId, $subjectTeacher) {
                $query->where('school_id', $schoolId)
                      ->where('class_id', $subjectTeacher->class_id);
            })->get();

            return view('backend.school_admin.subject_group.teacher.update', compact('subjectTeacher', 'classes', 'sections', 'teachers', 'page_title'));
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Record not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error editing record: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $schoolId = session('school_id');
            if (!$schoolId) {
                return response()->json(['error' => 'School ID not found in session'], 403);
            }

            $request->validate([
                'class_id' => 'required|exists:classes,id',
                'section_id' => 'required|exists:sections,id',
                'user_id' => 'required|exists:users,id',
            ]);

            $subjectTeacher = SubjectTeacher::findOrFail($id);

            $class = Classg::where('id', $request->class_id)->where('school_id', $schoolId)->firstOrFail();
            $section = Section::whereHas('classSections', function($query) use ($schoolId, $request) {
                $query->where('school_id', $schoolId)
                      ->where('class_id', $request->class_id);
            })->findOrFail($request->section_id);
            $teacher = User::where('id', $request->user_id)->where('school_id', $schoolId)->firstOrFail();

            $subjectTeacher->update($request->all());

            return response()->json(['message' => 'Subject teacher updated successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Record not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating record: ' . $e->getMessage()], 500);
        }
    }

    public function deleteAssignTeachers($id)
    {
        try {
            $schoolId = session('school_id');
            if (!$schoolId) {
                return response()->json(['error' => 'School ID not found in session'], 403);
            }

            $subjectTeacher = SubjectTeacher::findOrFail($id);

            if ($subjectTeacher->class->school_id != $schoolId) {
                return response()->json(['error' => 'You do not have permission to delete this record'], 403);
            }

            $subjectTeacher->delete();

            return redirect()->back()->withToastSuccess('Subject Teacher has been Successfully Deleted!');
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->withToastError('Record not found.');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting record: ' . $e->getMessage()], 500);
        }
    }
}