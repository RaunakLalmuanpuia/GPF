<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\EntryInfo;
use App\Models\Signatory;

class GpfController extends Controller
{
    //
    public function get_entry_info(){
        $entry_info = EntryInfo::with(['individualInfos','template'])->orderBy('id', 'DESC')->get();
        $entry_info->load('signatory');
        return response()->json([
            'entry_info' => $entry_info
        ],200);
    }
    public function search_gpf(Request $request){
        $search = $request->get('s');
        if($search!=null){
            $entry_info = EntryInfo::with(['individualInfos','template','signatory'])
            ->where('status','LIKE', "%$search%")
            ->orWhere('file_number','LIKE', "%$search%")
            ->orWhere('amount','LIKE', "%$search%")
            ->orWhere('date','LIKE', "%$search%")
            ->orWhere('from_designation','LIKE', "%$search%")
            ->orWhere('from_deparment','LIKE', "%$search%")
            ->orWhere('gpf_name','LIKE', "%$search%")
            ->get();

            return response()->json([
                'entry_info' => $entry_info
            ],200);
        }else{
            return $this->get_entry_info();
        }
    }

   
    public function save_gpf(Request $request){
        // dd($request);
        try {
            // Access data directly from the request
            $fileNumber = $request->input('file_number');
            $date = $request->input('date');
            $amount = $request->input('amount');
            $status = $request->input('status');
            $individualInfos = $request->input('individual_infos');
            $signatory_id = $request->input('selectedSignatory');
            $department = $request->input('department');
            $designation = $request->input('designation');
            $name = $request->input('name');

            // Create a new entry info
            $entryInfo = EntryInfo::create([
                'file_number' => $fileNumber,
                'date' => $date,
                'amount' => $amount,
                'status' => $status,
                'signatory_id' => $signatory_id,
                'from_designation' => $designation,
                'from_deparment' => $department,
                'gpf_name' => $name
            ]);
            
    
            // Save individual info for each line
            foreach ($individualInfos as $individualInfoData) {
                $entryInfo->individualInfos()->create([
                    'name' => $individualInfoData['name'],
                    'designation' => $individualInfoData['designation'],
                    'account' => $individualInfoData['account'],
                    'amount' => $individualInfoData['amount'],
                    'phone' => $individualInfoData['mobile'],
                    'status' => $individualInfoData['status'],
                ]);
            }
    
            return response()->json($entryInfo, 201);
        } catch (\Exception $e) {
            // Handle any exceptions
            info($e);
            return response()->json(['error' => 'Failed to save GPF entry.'], 500);
        }
    }

    public function signatory(){
        $signatory = Signatory::orderBy('id', 'DESC')->get();
        return response()->json([
            'signatory' => $signatory
        ],200);
    }

    public function show_gpf($id){
        $entry_info = EntryInfo::with(['individualInfos','template', 'signatory'])->find($id);
        return response()->json([
            'entry_info' => $entry_info
        ],200);
    }
    
    public function edit_gpf($id){
        $entry_info = EntryInfo::with(['individualInfos','template', 'signatory'])->find($id);
        return response()->json([
            'entry_info' => $entry_info
        ],200);
    }
}
