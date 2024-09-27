<?php

namespace App\Http\Controllers\Shared;

use Alert;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Inclusivequota;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class InclusiveQuotasController extends Controller
{
    public function index()
    {
        $page_title = 'Inclusive Quotas Listing';
        $inclusivequotas = Inclusivequota::orderBy('created_at', 'desc')->paginate(10);
        return view('backend.shared.inclusivequotas.index', compact('page_title', 'inclusivequotas'));
    }
    public function create()
    {
        $page_title = 'Inclusive Create Form';
        return view('backend.shared.inclusivequotas.create', compact('page_title'));
    }
    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'priority' => 'required|string',
            'is_active' => 'required',
        ]);

        // try {
        //     $inclusivequotas = Inclusivequota::create($validatedData);
        //     Session::flash('success', 'Inclusive Quotas added successfully!');
        //     return redirect()->route('shared.inclusivequotas.index');
        // } catch (QueryException $e) {
        //     Session::flash('error', 'An error occurred while adding the Inclusive Quotas.');
        //     return redirect()->route('shared.inclusivequotas.create');
        // } catch (\Exception $e) {
        //     Session::flash('error', 'An error occurred.');
        //     return redirect()->route('shared.inclusivequotas.create');
        // }
        if ($validatedData->fails()) {

            return back()->withToastError($validatedData->messages()->all()[0])->withInput();
        }

        try {
            $inclusivequotas = $request->all();
            $savedData = Inclusivequota::Create($inclusivequotas);
            return redirect()->back()->withToastSuccess('Inclusive Quotes Saved Successfully!');

        } catch (\Exception $e) {
            // return back()->with('toast_error', $validator->messages()->all()[0])->withInput();
            return back()->withToastError($e->getMessage());
        }
    }
    public function edit($id)
    {
        $inclusivequota = Inclusivequota::find($id);

        if (!$inclusivequota) {
            return redirect()->route('shared.inclusivequotas.index')->with('error', 'Inclusive Quotas not found.');
        }
        return view('backend.shared.inclusivequotas.update', ['inclusivequota' => $inclusivequota]);
    }
    public function update(Request $request, $id)
    {
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'priority' => 'required|string',
            'is_active' => 'required',
        ]);

        // $inclusivequota = Inclusivequota::find($id);

        // if (!$inclusivequota) {
        //     return redirect()->route('shared.inclusivequotas.index')->with('error', 'Inclusive Quotas not found.');
        // }

        // try {
        //     $inclusivequota->update($validatedData);
        //     Session::flash('success', 'Inclusive Quotas updated successfully!');
        //     return redirect()->route('shared.inclusivequotas.index');
        // } catch (QueryException $e) {
        //     Session::flash('error', 'An error occurred while updating the Inclusive Quotas.');
        //     return redirect()->route('shared.inclusivequotas.edit');
        // } catch (\Exception $e) {
        //     Session::flash('error', 'An error occurred.');
        //     return redirect()->route('shared.inclusivequotas.edit');
        // }
        if ($validatedData->fails()) {

            return back()->withToastError($validatedData->messages()->all()[0])->withInput();
        }

        $inclusivequotas = Inclusivequota::findorfail($id);
        try {
            $inclusivequota = $request->all();
            $updateNow = $inclusivequotas->update($inclusivequota);

            return redirect()->back()->withToastSuccess('Successfully Updated Inclusive Quotas!');
        } catch (Exception $e) {
            return back()->withToastError($e->getMessage())->withInput();
        }
        return back()->withToastError('Cannot Update Inclusive Quotas Please try again')->withInput();
    }
    public function destroy($id)
    {
        $inclusivequota = Inclusivequota::find($id);
        try {
            $updateNow = $inclusivequota->delete();
            return redirect()->back()->withToastSuccess('Inclusive Quotas has been Successfully Deleted!');
        } catch (Exception $e) {
            $error_message = $e->getMessage();
            return back()->withToastError($e->getMessage());
        }

        return back()->withToastError('Something went wrong. please try again');
    }
    public function getAllInclusivequotas(Request $request)
    {
        $inclusivequota = $this->getForDataTable($request->all());

        return Datatables::of($inclusivequota)
            ->escapeColumns([])
            ->addColumn('name', function ($inclusivequota) {
                return $inclusivequota->name;
            })
            ->addColumn('description', function ($inclusivequota) {
                return $inclusivequota->description;
            })
            ->addColumn('priority', function ($inclusivequota) {
                return $inclusivequota->priority;
            })
            ->addColumn('created_at', function ($inclusivequota) {
                return $inclusivequota->created_at->diffForHumans();
            })
            ->addColumn('status', function ($inclusivequota) {
                return $inclusivequota->is_active == 1 ? '<span class="btn-sm btn-success">Active</span>' : '<span class="btn-sm btn-danger">Inactive</span>';

            })

            ->addColumn('actions', function ($inclusivequota) {
                return view('backend.shared.inclusivequotas.partials.controller_action', ['inclusivequota' => $inclusivequota])->render();
            })

            ->make(true);
    }
    public function getForDataTable($request)
    {
        $dataTableQuery = Inclusivequota::where(function ($query) use ($request) {
            if (isset($request->id)) {
                $query->where('id', $request->id);
            }
        })
            ->get();

        return $dataTableQuery;
    }

}


