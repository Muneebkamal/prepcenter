<?php

namespace App\Http\Controllers;

use App\Models\DailyInputDetail;
use App\Models\DailyInputs;
use App\Models\Products;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DailyInputController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $daily_inputs = DailyInputs::all();
        // $daily_inputs = DailyInputs::with('user')->get();
        $daily_inputs = DailyInputs::with('user')
        ->leftJoinSub(
            DB::table('daily_input_details')
                ->select('daily_input_id', DB::raw('COALESCE(SUM(qty), 0) as total_qty'))
                ->groupBy('daily_input_id'),
            'details_sum',
            'daily_inputs.id',
            'details_sum.daily_input_id'
        )
        ->select('daily_inputs.*', 'details_sum.total_qty')
        ->orderBy('id', 'DESC')
        ->get();

        return view('daily_input.index', compact('daily_inputs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = User::where('status', 'active')->get();
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
            $product = Products::where('fnsku', 'like', '%' . $fnsku_value . '%')->first();
            $product->item = $request->item;
            $product->pack = $request->pack;
            $detail_update->save();
            $product->save();
        }else{
            $detail = new DailyInputDetail;
            $detail->daily_input_id = $daily_input_id;
            $detail->fnsku = $request->fnsku;
            $detail->qty = $request->qty;
            $detail->pack = $request->pack;

            $new_product = new Products;
            $new_product->fnsku = $request->fnsku;
            $new_product->item = $request->item;
            $new_product->pack = $request->pack;

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
        $total_item_hour = number_format($totalTimeHours, 2) / $total_qty;
        
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
        return response()->json([
            'success' => 'Product added Successful!',
        ]);
    }

    public function checkFnsku(Request $request)
    {
        $fnsku = $request->fnsku;
        $product =  Products::where('fnsku', 'like', '%' . $fnsku . '%')->first();
        
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
        $employees = User::where('status', 'active')->get();
        $daily_input_details = DailyInputDetail::with('product')->where('daily_input_id', $id)->get();
        return view('daily_input.edit-daily-input', compact('daily_input','employees','daily_input_details'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
