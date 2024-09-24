<?php
namespace App\Http\Controllers\Shared;

use App\Models\EcaActivity;
use App\Models\ExtraCurricularHead;
use App\Models\EcaParticipation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\School;
use Yajra\Datatables\Datatables;
use App\Models\Classg; 
use App\Models\Section; 
use App\Models\Student; 
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class EcaActivityController extends Controller
{
    private function getUserType()
    {
        $user = Auth::user();
        if ($user->school_id) {
            return 'school_admin';
        }
        return $user->type ?? 'municipality';
    }
    public function index()
{
    $page_title = 'ECA Activities';
    $ecaHeads = ExtraCurricularHead::where('is_active', 1)->get();
    $user_type = $this->getUserType();
    
    
    $schools = collect();
    $classes = collect();
    
    if ($user_type === 'municipality') {
        $schools = School::all();
    } elseif ($user_type === 'school_admin') {
        $school_id = Auth::user()->school_id;
        $classes = Classg::where('school_id', $school_id)->get();
    }
    
    $sections = Section::all();
    $students= Student::all();

    return view('backend.shared.extraactivities.index', compact('page_title', 'ecaHeads', 'schools', 'classes', 'sections', 'user_type', 'students'));
}

    public function store(Request $request)
    {
        $user_type = $this->getUserType();
        
        $validationRules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'player_type' => 'required|in:single,multi',
            'is_active' => 'required|boolean',
            'eca_head_id' => 'required|exists:extra_curricular_heads,id',
            'pdf_image' => 'nullable|mimes:pdf,jpeg,png,jpg|max:2048',
        ];

        if ($user_type === 'municipality') {
            $validationRules['school_ids'] = 'required|array';
            $validationRules['school_ids.*'] = 'exists:schools,id';
        } elseif ($user_type === 'school_admin') {
            $validationRules['class_ids'] = 'required|array';
            $validationRules['class_ids.*'] = 'exists:classes,id';
        }

        $request->validate($validationRules);

        $data = $request->all();
        if ($request->hasFile('pdf_image')) {
            $data['pdf_image'] = $request->file('pdf_image')->store('pdf_images');
        }

        $ecaActivity = EcaActivity::create($data);

        if ($user_type === 'municipality') {
            $ecaActivity->schools()->sync($request->school_ids);
        } elseif ($user_type === 'school_admin') {
            $ecaActivity->schools()->sync([Auth::user()->school_id]);
            $ecaActivity->classes()->sync($request->class_ids);
        }

        return redirect()->route('admin.eca_activities.index')->with('success', 'ECA Activity created successfully.');
    }

    public function update(Request $request, EcaActivity $ecaActivity)
    {
        $user_type = $this->getUserType();
        
        $validationRules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'player_type' => 'required|in:single,multi',
            'is_active' => 'required|boolean',
            'eca_head_id' => 'required|exists:extra_curricular_heads,id',
            'pdf_image' => 'nullable|mimes:pdf,jpeg,png,jpg|max:2048',
        ];

        if ($user_type === 'municipality') {
            $validationRules['school_ids'] = 'required|array';
            $validationRules['school_ids.*'] = 'exists:schools,id';
        } elseif ($user_type === 'school_admin') {
            $validationRules['class_ids'] = 'required|array';
            $validationRules['class_ids.*'] = 'exists:classes,id';
        }

        $request->validate($validationRules);

        $data = $request->all();
        if ($request->hasFile('pdf_image')) {
            $data['pdf_image'] = $request->file('pdf_image')->store('pdf_images');
        }

        $ecaActivity->update($data);

        if ($user_type === 'municipality') {
            $ecaActivity->schools()->sync($request->school_ids);
        } elseif ($user_type === 'school_admin') {
            $ecaActivity->schools()->sync([Auth::user()->school_id]);
            $ecaActivity->classes()->sync($request->class_ids);
        }

        return redirect()->route('admin.eca_activities.index')->with('success', 'ECA Activity updated successfully.');
    }

    public function getEcaActivities(Request $request)
{
    if ($request->ajax()) {
        // Assuming the current school_id is stored in session or can be retrieved from authenticated user
        $school_id = auth()->user()->school_id;

        $data = EcaActivity::with('ecaHead')->get();

        return Datatables::of($data)
            ->addColumn('actions', function($row) use ($school_id) {
                // Check if the school has already participated in this ECA activity
                $alreadyParticipated = DB::table('eca_activity_school')
                    ->where('eca_activity_id', $row->id)
                    ->where('school_id', $school_id)
                    ->exists();

                // Create buttons
                $btn = '<a href="javascript:void(0)" class="edit-eca-activity btn btn-warning btn-sm" data-id="'.$row->id.'" data-title="'.$row->title.'" data-description="'.$row->description.'" data-player_type="'.$row->player_type.'" data-is_active="'.$row->is_active.'" data-eca_head_id="'.$row->eca_head_id.'">Edit</a>';

                if ($alreadyParticipated) {
                    // Disable participate button if already participated
                    $btn .= ' <button class="btn btn-info btn-sm" disabled>Participated</button>';
                } else {
                    $btn .= ' <a href="javascript:void(0)" class="participate-eca-activity btn btn-info btn-sm" data-id="'.$row->id.'">Participate</a>';
                }

                // Delete button
                $btn .= ' <form action="'.route('admin.eca_activities.destroy', $row->id).'" method="POST" style="display:inline-block;">';
                $btn .= csrf_field();
                $btn .= method_field('DELETE');
                $btn .= ' <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')">Delete</button>';
                $btn .= '</form>';

                return $btn;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}


        public function getClasses(Request $request)
    {
        $schoolId = $request->input('school_id');
        $classes = Classg::where('school_id', $schoolId)->get();
        return response()->json($classes);
    }

    public function getSections(Request $request)
    {
        $classId = $request->input('class_id');
        $schoolId = $request->input('school_id'); 

        $sections = Section::whereHas('classes', function ($query) use ($classId, $schoolId) {
            $query->where('class_sections.class_id', $classId)
                ->where('class_sections.school_id', $schoolId);
        })->get();

        return response()->json($sections);
    }

    public function getStudents(Request $request)
    {
        $schoolId = $request->input('school_id');
        $classId = $request->input('class_id');
        $sectionId = $request->input('section_id');
    
        Log::info("Fetching students for school: $schoolId, class: $classId, section: $sectionId");
    
        $students = Student::where('students.school_id', $schoolId) 
            ->where('students.class_id', $classId)
            ->where('students.section_id', $sectionId)
            ->join('users', 'students.user_id', '=', 'users.id') 
            ->get([
                'students.id', 
                'users.f_name', 
                'users.m_name', 
                'users.l_name'
            ]);
    
        Log::info("Students fetched:", $students->toArray());
    
        return response()->json($students);
    }


    public function storeParticipation(Request $request)
    {
        Log::info('Request data:', $request->all());

        $request->validate([
            'eca_activity_id' => 'required|exists:eca_activities,id',
            'school_id' => 'required|exists:schools,id',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'participant_name' => 'required|array',
            'participant_name.*' => 'required|integer|exists:students,id',
        ]);

        $participantIds = array_filter($request->participant_name, function($value) {
            return $value !== null && $value !== '';
        });

        $participationData = [
            'user_id' => auth()->id(),
            'school_id' => $request->school_id,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'eca_activity_id' => $request->eca_activity_id,
            'participant_name' => $participantIds,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        EcaParticipation::create($participationData);
    
        return redirect()->route('admin.eca_activities.index')
            ->with('success', 'Participation recorded successfully for ' . count($request->participant_name) . ' students.');
    }
    
}
