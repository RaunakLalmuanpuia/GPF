<?php

namespace App\Http\Controllers;
use App\Models\IndividualInfo;
use Illuminate\Http\Request;

class IndividualController extends Controller
{
    public function index(){
        $individual_info = IndividualInfo::with(['entryInfo.signatory', 'template' => function ($query) {
            $query->withTrashed();
        }])
        ->orderBy('id', 'DESC')
        ->get();
        return response()->json([
            'individual_info' => $individual_info
        ],200);
    }

    public function search_individual(Request $request)
    {
        $search = $request->get('s');
        if ($search != null) {
            try {
                $individual_info = IndividualInfo::with(['entryInfo.signatory', 'template' => function ($query) {
                    $query->withTrashed();
                }])
                ->where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%$search%")
                        ->orWhere('account', 'LIKE', "%$search%")
                        ->orWhere('amount', 'LIKE', "%$search%")
                        ->orWhere('designation', 'LIKE', "%$search%")
                        ->orWhere('phone', 'LIKE', "%$search%")
                        ->orWhere('status', 'LIKE', "%$search%");
                })
                ->orWhereHas('entryInfo', function ($query) use ($search) {
                    $query->where('file_number', 'LIKE', "%$search%")
                        ->orWhere('from_deparment', 'LIKE', "%$search%")
                        ->orWhere('date', 'LIKE', "%$search%");
                })
                ->get();
            } catch (\Throwable $th) {
                info($th);
                return response()->json(['error' => 'An error occurred while searching.'], 500);
            }

            return response()->json([
                'individual_info' => $individual_info
            ], 200);
        } else {
            return $this->index();
        }
    }

}
