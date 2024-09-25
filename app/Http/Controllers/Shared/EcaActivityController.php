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
use App\Models\EcaResult;
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
    
    public function index(Request $request)
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
    Log::info('Received player_type: ' . $request->input('player_type'));
    $user_type = $this->getUserType();
    
    $validationRules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'player_type' => 'required|in:single,multi,competitive',
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

    // Add the created_by field with the current user's ID
    $data['created_by'] = Auth::id();

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
            'player_type' => 'required|in:single,multi,competitive',
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
    try {
        if ($request->ajax()) {
            $user = auth()->user();
            $school_id = $user->school_id;
            $user_type = $this->getUserType();

            if ($user_type === 'municipality') {
                $data = EcaActivity::with('ecaHead')
                    ->where('created_by', auth()->id())
                    ->get();
            } else {
                $data = EcaActivity::with('ecaHead')
                    ->where(function($query) use ($school_id) {
                        $query->whereHas('schools', function($q) use ($school_id) {
                            $q->where('schools.id', $school_id);
                        })
                        ->orWhere('created_by', auth()->id());
                    })
                    ->get();
            }

            return Datatables::of($data)
                ->addColumn('actions', function($row) use ($school_id, $user_type) {
                    $btn = '';

                    if ($row->created_by == auth()->id()) {
                        $btn .= '<a href="javascript:void(0)" class="edit-eca-activity btn btn-warning btn-sm" data-id="'.$row->id.'" data-title="'.$row->title.'" data-description="'.$row->description.'" data-player_type="'.$row->player_type.'" data-is_active="'.$row->is_active.'" data-eca_head_id="'.$row->eca_head_id.'">Edit</a>';
                    }

                    $alreadyParticipated = DB::table('eca_participations')
                        ->where('eca_activity_id', $row->id)
                        ->where('school_id', $school_id)
                        ->exists();
                    
                        $participationExists = DB::table('eca_participations')
                        ->where('eca_activity_id', $row->id)
                        ->exists();

                    if ($user_type !== 'municipality') {
                        if ($alreadyParticipated) {
                            $btn .= ' <button class="btn btn-info btn-sm" disabled style="margin-left: 5px;">Participated</button>';
                        } else {
                            $btn .= ' <a href="javascript:void(0)" class="participate-eca-activity btn btn-info btn-sm" style="margin-left: 5px;" data-id="'.$row->id.'">Participate</a>';
                        }
                    }
                    if ($user_type === 'municipality' && $participationExists) {
                        $school_id = DB::table('eca_participations')
                            ->where('eca_activity_id', $row->id)
                            ->value('school_id'); 

                        if (is_null($school_id)) {
                            Log::warning('School ID is null. Cannot fetch participation ID.');
                            return; 
                        }

                        $participationId = DB::table('eca_participations')
                            ->where('eca_activity_id', $row->id)
                            ->where('school_id', $school_id)
                            ->value('id');

                        $participations = DB::table('eca_participations')
                            ->join('schools', 'eca_participations.school_id', '=', 'schools.id')
                            ->select(
                                'schools.id as school_id',
                                'schools.name as school_name',
                                'eca_participations.participant_name',
                                'eca_participations.id as participation_id'
                            )
                            ->where('eca_participations.eca_activity_id', $row->id)
                            ->when($school_id, function($query) use ($school_id) {
                                return $query->where('eca_participations.school_id', $school_id);
                            })
                            ->get();

                        $schools = $participations->pluck('school_name', 'school_id')->toArray();

                        $participantNames = $participations->groupBy('school_id')->map(function ($group) {
                            return $group->map(function($participation) {
                                $ids = json_decode($participation->participant_name, true);
                                return DB::table('users')
                                    ->whereIn('id', $ids)
                                    ->pluck('f_name')
                                    ->toArray();
                            })->toArray();
                        })->toArray();

                        $schoolOptions = '';
                        foreach ($schools as $schoolId => $schoolName) {
                            $schoolOptions .= '<option value="'.$schoolId.'">'.$schoolName.'</option>';
                        }
                        $btn .= ' <a href="javascript:void(0)" class="result-eca-activity btn btn-success btn-sm" 
                                    data-id="'.$row->id.'" 
                                    data-participation-id="'.$participationId.'"
                                    data-activity-title="'.$row->title.'" 
                                    data-player-type="'.$row->player_type.'" 
                                    data-schools=\''.json_encode($schools).'\' 
                                    data-participants=\''.json_encode($participantNames).'\' 
                                    data-toggle="modal" 
                                    data-target="#resultModal">View Result</a>';
                    }
                    
                    if ($row->created_by == auth()->id()) {
                        $btn .= '<span style="margin-left: 5px;">';
                        $btn .= '<form action="'.route('admin.eca_activities.destroy', $row->id).'" method="POST" style="display:inline-block;">';
                        $btn .= csrf_field();
                        $btn .= method_field('DELETE');
                        $btn .= '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')">Delete</button>';
                        $btn .= '</form>';
                        $btn .= '</span>';
                    }

                    return $btn;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    } catch (\Exception $e) {
        Log::error('Error in getEcaActivities: ' . $e->getMessage());
        return response()->json(['error' => 'An error occurred while fetching data.'], 500);
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

    public function getClassesForActivity(Request $request)
{
    $activityId = $request->input('activity_id');
    $classes = Classg::whereHas('activities', function($query) use ($activityId) {
        $query->where('activity_id', $activityId);
    })->get()->map(function($class) {
        return [
            'id' => $class->id,
            'class_name' => $class->name,
            'selected' => $class->isSelectedForActivity(),
        ];
    });

    return response()->json(['classes' => $classes]);
}

public function getSchoolsForActivity(Request $request)
{
    $activityId = $request->input('activity_id');
    $schools = School::whereHas('activities', function($query) use ($activityId) {
        $query->where('activity_id', $activityId);
    })->get()->map(function($school) {
        return [
            'id' => $school->id,
            'school_name' => $school->name,
            'selected' => $school->isSelectedForActivity(), 
        ];
    });

    return response()->json(['schools' => $schools]);
}
public function storeEcaResult(Request $request)
{
    $request->validate([
        'eca_participation_id' => 'required|exists:eca_participations,id',
        'result_type' => 'required|in:first,second,third',
        'description' => 'nullable|string',
        'is_publish' => 'boolean',
    ]);

    try {
        $ecaResult = EcaResult::create($request->all());
       
        return redirect()->route('admin.eca_activities.index')->with('success', 'ECA Result created successfully.');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'Failed to create ECA Result']);
    }
}


}
