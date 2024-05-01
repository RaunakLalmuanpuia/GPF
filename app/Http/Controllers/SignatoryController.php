<?php

namespace App\Http\Controllers;

use App\Models\Signatory;
use Illuminate\Http\Request;

class SignatoryController extends Controller
{
    //
    public function signatory(){
        $signatory = Signatory::orderBy('id', 'DESC')->get();
        return response()->json([
            'signatory' => $signatory
        ],200);
    }

    // public function delete_signatory($id, Signatory $signatory){
    //     $signatory = Signatory::findOrfail($id);
    //     $signatory->delete();

    // }
    public function delete_signatory(Signatory $signatory)
    {
        $signatory->delete();

        return response()->json(['message' => 'Department deleted successfully']);
    }

    public function save_signatory(Request $request){
        // dd('save');
        // dd($request);
        $signatory = Signatory::create([
            'name' => $request->name,
            'designation' => $request->designation,
            'phone' => $request->phone,
        ]);
        $signatory->save();
    }

    public function update(Request $request, Signatory $signatory)
    {
        // dd($request);
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|integer',
            'designation' => 'nullable|string|max:255',
        ]);

        $signatory->update($request->all());

        return $signatory;
    }
    
    public function search_signatory(Request $request)
    {
        $search = $request->get('s');
        if ($search != null) {
            try {
                $signatory = Signatory::
                where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%$search%")
                        ->orWhere('designation', 'LIKE', "%$search%")
                        ->orWhere('phone', 'LIKE', "%$search%");
                       
                })
                ->get();
            } catch (\Throwable $th) {
                info($th);
                return response()->json(['error' => 'An error occurred while searching.'], 500);
            }

            return response()->json([
                'signatory' => $signatory
            ], 200);
        } else {
            return $this->signatory();
        }
    }
}
