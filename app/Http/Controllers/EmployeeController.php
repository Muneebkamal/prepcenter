<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = User::all();
        return view('employee.index',compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('employee.add-employee');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8', // Ensure password is required and has a minimum length
            'phone_no' => 'nullable|string|max:20',
            'role' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'status' => 'required|string|max:50',
            'rate' => 'nullable|numeric'
        ]);

        // Concatenate first and last names
        $name = $request->first_name . ' ' . $request->last_name;

        // Create a new User instance and set its properties
        $employee = new User;
        $employee->name = $name;
        $employee->email = $request->email;
        $employee->password = Hash::make($request->password); // Hash the provided password
        $employee->phone_no = $request->phone_no;
        $employee->role = $request->role;
        $employee->department = $request->department;
        $employee->status = $request->status;
        $employee->rate = $request->rate;

        // Save the employee to the database
        $employee->save();

        return response()->json(['success'=>'employee added Successful!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $employee = User::where('id', $id)->first();
        // dd($employee[0]->email);
        return view('employee.edit-employee', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request data
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_no' => 'nullable|string|max:20',
            'role' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'status' => 'required|string|max:50',
            'rate' => 'nullable|numeric'
        ]);

        // Concatenate first and last names
        $name = $request->first_name . ' ' . $request->last_name;

        // Create a new User instance and set its properties
        $employee = User::where('id',$id)->first();
        $employee->name = $name;
        $employee->first_name = $request->first_name;
        $employee->last_name = $request->last_name;
        if ($request->filled('password')) {
            $employee->password = Hash::make($request->password); // Hash the new password
        }
        // $employee->password = Hash::make($request->password);
        $employee->phone_no = $request->phone_no;
        $employee->role = $request->role;
        $employee->department = $request->department;
        $employee->status = $request->status;
        $employee->rate = $request->rate;

        // Save the employee to the database
        $employee->save();

        return response()->json(['success'=>'employee updated Successful!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $employee = User::find($id);
        if (!$employee) {
            return response()->json(['message' => 'Employee not found.'], 404);
        }
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee Deleted successfully.');
        // return response()->json(['message' => 'Employee deleted successfully.'], 200);
    }


    public function mergeData()
    {
        // Fetch matching employee and user records using a JOIN
        $results = DB::table('old_employee')
        ->where('old_employee.email', '<>', '') 
        ->get();
            // dd($results);
        // Insert the results into the user_combined table
        foreach ($results as $result) {
            $email = $result->email; // Replace with the email you want to check
            $existingUser = DB::table('old_users')
                ->where('email', $email)
                ->first();
                // print_r($existingUser);
                // dd($result->first_name);
            if ($existingUser) {
                User::create([
                    'id' => $result->id,
                    'name' => $existingUser->username,
                    'first_name' => $result->first_name,
                    'last_name' => $result->last_name,
                    'email' => $result->email,
                    'password' => Hash::make($existingUser->password),
                    'phone_no' => $result->phone,
                    'role' => $result->role_id,
                    'department' => $result->id_department,
                    'status' => $result->employee_status,
                    'rate' => $result->rate,
                ]);
            }
        }

        return 'Data merged successfully!';
    }
}
