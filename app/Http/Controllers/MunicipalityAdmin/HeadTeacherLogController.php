<?php

namespace App\Http\Controllers\MunicipalityAdmin;

use App\Http\Controllers\Controller;
use App\Models\HeadTeacherLog;
use App\Models\School;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class HeadTeacherLogController extends Controller
{
    public function index()
    {
        $page_title = "Municipality Head Teacher Logs";
        $schools = School::all();
        return view('backend.municipality_admin.report.logreport.index', compact('page_title', 'schools'));
    }
    
    public function getAllHeadTeacherLogs(Request $request)
    {
        try {
            $query = $this->getFilteredQuery($request);
    
            return DataTables::of($query)
                ->addColumn('school_name', function ($log) {
                    return $log->school_name;
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Exception $e) {
            Log::error('Error in getAllHeadTeacherLogs: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while processing the request.'], 500);
        }
    }

    public function exportToExcel(Request $request)
    {
        try {
            $query = $this->getFilteredQuery($request);
            $logs = $query->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $headers = ['ID', 'School Name', 'Major Incidents', 'Major Work Observation', 'Assembly Management', 'Miscellaneous', 'Logged Date'];
            $sheet->fromArray([$headers], NULL, 'A1');

            $dataArray = $logs->map(function ($log) {
                return [
                    $log->id,
                    $log->school_name,
                    $log->major_incidents,
                    $log->major_work_observation,
                    $log->assembly_management,
                    $log->miscellaneous,
                    $log->logged_date,
                ];
            })->toArray();

            $sheet->fromArray($dataArray, NULL, 'A2');

            $writer = new Xlsx($spreadsheet);
            $filename = 'head_teacher_logs_' . date('Y-m-d') . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;

        } catch (\Exception $e) {
            Log::error('Error in exportToExcel: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while exporting the data.');
        }
    }

    private function getFilteredQuery(Request $request)
    {
        $query = HeadTeacherLog::join('schools', 'head_teacher_logs.school_id', '=', 'schools.id')
            ->select('head_teacher_logs.*', 'schools.name as school_name');

        if ($request->has('search') && !empty($request->search)) {
            $searchValue = $request->search;
            $query->where(function ($q) use ($searchValue) {
                $q->where('schools.name', 'like', "%{$searchValue}%")
                  ->orWhere('head_teacher_logs.major_incidents', 'like', "%{$searchValue}%");
            });
        }

        if ($request->has('school_id') && !empty($request->school_id)) {
            $query->where('schools.id', $request->school_id);
        }

        if ($request->has('start_date') && !empty($request->start_date)) {
            $startDate = $request->start_date;
            if ($request->has('end_date') && !empty($request->end_date)) {
                $endDate = $request->end_date;
                $query->whereBetween('head_teacher_logs.logged_date', [$startDate, $endDate]);
            } else {
                $query->whereDate('head_teacher_logs.logged_date', $startDate);
            }
        }

        return $query;
    }
}