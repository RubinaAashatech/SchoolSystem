<?php

namespace App\Http\Controllers\MunicipalityAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Staff;
use Yajra\DataTables\Facades\DataTables;

class StaffReportController extends Controller
{
    public function index()
    {
        $schools = School::all();
        return view('backend.municipality_admin.report.staffreport.index', compact('schools'));
    }

    public function report(Request $request)
    {
        if ($request->ajax()) {
            $query = Staff::with(['user:id,f_name,m_name,l_name,gender,phone', 'school:id,name']);

            if ($request->has('school_id') && $request->school_id != '') {
                $query->where('school_id', $request->school_id);
            }

            return DataTables::of($query)
                ->addColumn('name', function ($staff) {
                    return $staff->user->f_name . ' ' . $staff->user->m_name . ' ' . $staff->user->l_name;
                })
                ->addColumn('gender', function ($staff) {
                    return $staff->user->gender;
                })
                ->addColumn('phone', function ($staff) {
                    return $staff->user->phone; // Correctly fetch phone from user relationship
                })
                ->addColumn('school_name', function ($staff) {
                    return $staff->school ? $staff->school->name : 'N/A';
                })
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && $request->search['value'] != '') {
                        $searchValue = $request->search['value'];
                        $query->where(function ($q) use ($searchValue) {
                            $q->whereHas('user', function ($userQuery) use ($searchValue) {
                                $userQuery->where('f_name', 'like', "%{$searchValue}%")
                                    ->orWhere('m_name', 'like', "%{$searchValue}%")
                                    ->orWhere('l_name', 'like', "%{$searchValue}%")
                                    ->orWhere('gender', 'like', "%{$searchValue}%")
                                    ->orWhere('phone', 'like', "%{$searchValue}%");
                            })
                            ->orWhereHas('school', function ($schoolQuery) use ($searchValue) {
                                $schoolQuery->where('name', 'like', "%{$searchValue}%");
                            });
                        });
                    }
                })
                ->rawColumns(['name', 'gender', 'phone', 'school_name'])
                ->make(true);
        }

        $schools = School::all();
        return view('backend.municipality_admin.report.staffreport.index', compact('schools'));
    }
}