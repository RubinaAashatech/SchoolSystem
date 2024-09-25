<?php

namespace App\Http\Controllers\Shared;

use Alert;
use Validator;
use Image, View;
use Carbon\Carbon;
use App\Models\User;
use App\Models\School;
use App\Models\District;
use App\Models\SchoolUser;

use App\Models\SchoolGroup;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Services\FormService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;


class SchoolController extends Controller
{

    protected $formService;

    protected $imageSavePath = '/uploads/schools/';

    public function __construct(FormService $formService)
    {
        $this->formService = $formService;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getDistrict($province_id)
    {

        $districts = $this->formService->getDistricts($province_id);
        return response()->json($districts);
    }

    public function getMunicipality($district_id)
    {

        $municipalities = $this->formService->getMunicipalities($district_id);
        return response()->json($municipalities);
    }

    public function getWard($municipality_id)
    {
        $wards = $this->formService->getWards($municipality_id);

        return response()->json($wards);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $page_title = 'Schools  Listing';
        $permissions = Permission::orderBy('id', 'asc')->get('id');
        $jsonData = $permissions->toJson();
        // dd($jsonData);

        return view('backend.shared.school.index', compact('page_title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $page_title = 'School Create Form';

        $states = $this->formService->getProvinces();
        $groups = SchoolGroup::all();

        $adminStateId = Auth::user()->state_id;
        $adminDistrictId = Auth::user()->district_id;
        $adminMunicipalityId = Auth::user()->municipality_id;

        return view('backend.shared.school.create', compact('states', 'page_title', 'groups', 'adminStateId', 'adminDistrictId', 'adminMunicipalityId'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'state_id' => 'required',
            'district_id' => 'required',
            'municipality_id' => 'required',
            'ward_id' => 'required',
            'school_type' => 'required',
            'school_code' => 'required',
            'name' => 'required',
            'address' => 'required',
            'phone_number' => 'required',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'email' => 'required|unique:users,email',
            'is_active' => 'required',
        ]);
    
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
    
        try {
            // Begin transaction
            DB::beginTransaction();
    
              // Handle file upload for logo
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $newLogoName = time() . '.' . $logo->getClientOriginalName();
            $logoPath = 'uploads/schoollogo/';
            $logo->move(public_path($logoPath), $newLogoName);
            $fullLogoPath = $logoPath . $newLogoName;
        } else {
            throw new \Exception('Logo file not found.');
        }
    
            // Create the school record
            $school = School::create([
                'state_id' => $request->state_id,
                'district_id' => $request->district_id,
                'municipality_id' => $request->municipality_id,
                'ward_id' => $request->ward_id,
                'school_type' => $request->school_type,
                'school_code' => $request->school_code,
                'name' => $request->name,
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'logo' => $fullLogoPath, // Store the image filename in 'logo' column
                'email' =>$request->email,
                'is_active' => $request->is_active,
            ]);
    
            if ($school) {
                // Create the user associated with the school
                $user = new User();
                $user->user_type_id = 5; // Assuming this is the user type for schools
                $user->role_id = 5; // Assuming this is the role ID for schools
                $user->school_id = $school->id;
                $user->state_id = $school->state_id;
                $user->district_id = $school->district_id;
                $user->municipality_id = $school->municipality_id;
                $user->ward_id = $school->ward_id;
                $user->f_name = $school->name;
                $user->email = $request->email; // Use the provided email from the form
                $user->phone = $school->phone_number;
                $user->image = $fullLogoPath; // Store the same image filename in 'image' column of the users table
                $user->username = strtolower(str_replace(' ', '', $school->name) . '-' . trim($school->school_code));
                $user->password = Hash::make('password'); // Temporary password, change as per your logic
                $user->local_address = $school->address;
                $user->is_active = $request->is_active;
                $user->save();
    
                if ($user) {
                    $user->assignRole(5); // Assign role if using a role management system
                    SchoolUser::create([
                        'school_id' => $school->id,
                        'user_id' => $user->id
                    ]);
                }
    
                DB::commit();
                return redirect()->back()->withToastSuccess('School Successfully Registered!');
            }
        } catch (\Exception $e) {
            // Rollback transaction in case of error
            DB::rollBack();
            return back()->withToastError($e->getMessage())->withInput();
        }
    }

    public function getSchoolId()
    {
        // Assuming you fetch the school ID based on the logged-in user
        $schoolId = Auth::user()->school_id;
        return response()->json(['school_id' => $schoolId]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {

            $school = School::findOrFail($id);
            $page_title = "Edit School";
            $states = $this->formService->getProvinces();
            $groups = SchoolGroup::all();


            return view('backend.shared.school.update', compact('school', 'page_title', 'states', 'groups'));
        } catch (\Exception $e) {
            return back()->withToastError($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'state_id' => 'required',
                'district_id' => 'required',
                'municipality_id' => 'required',
                'ward_id' => 'required',
                'school_type' => 'required',
                'school_code' => 'required',
                'name' => 'required',
                'address' => 'required',
                'phone_number' => 'required',
                'email' => 'required',
                'head_teacher' => 'nullable',
                'established_date' => 'nullable',
                // 'emergency_contact' => 'required',
                // 'bank_name' => 'required',
                // 'disable_reason' => 'required',
                // 'facebook' => 'required',
                // 'twitter' => 'required',
                // 'linkedin' => 'required',
                // 'instagram' => 'required',
                // 'website' => 'required',
                // 'note' => 'required',
                // 'disable_at' => 'required',
                // 'verification_code' => 'required',
                'is_active' => 'required',
            ]);

            $input = $request->all();

            // Check if a new logo is uploaded
            if ($request->has('inputCroppedPic') && !is_null($request->inputCroppedPic)) {
                // Handle logo upload
                if (!File::exists($this->imageSavePath)) {
                    File::makeDirectory($this->imageSavePath, 0775, true, true);
                }
                $destinationPath = $this->imageSavePath . $this->getDateFormatFileName('jpg');
                Image::make($request->input('inputCroppedPic'))
                    ->encode('jpg')
                    ->save(public_path($destinationPath));

                $input['logo'] = $destinationPath;
            } else {
                // If no new logo uploaded, remove the logo field from the input
                unset($input['logo']);
            }

            // Update the school record
            $school = School::findOrFail($id);
            $school->update($input);

            // Check if user with admin role exists for this school, if yes, update user details
            $schoolAdmin = User::where('school_id', $school->id)->where('role_id', 5)->first();
            if ($schoolAdmin) {
                $schoolAdmin->update([
                    'email' => $school->email,
                    "user_type_id" => 5,
                    "role_id" => 5,
                    "school_id" => $school->id,
                    "state_id" => $school->state_id,
                    "district_id" => $school->district_id,
                    "municipality_id" => $school->municipality_id,
                    "ward_id" => $school->ward_id,
                    "phone" => $school->phone_number,
                    "local_address" => $school->address,
                    "image" => $school->logo,
                    "bank_name" => $school->bank_name,
                    "disable_reason" => $school->disable_reason,
                    "facebook" => $school->facebook,
                    "twitter" => $school->twitter,
                    "linkedin" => $school->linkedin,
                    "instagram" => $school->instagram,
                    "note" => $school->note,
                    "disable_at" => $school->disable_at,
                    "established_date" => $school->established_date,
                    "is_active" => $school->is_active,

                ]);
            }

            return redirect()->back()->withToastSuccess('School Successfully Updated!');
        } catch (\Exception $e) {
            return back()->withToastError($e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $school = School::findOrFail($id);

            // Find and delete the associated user
            $user = User::where('school_id', $school->id)->first();
            if ($user) {
                $user->delete();
            }

            // Delete records from the pivot table (SchoolUser)
            SchoolUser::where('school_id', $school->id)->delete();

            // Delete the school
            $school->delete();

            return redirect()->back()->withToastSuccess('School Successfully Deleted!');
        } catch (\Exception $e) {
            return back()->withToastError($e->getMessage());
        }
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllSchools(Request $request)
    {
        $school = $this->getForDataTable($request->all());

        return Datatables::of($school)
            ->escapeColumns([])
            ->addColumn('name', function ($row) {
                return $row->name;
            })
            ->addColumn('phone_number', function ($row) {
                return $row->phone_number;
            })
            // ->escapeColumns([])
            // ->addColumn('school_group', function ($school) {
            //     return $school->group->name ? $school->group->name : "";
            // })
            // ->addColumn('district_name', function ($school) {
            //     return $school->district->name;
            // })
            ->addColumn('address', function ($school) {
                return $school->address;
            })
            ->addColumn('school_type', function ($school) {
                return $school->school_type;
            })
            ->addColumn('school_type', function ($school) {
                return $school->email;
            })
            ->addColumn('status', function ($school) {
                return $school->is_active == 1 ? '<span class="btn-sm btn-success">Active</span>' : '<span class="btn-sm btn-danger">Inactive</span>';
            })

            ->addColumn('actions', function ($school) {
                return view('backend.shared.school.partials.controller_action', [
                    'school' => $school
                ])->render();
            })

            ->make(true);
    }





    /**
     * Get all the required data
     *
     * @return Response
     */
    // public function getForDataTable($request)
    // {
    //     $dataTableQuery = School::where(function ($query) use ($request) {
    //         if (isset($request->id)) {
    //             $query->where('id', $request->id);
    //         }
    //     })
    //         ->get();

    //     dd($dataTableQuery);
    //     return $dataTableQuery;
    // }
    public function getForDataTable($request)
    {
        $dataTableQuery = School::with('district', 'group') // Eager load relationships
            ->when(isset($request->id), function ($query) use ($request) {
                $query->where('id', $request->id);
            })
            ->get();

        // dd($dataTableQuery);
        return $dataTableQuery;
    }
     public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed'
        ]);


        $user = User::where('school_id', $id)->firstOrFail();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->withToastSuccess('Password reset successfully!');

    }
}
