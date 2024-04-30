<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\EntryInfo;
use App\Exports\GpfExport;
use Illuminate\Http\Request;
use Dotenv\Parser\EntryParser;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
   
    public function getReport(Request $request){
        $query = EntryInfo::query();
    
        // Filter by department
        if ($request->has('selectedDepartmentId')) {
            $query->where('department_id', $request->selectedDepartmentId);
        }
    
        if ($request->has('status')) {
            $status = $request->status;
            // Filter individualInfos based on status
            $query->whereHas('individualInfos', function ($q) use ($status) {
                $q->whereIn('status', $status);
            });
        }
    
        // Filter by date range (RoutineSheet creation date)
        if ($request->has('start_date') && $request->has('end_date')) {
            // Both `start_date` and `end_date` are provided
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($request->has('start_date')) {
            // Only `start_date` is provided, query from `start_date` to now
            $startDate = $request->start_date;
            $query->whereBetween('created_at', [$startDate, Carbon::now()]);
        } elseif ($request->has('end_date')) {
            // Only `end_date` is provided, query from the oldest record to `end_date`
            $endDate = $request->end_date;
            // Get the earliest `created_at` date to use as the start point
            $earliestRecord = EntryInfo::min('created_at'); // Find the earliest date
            $query->whereBetween('created_at', [$earliestRecord, $endDate]);
        }
    
        // // Include relationships
        // $query->with(['individualInfos' => function ($query) use ($status) {
        //     $query->whereIn('status', $status);
        // }, 'signatory', 'departments']);
        // Include relationships
        $query->with(['signatory', 'departments', 'individualInfos']);

        if ($request->has('status')) {
            $status = $request->status;
            $query->with(['individualInfos' => function ($query) use ($status) {
                $query->whereIn('status', $status);
            }]);
        }
    
        $reports = $query->get();
    
        return response()->json($reports);
    }

}
