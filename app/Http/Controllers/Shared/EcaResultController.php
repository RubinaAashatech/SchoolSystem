<?php

namespace App\Http\Controllers\Shared;
use App\Models\EcaResult;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class EcaResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ecaResults = EcaResult::with(['ecaParticipation'])->get();
        return response()->json($ecaResults);
    }

    /**
     * Store a newly created resource in storage.
     */
//     public function store(Request $request)
// {
//     Log::info('Store ECA Result called', $request->all());

//     $request->validate([
//         'eca_participation_id' => 'required|exists:eca_participations,id',
//         'result_type' => 'required|in:first,second,third',
//         'description' => 'nullable|string',
//         'is_publish' => 'boolean',
//     ]);

//     $ecaResult = EcaResult::create($request->all());
//     return response()->json($ecaResult, 201);
// }
   

    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'eca_participation_id' => 'sometimes|exists:eca_participations,id',
            'result_type' => 'sometimes|in:first,second,third',
            'description' => 'nullable|string',
            'is_publish' => 'boolean',
        ]);

        $ecaResult = EcaResult::findOrFail($id);
        $ecaResult->update($request->all());
        return response()->json($ecaResult);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ecaResult = EcaResult::findOrFail($id);
        $ecaResult->delete();
        return response()->json(null, 204);
    }
}
