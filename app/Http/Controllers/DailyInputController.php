<?php

namespace App\Http\Controllers;

use App\Models\DailyInputDetail;
use App\Models\DailyInputs;
use App\Models\Department;
use App\Models\Products;
use App\Models\SystemSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Exists;

class DailyInputController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userIds = DB::table('users')->pluck('id');

        $daily_inputs = DailyInputs::with('user')
        ->leftJoinSub(
            DB::table('daily_input_details')
                ->select('daily_input_id', DB::raw('COALESCE(SUM(qty), 0) as total_qty'))
                ->whereNull('deleted_at')
                ->groupBy('daily_input_id'),
            'details_sum',
            'daily_inputs.id',
            'details_sum.daily_input_id'
        )
        ->select('daily_inputs.*', 'details_sum.total_qty')
        ->whereIn('daily_inputs.employee_id', $userIds);
        // Retrieve the start_date and end_date from the request
        $startDate = null;
        $endDate = null;
        $dateRange = $request->input('date_range');
        $dates = explode('_', $dateRange);
        
        if(count($dates) == 2) {
            $startDate = $dates[0];
            $endDate = $dates[1];
        }
        // Apply the date range filter if both dates are provided
        if ($startDate && $endDate) {
            $daily_inputs->whereBetween('date', [$startDate, $endDate]);
        }
        if(count($dates) == 2) {
            $startDate = $dates[0];
            $endDate = $dates[1];
        }else{
            $startDate = Carbon::today()->format('Y-m-d');
            $endDate = $startDate;
        }
        $weekStart = SystemSetting::pluck('week_started_day')->first();
        $weekStart =  $weekStart??6;
        $daily_inputs = $daily_inputs->orderBy('id', 'DESC')->get();
        return view('daily_input.index', get_defined_vars());
    }

    public function reportByEmployee(Request $request)
    {
        // Retrieve all employees for the dropdown
        $employees = User::where('status', '0')->get();
        // $dateRange = $request->input('date_range');
        // $dates = explode('_', $dateRange);
        // $startDate = $dates[0];
        // $endDate = $dates[1];
        $dateRange = $request->input('date_range');
        $dates = explode('_', $dateRange);
        
        if(count($dates) == 2) {
            $startDate = $dates[0];
            $endDate = $dates[1];
        } else {
            // Default to today's date if date range is null or not properly set
            $startDate = Carbon::today()->format('Y-m-d');
            $endDate = $startDate;
        }
        $query = DailyInputs::with('user')
        ->leftJoinSub(
            DB::table('daily_input_details')
                ->select('daily_input_id', DB::raw('COALESCE(SUM(qty), 0) as total_qty'))
                ->whereNull('deleted_at')
                ->groupBy('daily_input_id'),
            'details_sum',
            'daily_inputs.id',
            'details_sum.daily_input_id'
        )
        ->select('daily_inputs.*', 'details_sum.total_qty');
        // Query the user table
        $query = DailyInputs::with('user')
        ->leftJoinSub(
            DB::table('daily_input_details')
                ->select('daily_input_id', DB::raw('COALESCE(SUM(qty), 0) as total_qty'))
                ->whereNull('deleted_at')
                ->groupBy('daily_input_id'),
            'details_sum',
            'daily_inputs.id',
            'details_sum.daily_input_id'
        )
        ->select('daily_inputs.*', 'details_sum.total_qty');
        $query->whereBetween('date', [$startDate, $endDate]);
        // Determine the filter to apply, default to 'today'
        // $filterBy = $request->input('filter_by', 'today'); 
        // $weekStartDay = SystemSetting::first()->week_started_day ?? 6;
        // switch ($filterBy) {
        //     case 'today':
        //         $query->whereDate('daily_inputs.date', today());
        //         break;

        //     case 'custom':
        //         if ($request->has('filter_date')) {
        //             $filterDate = $request->input('filter_date');
        //             $query->whereDate('daily_inputs.date', $filterDate);
        //         }
        //         break;

        //     case 'last_week':
        //         $startOfLastWeek = now()->subDays(7)->startOfDay();
        //         $endOfLastWeek = now()->subDay()->endOfDay();
        //         $query->whereBetween('daily_inputs.date', [$startOfLastWeek, $endOfLastWeek]);
        //         break;

        //     case 'last_month':
        //         $startOfLastMonth = now()->subDays(30)->startOfDay();
        //         $endOfLastMonth = now()->endOfDay();
        //         $query->whereBetween('daily_inputs.date', [$startOfLastMonth, $endOfLastMonth]);
        //         break;

        //     case 'last_year':
        //         $startOfLastYear = now()->subDays(365)->startOfDay();
        //         $endOfLastYear = now()->endOfDay();
        //         $query->whereBetween('daily_inputs.date', [$startOfLastYear, $endOfLastYear]);
        //         break;

        //     case 'this_week':
        //         $currentDayOfWeek = now()->dayOfWeekIso;
        //         $startOfWeek = now()->startOfWeek()->subDays(($currentDayOfWeek - $weekStartDay + 7) % 7)->startOfDay();
        //         $endOfWeek = $startOfWeek->copy()->addDays(6)->endOfDay();
        //         $query->whereBetween('daily_inputs.date', [$startOfWeek, $endOfWeek]);
        //         break;
                
        // }

        if ($request->has('employee_id') && $request->input('employee_id') !== 'all') {
            $employeeId = $request->input('employee_id');
            $query->where('daily_inputs.employee_id', $employeeId);
        }
        $weekStart = SystemSetting::pluck('week_started_day')->first();
        $weekStart =  $weekStart??6;
        $report_by_employees = $query->orderBy('id', 'DESC')->get();
        return view('report_by_employee.index', get_defined_vars());
    }

    public function dashboard()
    {
        $weekStartDay = SystemSetting::first()->week_started_day ?? 6;

        $query = DailyInputs::with('user')
            ->leftJoinSub(
                DB::table('daily_input_details')
                    ->select('daily_input_id', DB::raw('COALESCE(SUM(qty), 0) as total_qty'))
                    ->whereNull('deleted_at')
                    ->groupBy('daily_input_id'),
                'details_sum',
                'daily_inputs.id',
                'details_sum.daily_input_id'
            )
            ->select('daily_inputs.*', 'details_sum.total_qty');
           // Get the custom start day from the settings, default to 6 (Saturday) if not set
            $weekStart = SystemSetting::pluck('week_started_day')->first();
            $weekStart = $weekStart ?? 6; // Default to Saturday if not set

            // Get the current day of the week (1 to 7, where 1 is Monday and 7 is Sunday)
            $currentDayOfWeek = Carbon::now()->dayOfWeekIso;

            // Calculate the difference between the current day and the custom start day
            $dayDifference = $weekStart - $currentDayOfWeek;

            // Calculate the start of the week
            $startOfWeek = Carbon::now()->startOfDay()->addDays($dayDifference);
            if ($dayDifference > 0) {
            $startOfWeek->subWeek();
            }

            // Calculate the end of the week
            $endOfWeek = $startOfWeek->copy()->addDays(6)->endOfDay();

            // Format the dates for the frontend
            $startOfWeekFormatted = $startOfWeek->format('Y-m-d');
            $endOfWeekFormatted = $endOfWeek->format('Y-m-d');

            // $currentDayOfWeek = now()->dayOfWeekIso;
            // $startOfWeek = now()->startOfWeek()->subDays(($currentDayOfWeek - $weekStartDay + 7) % 7)->startOfDay();
            // $endOfWeek = $startOfWeek->copy()->addDays(6)->endOfDay();
            $query->whereBetween('daily_inputs.date', [$startOfWeekFormatted, $endOfWeekFormatted]);

            $report_by_times = $query->orderBy('id', 'DESC')->get();

        return view('dashboard', compact('report_by_times'));
    }


    public function reportByTime(Request $request)
    {
        // Initialize the query
        $query = DailyInputs::with('user')
            ->leftJoinSub(
                DB::table('daily_input_details')
                    ->select('daily_input_id', DB::raw('COALESCE(SUM(qty), 0) as total_qty'))
                    ->whereNull('deleted_at')
                    ->groupBy('daily_input_id'),
                'details_sum',
                'daily_inputs.id',
                'details_sum.daily_input_id'
            )
            ->select('daily_inputs.*', 'details_sum.total_qty');
        $dateRange = $request->input('date_range');
        $dates = explode('_', $dateRange);
        
        if(count($dates) == 2) {
            $startDate = $dates[0];
            $endDate = $dates[1];
        } else {
            // Default to today's date if date range is null or not properly set
            $startDate = Carbon::today()->format('Y-m-d');
            $endDate = $startDate;
        }
        // Determine the filter to apply, default to 'today'
        // $filterBy = $request->input('filter_by', 'today'); 
        // $weekStartDay = SystemSetting::first()->week_started_day ?? 6;
        // switch ($filterBy) {
        //     case 'today':
        //         $query->whereDate('daily_inputs.date', today());
        //         break;

        //     case 'custom':
        //         if ($request->has('filter_date')) {
        //             $filterDate = $request->input('filter_date');
        //             $query->whereDate('daily_inputs.date', $filterDate);
        //         }
        //         break;

        //     case 'last_week':
        //         $startOfLastWeek = now()->subDays(7)->startOfDay();
        //         $endOfLastWeek = now()->subDay()->endOfDay();
        //         $query->whereBetween('daily_inputs.date', [$startOfLastWeek, $endOfLastWeek]);
        //         break;

        //     case 'last_month':
        //         $startOfLastMonth = now()->subDays(30)->startOfDay();
        //         $endOfLastMonth = now()->endOfDay();
        //         $query->whereBetween('daily_inputs.date', [$startOfLastMonth, $endOfLastMonth]);
        //         break;

        //     case 'last_year':
        //         $startOfLastYear = now()->subDays(365)->startOfDay();
        //         $endOfLastYear = now()->endOfDay();
        //         $query->whereBetween('daily_inputs.date', [$startOfLastYear, $endOfLastYear]);
        //         break;

        //     case 'this_week':
        //         $currentDayOfWeek = now()->dayOfWeekIso;
        //         $startOfWeek = now()->startOfWeek()->subDays(($currentDayOfWeek - $weekStartDay + 7) % 7)->startOfDay();
        //         $endOfWeek = $startOfWeek->copy()->addDays(6)->endOfDay();
        //         $query->whereBetween('daily_inputs.date', [$startOfWeek, $endOfWeek]);
        //         break;
        // }

        // Execute the query and get results
        $query->whereBetween('date', [$startDate, $endDate]);
        $report_by_times = $query->orderBy('id', 'DESC')->get();
        $weekStart = SystemSetting::pluck('week_started_day')->first();
        $weekStart =  $weekStart??6;
        // Return the view with the data
        return view('report_by_time.index', get_defined_vars());
    }

    public function monthlySummary(Request $request)
    {
        $employees = User::where('status', '0')->get();
        $query = DailyInputs::with('user');

        $filterMonth = $request->input('filter_month', null);
        $employeeId = $request->input('employee_id', null);

        if ($filterMonth && $employeeId !== null) {
            $monthYear = \DateTime::createFromFormat('Y-m', $filterMonth);

            if ($monthYear) {
                $month = $monthYear->format('m');
                $year = $monthYear->format('Y');

                $query->whereMonth('date', $month)
                    ->whereYear('date', $year);
            } else {
                $query->whereRaw('1 = 0');
            }

            if ($employeeId !== 'all') {
                $query->where('employee_id', $employeeId);
            }
        } else {
            $query->whereRaw('1 = 0');
        }

        $monthly_summary = $query->orderBy('id', 'DESC')->get();
        return view('monthly-summary.index', compact('monthly_summary', 'employees'));
    }

    public function systemSetting(Request $request)
    {
        $query = Products::query();
        $query->with(['dailyInputDetails' => function($query) {
            $query->select('fnsku')
            ->selectRaw('SUM(qty) as total_qty')
            ->groupBy('fnsku');
        }]);

        // Check if the 'temporary' parameter is set
        if ($request->has('temporary') && $request->temporary == 'on') {
            $query->where('item', 'LIKE', '%Temporary Product Name%'); // Adjust this condition to match your temporary product naming
        }
        $products =$query->get();
        // return view('products.index', compact('products'));

        $setting = SystemSetting::first();
        $departments = Department::all();
        
        if ($request->isMethod('post')) {
            $request->validate([
                'day' => 'required|integer',
            ]);

            if ($setting) {
                $setting->week_started_day = $request->day;
                $setting->save();

                return view('system-setting.index', compact('setting', 'departments', 'products'))->with('success', 'Day Updated Successfully');
            } else {
                $started_day = new SystemSetting;
                $started_day->week_started_day = $request->day;
                $started_day->save();

                return view('system-setting.index', compact('setting', 'departments', 'products'))->with('success', 'Day Added Successfully');
            }
        } else {
            return view('system-setting.index', compact('setting', 'departments', 'products'));
        }
    }

    public function depAdd(Request $request)
    {   
        $edit_id = $request->edit_id;
        $dep_id = Department::where('id', $edit_id)->first();
        
        if ($dep_id) {
            $dep_id->dep_name = $request->edit_dep;
            $dep_id->save();

            return redirect()->route('system.setting')->with('success', 'Department Update Successfully');
        }else{
            $department = new Department;
            $department->dep_name = $request->department;
            $department->save();

            $departments = Department::all();
            return redirect()->route('system.setting')->with('success', 'Department Add Successfully');
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = User::where('status', '0')->get();
        return view('daily_input.add-daily-input', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $start_time = $request->start_time;
        $end_time = $request->end_time;

        $start = Carbon::parse($start_time);
        $end = Carbon::parse($end_time);

        $total_seconds = $end->diffInSeconds($start);

        $total_hours = $total_seconds / 3600;

        $total_hours = number_format($total_hours, 2);

        // Retrieve the user based on the employee_id
        $user = User::find($request->employee_id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $total_paid = $user->rate * $total_hours;
        // Create a new DailyInputs record
        $daily_input = new DailyInputs;
        $daily_input->rate = $user->rate; // Fetch the rate from the user model
        $daily_input->employee_id = $request->employee_id;
        $daily_input->date = $request->date;
        $daily_input->start_time = $request->start_time;
        $daily_input->end_time = $request->end_time;
        $daily_input->total_time_in_sec = $total_seconds;
        $daily_input->total_paid =  $total_paid;

        $daily_input->save();

        $id = $daily_input->id;
        return response()->json([
            'success' => 'Product added Successful!',
            'id' => $id
        ]);
    }

    public function detailStore(Request $request)
    {
        $daily_input_id = $request->daily_input_id;
        $fnsku_value = $request->fnsku;
        $detail_update = DailyInputDetail::where('daily_input_id', $daily_input_id)->where('fnsku', $fnsku_value)->first();
        if($detail_update){
            $old_qty = $detail_update->qty;
            $new_qty = $old_qty + $request->qty;
            
            $detail_update->fnsku = $request->fnsku;
            $detail_update->qty = $new_qty;
            $detail_update->pack = $request->pack;
            $product = Products::where('fnsku', $fnsku_value)->first();
            $product->item = $request->item;
            
            $product->pack = $request->pack;
            $detail_update->save();
            $product->save();
        }else{
            $detail = new DailyInputDetail;
            $detail->daily_input_id = $daily_input_id;
            $detail->fnsku = $request->fnsku;
            if($request->qty != null){
                $detail->qty = $request->qty;
            }
            if($request->pack != null){
                $detail->pack = $request->pack;
            }

            $new_product = new Products;
            $new_product->fnsku = $request->fnsku;
            if($request->item ==null || $request->item == ""){
                $new_product->item = "Temporary Product Name";
            }else{
                $new_product->item = $request->item;
            }
            if($request->pack != null){
                $new_product->pack = $request->pack;
            }

            $detail->save();
            $new_product->save();
        }


        $total_qty = DailyInputDetail::where('daily_input_id', $daily_input_id)->sum('qty');
        $dailyInput = DailyInputs::where('id', $daily_input_id)->first();
        if ($dailyInput) {
            $totalPaid = $dailyInput->total_paid;
            $totalTimeInSeconds = $dailyInput->total_time_in_sec;
            $totalTimeHours = $totalTimeInSeconds / 3600;
        }
       
        $total_packing_cost_per_item = $totalPaid / $total_qty;
        $total_item_hour =$total_qty/ number_format($totalTimeHours, 2) ;
        
        $dailyInput->total_item_hour = number_format($total_item_hour, 5);
        $dailyInput->total_packing_cost = $total_packing_cost_per_item;

        $dailyInput->save();

        return response()->json([
            'success' => 'Product added Successful!',
            'id' => $daily_input_id
        ]);
    }

    public function detailEdit(Request $request, string $id)
    {
        
        $edit_detail = DailyInputDetail::where('id', $id)->first();
        $edit_detail->qty = $request->edit_qty;
        $edit_detail->pack = $request->edit_pack;
        
        $edit_detail->save();

        $total_qty = DailyInputDetail::where('daily_input_id', $edit_detail->daily_input_id)->sum('qty');
        $dailyInput = DailyInputs::where('id', $edit_detail->daily_input_id)->first();
        if ($dailyInput) {
            $totalPaid = $dailyInput->total_paid;
            $totalTimeInSeconds = $dailyInput->total_time_in_sec;
            $totalTimeHours = $totalTimeInSeconds / 3600;
        }

        $total_packing_cost_per_item = $totalPaid / $total_qty;
        $total_item_hour = $total_qty / number_format($totalTimeHours, 2) ;
        
        $dailyInput->total_item_hour = number_format($total_item_hour, 5);
        $dailyInput->total_packing_cost = $total_packing_cost_per_item;
        $dailyInput->save();

        return response()->json([
            'success' => 'Product added Successful!',
        ]);
    }

    public function checkFnsku(Request $request)
    {
        $fnsku = $request->fnsku;
        $product =  Products::where('fnsku', $fnsku)->first();
        
        if ($product) {
            return response()->json([
                'success' => true,
                'data' => $product 
            ]);
        } else {
            return response()->json([
                'success' => false,
                'name' => null
            ]);
        }
    }

    public function delete($id)
    {
        $daily_input_delete = DailyInputDetail::find($id);
        $daily_input_delete->delete();

        return redirect()->back()->with('success', 'Record Deleted Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $daily_input = DailyInputs::where('id', $id)->with('user')->first();
        $employees = User::where('status', '0')->get();
        $daily_input_details = DailyInputDetail::with('product')->where('daily_input_id', $id)->get();
        return view('daily_input.edit-daily-input', compact('daily_input','employees','daily_input_details'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $daily_input = DailyInputs::where('id', $id)->first();
        $employees = User::where('status', '0')->get();
        return view('daily_input.edit-time', compact('daily_input','employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $edit_daily_input = DailyInputs::find($id);

        if (!$edit_daily_input) {
            return response()->json(['success' => false, 'message' => 'Record not found.'], 404);
        }

        $edit_daily_input->start_time = $request->input('start_time');
        $edit_daily_input->end_time = $request->input('end_time');
        $edit_daily_input->save();

        return response()->json([
            'success' => true,
            'id' => $edit_daily_input->id,
            'message' => 'Daily Input Time updated successfully!'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $daily_input_delete = DailyInputs::find($id);
        $daily_input_delete->delete();

        return redirect()->back()->with('success', 'Record Deleted Successfully!');
    }
}
