<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\EntryInfo;
use App\Models\IndividualInfo;
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
            ->whereHas('signatory', function ($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%")
                      ->orWhere('designation', 'LIKE', "%$search%");
            })
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
    public function delete_individual($id){
        // dd($id);
        $individualInfo = IndividualInfo::findOrFail($id);
        $individualInfo->delete();

    }

    public function update_gpf(Request $request, $id){
        // info($request);
        try {
            // Find the entry info by ID
            $entry_info = EntryInfo::findOrFail($id);
    
            // Update the entry info attributes
            $entry_info->update([
                'file_number' => $request->input('file_number'),
                'date' => $request->input('date'),
                'amount' => $request->input('amount'),
                'status' => $request->input('status'),
                'signatory_id' => $request->input('selectedSignatory'),
                'from_designation' => $request->input('designation'),
                'from_deparment' => $request->input('department'),
                'gpf_name' => $request->input('name')
            ]);
    
            // Update or create individual info for each line
            $individualInfos = $request->input('individual_infos');
            foreach ($individualInfos as $individualInfoData) {
                // Check if the 'id' key is present in the individual info data array
                if (isset($individualInfoData['id'])) {
                    // Find individual info by ID or create new if not found
                    $individual_info = IndividualInfo::updateOrCreate(
                        ['id' => $individualInfoData['id']], // Use 'id' as the unique identifier
                        [
                            'name' => $individualInfoData['name'],
                            'designation' => $individualInfoData['designation'],
                            'account' => $individualInfoData['account'],
                            'amount' => $individualInfoData['amount'],
                            'phone' => $individualInfoData['phone'], // Fix the key name here
                            'status' => $individualInfoData['status'],
                        ]
                    );
                } else {
                    // If 'id' is not present, create a new entry in the database
                    $individual_info = IndividualInfo::create([
                        'name' => $individualInfoData['name'],
                        'designation' => $individualInfoData['designation'],
                        'account' => $individualInfoData['account'],
                        'amount' => $individualInfoData['amount'],
                        'phone' => $individualInfoData['phone'], // Fix the key name here
                        'status' => $individualInfoData['status'],
                    ]);
                }
            
                // Associate individual info with the entry info
                $entry_info->individualInfos()->save($individual_info);
            }
    
            return response()->json($entry_info, 200);
        } catch (\Exception $e) {
            // Handle any exceptions
            info($e);
            return response()->json(['error' => 'Failed to update GPF entry.'], 500);
        }
    }

    public function delete_gpf($id){
        $entry_info = EntryInfo::findOrFail($id);
        $entry_info->individualInfos()->delete();
        $entry_info->template()->delete();
        $entry_info->delete();
    }
    public function delete_signatory($id){
        $signatory = Signatory::findOrfail($id);
        $signatory->delete();

    }
    public function save_signatory(Request $request){
        // dd('save');
        $signatory = Signatory::create([
            'name' => $request->name,
            'designation' => $request->designation,
        ]);
        $signatory->save();

    }
}
