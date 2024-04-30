<?php

namespace App\Http\Controllers;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    //
    public function department(){
        $department = Department::orderBy('id', 'DESC')->get();
        return response()->json([
            'department' => $department
        ],200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|integer',
            'address' => 'nullable|string|max:255',
        ]);

        return Department::create($request->all());
    }

    public function show(Department $department)
    {
        return $department;
    }

    public function update(Request $request, Department $department)
    {
        // dd($request);
        $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'nullable|integer',
            'address' => 'nullable|string|max:255',
        ]);

        $department->update($request->all());

        return $department;
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return response()->json(['message' => 'Department deleted successfully']);
    }

    public function search_department(Request $request)
    {
        $search = $request->get('s');
        if ($search != null) {
            try {
                $department = Department::
                where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%$search%")
                        ->orWhere('address', 'LIKE', "%$search%")
                        ->orWhere('number', 'LIKE', "%$search%");
                       
                })
                ->get();
            } catch (\Throwable $th) {
                info($th);
                return response()->json(['error' => 'An error occurred while searching.'], 500);
            }

            return response()->json([
                'department' => $department
            ], 200);
        } else {
            return $this->department();
        }
    }
}
