<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

use App\Models\EntryInfo;
use App\Models\IndividualInfo;
use App\Models\Signatory;
use App\Models\Template;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
class GpfController extends Controller
{
    
    public function get_entry_info(){
        $entry_info = EntryInfo::with(['individualInfos', 'template', 'signatory' , 'departments' => function ($query) {
            $query->withTrashed();
        }])
        ->orderBy('id', 'DESC')
        ->get();
        // ->paginate();
        return response()->json([
            'entry_info' => $entry_info
        ],200);
    }

    public function search_gpf(Request $request){
        $search = $request->get('s');
        if($search!=null){
            try {
                $entry_info = EntryInfo::with(['individualInfos', 'template', 'signatory' , 'departments' => function ($query) {
                    $query->withTrashed();
                }])
                ->where(function ($query) use ($search) {
                    $query->Where('file_number','LIKE', "%$search%")
                    ->orWhere('amount','LIKE', "%$search%")
                    ->orWhere('date','LIKE', "%$search%")
                    ->orWhere('from_designation','LIKE', "%$search%")
                    // ->orWhere('from_deparment','LIKE', "%$search%")
                    ->orWhere('gpf_name','LIKE', "%$search%");
                })
                ->orWhereHas('signatory', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%$search%");
                        //   ->orWhere('designation', 'LIKE', "%$search%");
                })
                ->orWhereHas('departments', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%$search%");
                        //   ->orWhere('designation', 'LIKE', "%$search%");
                })
                // ->where('status','LIKE', "%$search%")
                // ->orWhere('file_number','LIKE', "%$search%")
                // ->orWhere('amount','LIKE', "%$search%")
                // ->orWhere('date','LIKE', "%$search%")
                // ->orWhere('from_designation','LIKE', "%$search%")
                // ->orWhere('from_deparment','LIKE', "%$search%")
                // ->orWhere('gpf_name','LIKE', "%$search%")
                ->get();
            } catch (\Throwable $th) {
                //throw $th;
                info($th);
            }

            return response()->json([
                'entry_info' => $entry_info
            ],200);
        }else{
            return $this->get_entry_info();
        }
    }

    public function save_gpf(Request $request)
    {
        // dd($request);
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'file_number' => 'required|string',
            'date' => 'required|date',
            'individual_infos' => 'array',
            'selectedSignatory' => 'required|integer',
            'department' => 'required',
            'designation' => 'required|string',
            'name' => 'required|string',
            'individual_infos.*.name' => 'required|string',
            'individual_infos.*.designation' => 'required|string',
            'individual_infos.*.account' => 'required|string',
            'individual_infos.*.amount' => 'required|numeric',
            'individual_infos.*.mobile' => 'required|string',
            'individual_infos.*.status' => 'required|string',
        ]);

        // If validation fails, return the errors
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            // Access data directly from the validated request
            $fileNumber = $request->input('file_number');
            $date = $request->input('date');
            $individualInfos = $request->input('individual_infos');
            $signatory_id = $request->input('selectedSignatory');
            $department = $request->input('department');
            $designation = $request->input('designation');
            $name = $request->input('name');

            // Create a new entry info
            $entryInfo = EntryInfo::create([
                'file_number' => $fileNumber,
                'date' => $date,
                'signatory_id' => $signatory_id,
                'from_designation' => $designation,
                'department_id' => $department,
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

                //send sms
                $name = $individualInfoData['name'];
                $status = $individualInfoData['status'];
                $phone = $individualInfoData['mobile'];
                $account = $individualInfoData['account'];

                if($status == "Approved"){
                    info($status);
                    $message = 'Chibai  '.$name.',  MIPUI AW ah I lo in register avangin kan lawm e. I registration a fel tawh a. '.$status.'.mizoram.gov.in ah lut in Grievance I theih lut thei e. EGOV-MZ';
                     $templateId ='1407170608238248851';
                }
                elseif($status == "Rejected"){
                    info($status);
                    $message = 'Chibai  '.$name.',  MIPUI AW ah I lo in register avangin kan lawm e. I registration a fel tawh a. '.$status.'.mizoram.gov.in ah lut in Grievance I theih lut thei e. EGOV-MZ';
                    $templateId ='1407170608238248851';
                }
                elseif($status == "Pending"){
                    info($status);
                    $message = 'Chibai  '.$name.',  MIPUI AW ah I lo in register avangin kan lawm e. I registration a fel tawh a. '.$status.'.mizoram.gov.in ah lut in Grievance I theih lut thei e. EGOV-MZ';
                    $templateId ='1407170608238248851';
                }
                
                // $message = 'Chibai  '.$name.',  MIPUI AW ah I lo in register avangin kan lawm e. I registration a fel tawh a. '.$status.'.mizoram.gov.in ah lut in Grievance I theih lut thei e. EGOV-MZ';
                // $templateId ='1407170608238248851';
                
                
                Http::withHeaders([
                    'Authorization' => "Bearer 551|" . env('SMS_TOKEN'),
                 ])->get("https://sms.msegs.in/api/send-sms",[
                    'template_id' => $templateId,
                    'message' => $message,
                    'recipient'=> $phone
                 ]);         
            }

            return response()->json(['id'=>$entryInfo->id], 201);
        } catch (\Exception $e) {
            // Handle any exceptions
            info($e);
            return response()->json(['error' => 'Failed to save GPF entry.'], 500);
        }
    }

   

    public function show_gpf($id){
        $entry_info = EntryInfo::with(['individualInfos', 'template', 'signatory' , 'departments' => function ($query) {
            $query->withTrashed();
        }])->find($id);
        return response()->json([
            'entry_info' => $entry_info
        ],200);
    }
    
    public function edit_gpf($id){
        $entry_info = EntryInfo::with(['individualInfos', 'template', 'signatory' , 'departments' => function ($query) {
            $query->withTrashed();
        }])->find($id);
        return response()->json([
            'entry_info' => $entry_info
        ],200);
    }

    public function delete_individual($id){
        // dd($id);
        $individualInfo = IndividualInfo::findOrFail($id);
        $individualInfo->delete();
    }

  
    // public function update_gpf(Request $request, $id)
    // {
    //     // dd($request);
    //     // Validate the request data
    //     $validator = Validator::make($request->all(), [
    //         'file_number' => 'required|string',
    //         'date' => 'required|date',
    //         'selectedSignatory' => 'required|integer',
    //         'department' => 'required|integer',
    //         'designation' => 'required|string',
    //         'name' => 'required|string',
    //         'individual_infos' => 'array',
    //         'individual_infos.*.name' => 'required|string',
    //         'individual_infos.*.designation' => 'required|string',
    //         'individual_infos.*.account' => 'required|string',
    //         'individual_infos.*.amount' => 'required|numeric',
    //         'individual_infos.*.phone' => 'required|string',
    //         'individual_infos.*.status' => 'required|string',
    //     ]);
    
    //     // If validation fails, return the errors
    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 400);
    //     }
    
    //     try {
    //         // Find the entry info by ID
    //         $entry_info = EntryInfo::findOrFail($id);
    
    //         // Update the entry info attributes
    //         $entry_info->update([
    //             'file_number' => $request->input('file_number'),
    //             'date' => $request->input('date'),
    //             'signatory_id' => $request->input('selectedSignatory'),
    //             'from_designation' => $request->input('designation'),
    //             'department_id' => $request->input('department'),
    //             'gpf_name' => $request->input('name')
    //         ]);
    
    //         // Update or create individual info for each line
    //         $individualInfos = $request->input('individual_infos');
    //         foreach ($individualInfos as $individualInfoData) {
    //             // Check if the 'id' key is present in the individual info data array
    //             if (isset($individualInfoData['id'])) {
    //                 // Find individual info by ID or create new if not found
    //                 $individual_info = IndividualInfo::updateOrCreate(
    //                     ['id' => $individualInfoData['id']], // Use 'id' as the unique identifier
    //                     [
    //                         'name' => $individualInfoData['name'],
    //                         'designation' => $individualInfoData['designation'],
    //                         'account' => $individualInfoData['account'],
    //                         'amount' => $individualInfoData['amount'],
    //                         'phone' => $individualInfoData['phone'],
    //                         'status' => $individualInfoData['status'],
    //                     ]
    //                 );
    //             } else {
    //                 // If 'id' is not present, create a new entry in the database
    //                 $individual_info = IndividualInfo::create([
    //                     'name' => $individualInfoData['name'],
    //                     'designation' => $individualInfoData['designation'],
    //                     'account' => $individualInfoData['account'],
    //                     'amount' => $individualInfoData['amount'],
    //                     'phone' => $individualInfoData['phone'],
    //                     'status' => $individualInfoData['status'],
    //                 ]);
    //             }
    //             // Associate individual info with the entry info
    //             $entry_info->individualInfos()->save($individual_info);
    //             $name = $individualInfoData['name'];
    //             $status = $individualInfoData['status'];
    //             $message = 'Chibai  '.$name.',  MIPUI AW ah I lo in register avangin kan lawm e. I registration a fel tawh a. '.$status.'.mizoram.gov.in ah lut in Grievance I theih lut thei e. EGOV-MZ';
    //             $templateId ='1407170608238248851';
    //             $phone = $individualInfoData['phone'];
    //             info($message);

    //             Http::withHeaders([
    //                 'Authorization' => "Bearer 551|" . env('SMS_TOKEN'),
    //              ])->get("https://sms.msegs.in/api/send-sms",[
    //                 'template_id' => $templateId,
    //                 'message' => $message,
    //                 'recipient'=> $phone
    //              ]);    

    //         }
    
    //         return response()->json($entry_info, 200);
    //     } catch (\Exception $e) {
    //         // Handle any exceptions
    //         info($e);
    //         return response()->json(['error' => 'Failed to update GPF entry.'], 500);
    //     }
    // }

   
    public function update_gpf(Request $request, $id)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'file_number' => 'required|string',
            'date' => 'required|date',
            'selectedSignatory' => 'required|integer',
            'department' => 'required|integer',
            'designation' => 'required|string',
            'name' => 'required|string',
            'individual_infos' => 'array',
            'individual_infos.*.name' => 'required|string',
            'individual_infos.*.designation' => 'required|string',
            'individual_infos.*.account' => 'required|string',
            'individual_infos.*.amount' => 'required|numeric',
            'individual_infos.*.phone' => 'required|string',
            'individual_infos.*.status' => 'required|string',
        ]);

        // If validation fails, return the errors
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            // Find the entry info by ID
            $entry_info = EntryInfo::findOrFail($id);

            // Update the entry info attributes
            $entry_info->update([
                'file_number' => $request->input('file_number'),
                'date' => $request->input('date'),
                'signatory_id' => $request->input('selectedSignatory'),
                'from_designation' => $request->input('designation'),
                'department_id' => $request->input('department'),
                'gpf_name' => $request->input('name')
            ]);

            // Update or create individual info for each line
            $individualInfos = $request->input('individual_infos');
            foreach ($individualInfos as $individualInfoData) {
                // Check if the individual info is updated or newly created
                $isNew = !isset($individualInfoData['id']);
                // Find individual info by ID or create new if not found
                $individual_info = IndividualInfo::updateOrCreate(
                    ['id' => $individualInfoData['id'] ?? null], // Use 'id' as the unique identifier
                    [
                        'name' => $individualInfoData['name'],
                        'designation' => $individualInfoData['designation'],
                        'account' => $individualInfoData['account'],
                        'amount' => $individualInfoData['amount'],
                        'phone' => $individualInfoData['phone'],
                        'status' => $individualInfoData['status'],
                    ]
                );

                // Associate individual info with the entry info
                $entry_info->individualInfos()->save($individual_info);
                
                // Check if status is changed and send SMS
                if (!$isNew && $individual_info->wasChanged('status')) {
                    $name = $individualInfoData['name'];
                    $status = $individualInfoData['status'];
                    $message = 'Chibai  '.$name.',  MIPUI AW ah I lo in register avangin kan lawm e. I registration a fel tawh a. '.$status.'.mizoram.gov.in ah lut in Grievance I theih lut thei e. EGOV-MZ';
                    $templateId ='1407170608238248851';
                    $phone = $individualInfoData['phone'];
                    info($message);

                    Http::withHeaders([
                        'Authorization' => "Bearer 551|" . env('SMS_TOKEN'),
                    ])->get("https://sms.msegs.in/api/send-sms",[
                        'template_id' => $templateId,
                        'message' => $message,
                        'recipient'=> $phone
                    ]);  
                }
                if($isNew){
                    $name = $individualInfoData['name'];
                    $status = $individualInfoData['status'];
                    $message = 'Chibai  '.$name.',  MIPUI AW ah I lo in register avangin kan lawm e. I registration a fel tawh a. '.$status.'.mizoram.gov.in ah lut in Grievance I theih lut thei e. EGOV-MZ';
                    $templateId ='1407170608238248851';
                    $phone = $individualInfoData['phone'];
                    info($message);

                    Http::withHeaders([
                        'Authorization' => "Bearer 551|" . env('SMS_TOKEN'),
                    ])->get("https://sms.msegs.in/api/send-sms",[
                        'template_id' => $templateId,
                        'message' => $message,
                        'recipient'=> $phone
                    ]);  
                }
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

   
    
    public function save_approval_template(Request $request, $id)
    {
        try {
            // Check if $request->new is true
            if ($request->new) {
                // Create a new template
                $template = Template::create([
                    'purpose' => $request->input('purpose'),
                    'contents' => $request->input('content'),
                    'entry_info_id' => $id,
                ]);
            } else {
                // Try to find the template by ID
                $template = Template::find($request->template_id);
                // If the template does not exist, return an error
                if (!$template) {
                    return response()->json(['error' => 'Template not found.'], 404);
                }
                // Update the existing template with the new attributes
                $template->update([
                    'contents' => $request->input('content'),
                ]);
            }
            
            return response()->json($template, 200);
        } catch (\Exception $e) {
            // Handle any exceptions
            info($e);
            return response()->json(['error' => 'Failed to update GPF Approval Template.'], 500);
        }
    }
    //check if template exists
    public function check_existence(Request $request)
    { 
        $template = Template::where('entry_info_id', $request->entry_info)
                                ->where('purpose', $request->purpose)
                                ->first();
        if ($template) {
            return response()->json(['exists' => 1, 'id' => $template->id], 200);
        } else {
            return response()->json(['exists' => 0], 200);
        }

        
    }
    // fetch a template
    public function approval_templates($id){
        $template = Template::where('entry_info_id', $id)->where('purpose', 'approval')->latest()->first();
        if (!$template) {
            return response()->json(['message' => 'Template not found'], 404);
        }
        return response()->json(['template' => $template], 200);

    }
    public function department(){
        $department = Department::orderBy('id', 'DESC')->get();
        return response()->json([
            'department' => $department
        ],200);
    }
}
