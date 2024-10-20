<?php


namespace App\Http\Controllers\SchoolAdmin;


use Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Classg;
use App\Models\User;
use App\Models\SubjectGroup;
use App\Models\School;
use App\Models\TeacherLog;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Services\FileService;
use App\Http\Services\TeacherLogService;
use App\Http\Requests\TeacherLogRequest;
use App\Rules\UniqueLogBookDate;
use Illuminate\Support\Facades\Storage;


class TeacherLogController extends Controller
{
    protected $fileService;


    protected $studentUserService;


    public function __construct(FileService $fileService, TeacherLogService $teacherLogService)
    {
        $this->fileService = $fileService;
        $this->teacherLogService = $teacherLogService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $page_title = "Teacher's Logs";
    $schoolId = session('school_id');


    // Get the role of the authenticated user
    $user = Auth::user();
    $roles = $user->getRoleNames();


    // Check if the user has the 'Municipality Admin' role
    $isMunicipality = $roles->contains('Municipality Admin');


    $classes = Classg::where('school_id', $schoolId)
        ->orderBy('created_at', 'desc')
        ->get();
   
    $subject_groups = SubjectGroup::where('school_id', $schoolId)
        ->orderBy('created_at', 'desc')
        ->get();
   
    $schools = School::all();


    // Correctly use the role scope on the query builder
    $teachers = User::query()->role('Teacher')->get();


    return view('backend.school_admin.logs.teachers_logs.index', compact('classes', 'subject_groups', 'page_title', 'schools', 'isMunicipality', 'teachers'));
}






    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schoolId = session('school_id');


        $classes = Classg::where('school_id', $schoolId)
            ->orderBy('created_at', 'desc')
            ->get();


        $subject_groups = SubjectGroup::where('school_id', $schoolId)
            ->orderBy('created_at', 'desc')
            ->get();


        $page_title = "Create Teacher's Logs";


        return view('backend.school_admin.logs.teachers_logs.create', compact('classes', 'subject_groups', 'page_title'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Initial validation rules
        $rules = [
            'class_id' => 'required|numeric|exists:classes,id',
            'sections' => 'required|array',
            'sections.*' => 'exists:sections,id',
            'subject_group_id' => 'required|numeric|exists:subject_groups,id',
            'subject_id' => 'required|numeric|exists:subjects,id',
            'lesson_id' => 'required|numeric|exists:lessons,id',
            'topic_id' => 'required|numeric|exists:topics,id',
        ];


        $validator = Validator::make($request->all(), $rules);


        // Adding an "after" validation hook
        $validator->after(function ($validator) use ($request) {
            if ($validator->failed())
                return;
            // dd($request->all());
            // Assuming UniqueLogBookDate is a custom validation rule or you have a custom validation logic here
            $uniqueLogBookDateValidator = Validator::make($request->all(), [
                'log_book_date' => [
                    new UniqueLogBookDate(
                        $request->input('class_id'),
                        $request->input('sections'),
                        $request->input('subject_group_id'),
                        $request->input('subject_id'),
                        $request->input('lesson_id'),
                        $request->input('topic_id'),
                        $request->input('log_book_date'),
                    )
                ],
            ]);
            // dd($uniqueLogBookDateValidator);
            // Check if the custom validation for log_book_date fails
            if ($uniqueLogBookDateValidator->fails()) {
                // Assuming you want to add errors for the log_book_date field
                $validator->errors()->add(
                    'log_book_date',
                    'The log_book_date must be unique for the specified conditions.'
                );
            }
        });


        if ($validator->fails()) {
            return back()->withToastError($validator->messages()->all())->withInput();
        }


        try {
            $data = $request->only(['subject_group_id', 'subject_id', 'class_id', 'lesson_id', 'topic_id', 'classwork', 'homework', 'log_book_date']);
            $data['academic_session_id'] = session('academic_session_id');
            $data['school_id'] = session('school_id');
            $data['teacher_log_id'] = Auth::id();
            if ($request->hasFile('file') && !is_null($request->file)) {
                $folder = 'teacher_log';
                $data['file'] = $this->fileService->saveFile($request->file('file'), $folder);
            }
            foreach ($request->input('sections') as $section) {
                $data['section_id'] = $section;
                $savedData = TeacherLog::Create($data);
            }
            return redirect()->back()->withToastSuccess('Your Log Report Saved Successfully!');
        } catch (\Exception $e) {
            return back()->withToastError($e->getMessage());
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function getAllTeacherLogs(Request $request)
    {
        $school_id = session('school_id');
       
        // Ensure you retrieve the logs only for the current school
        $teacherLogs = $this->teacherLogService->getTeacherLogsForDataTable($request->all(), $school_id);
   
        // Check if the teacherLogs are retrieved correctly
        if ($teacherLogs->isEmpty()) {
            return response()->json(['data' => []]); // Return empty array if no logs found
        }
   
        return Datatables::of($teacherLogs)
            ->escapeColumns([])
            ->addColumn('class', function ($teacherLogs) {
                return $teacherLogs->classes ? $teacherLogs->classes->class : 'Undefined';
            })
            ->addColumn('section', function ($teacherLogs) {
                return $teacherLogs->sections ? $teacherLogs->sections->section_name : 'Undefined';
            })
            ->addColumn('subjectgroup', function ($teacherLogs) {
                return $teacherLogs->subjectGroups ? $teacherLogs->subjectGroups->subject_group_name : 'Undefined';
            })
            ->addColumn('subject', function ($teacherLogs) {
                return $teacherLogs->subjects ? $teacherLogs->subjects->subject : 'Undefined';
            })
            ->addColumn('lesson', function ($teacherLogs) {
                return $teacherLogs->lessons ? $teacherLogs->lessons->name : 'Undefined';
            })
            ->addColumn('topic', function ($teacherLogs) {
                return $teacherLogs->topics ? $teacherLogs->topics->topic_name : 'Undefined';
            })
            ->addColumn('file', function ($teacherLogs) {
                return $teacherLogs->file ? '<a href="' . asset('storage/teacher_log/' . $teacherLogs->file) . '" target="_blank" class="btn btn-primary"><i class="fas fa-file"></i> View</a>' : '';
            })
            ->addColumn('teacher', function ($teacherLogs) {
                return $teacherLogs->teacher_id ? $teacherLogs->f_name . ' ' . $teacherLogs->l_name : 'Undefined';
            })
            ->addColumn('actions', function ($teacherLogs) {
                return view('backend.school_admin.logs.teachers_logs.partials.controller_action', ['teacherlog' => $teacherLogs])->render();
            })
            ->addColumn('created_at', function ($teacherLogs) {
                return $teacherLogs->created_at->diffForHumans();
            })
            ->make(true);
    }
   


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $teacherlogs = TeacherLog::findOrFail($id);
        $page_title = "Edit Teacher Log";
        $classes = Classg::all();
        return view('backend.school_admin.logs.teachers_logs.edit', compact('teacherlogs', 'classes', 'page_title'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Initial validation rules
        $rules = [
            'class_id' => 'required|numeric|exists:classes,id',
            'sections' => 'required|array',
            'sections.*' => 'exists:sections,id',
            'subject_group_id' => 'required|numeric|exists:subject_groups,id',
            'subject_id' => 'required|numeric|exists:subjects,id',
            'lesson_id' => 'required|numeric|exists:lessons,id',
            'topic_id' => 'required|numeric|exists:topics,id',
        ];


        $validator = Validator::make($request->all(), $rules);


        // Adding an "after" validation hook
        $validator->after(function ($validator) use ($request, $id) {
            if ($validator->failed())
                return;
            // dd($request->all());
            // Assuming UniqueLogBookDate is a custom validation rule or you have a custom validation logic here
            $uniqueLogBookDateValidator = Validator::make($request->all(), [
                'log_book_date' => [
                    new UniqueLogBookDate(
                        $request->input('class_id'),
                        $request->input('sections'),
                        $request->input('subject_group_id'),
                        $request->input('subject_id'),
                        $request->input('lesson_id'),
                        $request->input('topic_id'),
                        $request->input('log_book_date'),
                        $id,
                    )
                ],
            ]);
            // Check if the custom validation for log_book_date fails
            if ($uniqueLogBookDateValidator->fails()) {
                return back()->withToastError($uniqueLogBookDateValidator->messages()->all()[0])->withInput();
            }
        });


        if ($validator->fails()) {
            return back()->withToastError($validator->messages()->all())->withInput();
        }


        try {
            $teacherLog = TeacherLog::findOrFail($id);


            $data = $request->except('_token', '_method', 'sections');
            if ($request->hasFile('file') && !is_null($request->file)) {
                $folder = 'teacher_log';
                $data['file'] = $this->fileService->saveFile($request->file('file'), $folder);
                // Delete the old file if it exists
                if ($teacherLog->file) {
                    Storage::delete('public/teacher_log/' . $teacherLog->file);
                }
            }
            foreach ($request->input('sections') as $section) {
                $data['section_id'] = $section;
                $savedData = TeacherLog::updateOrCreate(['id' => $id], $data);
            }
            return redirect()->back()->withToastSuccess('Your Log Report Updated Successfully!');
        } catch (\Exception $e) {
            return back()->withToastError($e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $teacherlog = TeacherLog::find($id);
        try {
            $updateNow = $teacherlog->delete();
            return redirect()->back()->withToastSuccess('Teacher log has been Successfully Deleted!');
        } catch (\Exception $e) {
            return back()->withToastError($e->getMessage());
        }


        return back()->withToastError('Something went wrong. please try again');
    }
}

