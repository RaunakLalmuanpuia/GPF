<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\EntryInfo;

class GpfController extends Controller
{
    //
    public function get_entry_info(){
        $entry_info = EntryInfo::with(['individualInfos','template'])->orderBy('id', 'DESC')->get();

        return response()->json([
            'entry_info' => $entry_info
        ],200);
    }
    public function search_gpf(Request $request){
        $search = $request->get('s');
        if($search!=null){
            $entry_info = EntryInfo::with(['individualInfos','template'])
            ->where('status','LIKE', "%$search%")
            ->get();

            return response()->json([
                'entry_info' => $entry_info
            ],200);
        }else{
            return $this->get_entry_info();
        }
    }

    // public function save_gpf(Request $request){
    //     // dd($request);
    //      // Validate the request data
    //      $validatedData = $request->validate([
    //         'file_number' => 'required',
    //         'date' => 'required|date',
    //         // 'signatory' => 'required',
    //         'amount' => 'required|numeric',
    //         'status' => 'required',
    //         // 'reference' => 'nullable|string',
    //         'individual_infos' => 'required|array',
    //         'individual_infos.*.name' => 'required|string',
    //         'individual_infos.*.designation' => 'required|string',
    //         'individual_infos.*.gpf_account' => 'required|string',
    //         'individual_infos.*.amount' => 'required|numeric',
    //         'individual_infos.*.mobile' => 'required|string',
    //     ]);

    //     // Create a new entry info
    //     $entryInfo = EntryInfo::create([
    //         'file_number' => $validatedData['file_number'],
    //         'date' => $validatedData['date'],
    //         // 'signatory' => $validatedData['signatory'],
    //         'amount' => $validatedData['amount'],
    //         'status' => $validatedData['status'],
    //         // 'reference' => $validatedData['reference'],
    //     ]);

    //     // Save individual info for each line
    //     foreach ($validatedData['individual_infos'] as $individualInfoData) {
    //         $entryInfo->individualInfos()->create([
    //             'name' => $individualInfoData['name'],
    //             'designation' => $individualInfoData['designation'],
    //             'gpf_account' => $individualInfoData['gpf_account'],
    //             'amount' => $individualInfoData['amount'],
    //             'mobile' => $individualInfoData['mobile'],
    //         ]);
    //     }

    //     return response()->json($entryInfo, 201);
    // }
    public function save_gpf(Request $request){
        try {
            // Access data directly from the request
            $fileNumber = $request->input('file_number');
            $date = $request->input('date');
            $amount = $request->input('amount');
            $status = $request->input('status');
            $individualInfos = $request->input('individual_infos');
    
            // Create a new entry info
            $entryInfo = EntryInfo::create([
                'file_number' => $fileNumber,
                'date' => $date,
                'amount' => $amount,
                'status' => $status,
            ]);
    
            // Save individual info for each line
            foreach ($individualInfos as $individualInfoData) {
                $entryInfo->individualInfos()->create([
                    'name' => $individualInfoData['name'],
                    'designation' => $individualInfoData['designation'],
                    'gpf_account' => $individualInfoData['account'],
                    'amount' => $individualInfoData['amount'],
                    'mobile' => $individualInfoData['mobile'],
                ]);
            }
    
            return response()->json($entryInfo, 201);
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json(['error' => 'Failed to save GPF entry.'], 500);
        }
    }
}
