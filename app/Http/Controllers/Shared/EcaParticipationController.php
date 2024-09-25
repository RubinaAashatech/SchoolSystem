<?php

namespace App\Http\Controllers\Shared;

use App\Models\EcaParticipation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\School;
use Yajra\Datatables\Datatables;
use App\Models\Classg; 
use App\Models\Section; 
use App\Models\User; 
use Illuminate\Support\Facades\Log;


class EcaParticipationController extends Controller
{

    public function index()
    {
        $schools = School::all();
        $classes = Classg::all(); 
        $sections = Section::all(); 
        return view('backend.shared.extraactivities_participate.index', compact('schools', 'classes', 'sections'));
    }
    public function store(Request $request)
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
            'participant_name' => json_encode($participantIds), 
            'created_at' => now(),
            'updated_at' => now(),
        ];
    
        EcaParticipation::create($participationData);
    
        return redirect()->route('admin.eca_activities.index')
            ->with('success', 'Participation recorded successfully for ' . count($participantIds) . ' students.');
    }

    public function getEcaParticipations(Request $request)
    {
        if ($request->ajax()) {
            $data = EcaParticipation::with(['ecaActivity', 'class', 'section', 'school'])
                ->select('eca_participations.*');
    
            return datatables()->of($data)
                ->addColumn('eca_activity.title', function($row){
                    return $row->ecaActivity->title;
                })
                ->addColumn('participant_name', function($row){
                    $participantIds = json_decode($row->participant_name);
                    $participants = User::whereIn('id', $participantIds)->pluck('f_name')->toArray();
                    return implode(', ', $participants);
                })
                ->addColumn('class.name', function($row){
                    return $row->class->name;
                })
                ->addColumn('section.name', function($row){
                    return $row->section->name;
                })
                ->addColumn('school.name', function($row){
                    return $row->school->name;
                })
                ->addColumn('action', function($row){
                    return '<a href="#" class="btn btn-sm btn-primary">Edit</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
    
}