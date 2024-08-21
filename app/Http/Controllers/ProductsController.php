<?php

namespace App\Http\Controllers;

use App\Models\DailyInputDetail;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $sumQty = DailyInputDetail::whereHas('product')
        // ->whereColumn('daily_input_details.fnsku', 'products.fnsku')
        // ->sum('qty');
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
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.add-product');
    }

    public function importProducts()
    {
        return view('products.import-product');
    }

    public function uploadCSV(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->getRealPath();

            if (($handle = fopen($filePath, 'r')) !== false) {
                $header = fgetcsv($handle);

                while (($row = fgetcsv($handle)) !== false) {
                    $data = array_combine($header, $row);

                    Products::updateOrCreate(
                        ['fnsku' => $data['FNSKU'] ?? null],
                        [
                            'msku' => $data['MSKU'] ?? null,
                            'item' => $data['Title'] ?? null,
                            'asin' => $data['ASIN'] ?? null,
                            'pack' => 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }

                fclose($handle);

                return redirect()->back()->with('success', 'Products have been uploaded successfully!');
            } else {
                return redirect()->back()->with('error', 'Unable to open the file.');
            }
        }

        return redirect()->back()->with('error', 'Please select a valid CSV file.');
    }

    public function uploadWalmart(Request $request)
    {
        $request->validate([
            'walmartFile' => 'required|mimes:csv,txt|max:2048',
        ]);

        if ($request->hasFile('walmartFile')) {
            $file = $request->file('walmartFile');
            $filePath = $file->getRealPath();

            if (($handle = fopen($filePath, 'r')) !== false) {
                $header = fgetcsv($handle);

                while (($row = fgetcsv($handle)) !== false) {
                    $data = array_combine($header, $row);

                    Products::updateOrCreate(
                        ['fnsku' => $data['GTIN'] ?? null],
                        [
                            'msku' => $data['SKU'] ?? null,
                            'item' => $data['Item name'] ?? null,
                            'asin' => $data['Item ID'] ?? null,
                            'pack' => 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }

                fclose($handle);

                return redirect()->back()->with('success', 'Products have been uploaded successfully!');
            } else {
                return redirect()->back()->with('error', 'Unable to open the file.');
            }
        }

        return redirect()->back()->with('error', 'Please select a valid CSV file.');
    }

    public function importTable()
    {
        return view('products.import-table');
    }

    public function deleteDuplicate()
    {
        $duplicateFnskus = Products::select('fnsku')
        ->groupBy('fnsku')
        ->havingRaw('COUNT(fnsku) > 1')
        ->pluck('fnsku');
        // dd($duplicateFnskus);
        $latestIds = Products::select(DB::raw('MAX(id) as id'))
        ->whereIn('fnsku', $duplicateFnskus)
        ->groupBy('fnsku')
        ->pluck('id');

        $idsToDelete = Products::whereIn('fnsku', $duplicateFnskus)
        ->whereNotIn('id', $latestIds)
        ->pluck('id');
        // dd($idsToDelete);
        Products::whereIn('id', $idsToDelete)->delete();
        return true;
    }

    public function tempProductMerge(Request $request)
    {
        $productIds = $request->input('select_products', []);
        if(sizeof($productIds)>2){
            return response()->json(['error' => 'cannot merge more than two Products'], 404);
        }
        $temp = null;
        $orignal = null;
        foreach($productIds as $id){
            $product1 = Products::where('id',$id)->first();
            
            if($product1->item === 'Temporary Product Name'){
                $temp = $product1;
            }else{
                $orignal = $product1;
            }
        }
        if($temp){
            if($orignal){
                $dailyInputs = DailyInputDetail::where('fnsku', $temp->fnsku)->update([
                    'fnsku'=>$orignal->fnsku
                ]);
                $temp->delete();
                return response()->json(['success' => 'Product merged']);
            }else{
                return response()->json(['error' => 'Temporary Product not found'], 404);
            }
        }else{
            return response()->json(['error' => 'Temporary Product not found'], 404);
        }

        // $product1 = Products::where('id',$productIds[0])->first();
        // $product2 = Products::where('id',$productIds[1])->first();

        // // dd($product2->dailyInputDetails->pluck('total_qty'));

        // if($product1->item == 'Temporary Product Name'){
        //     if($product2->item != 'Temporary Product Name'){

        //         $dailyInputs = DailyInputDetail::where('fnsku', $product1->fnsku)->get();
        //         foreach($dailyInputs as $dailyInput){
        //             $dailyInput->fnsku = $product2->fnsku;
        //             $dailyInput->save();
        //         }
        //         $product1->delete();
        //         return response()->json(['success' => 'Product merged']);
        //     }else{
        //         return response()->json(['error' => 'Temporary Product not found'], 404);
        //     }
        // }elseif($product1->item != 'Temporary Product Name'){
        //     if($product2->item == 'Temporary Product Name'){
        //         $dailyInputs = DailyInputDetail::where('fnsku', $product2->fnsku)->get();
        //         foreach($dailyInputs as $dailyInput){
        //             $dailyInput->fnsku = $product1->fnsku;
        //             $dailyInput->save();
        //         }
        //         $product2->delete();
        //         return response()->json(['success' => 'Product merged']);
        //     }else{
        //         return response()->json(['error' => 'Temporary Product not found'], 404);
        //     }
        // }else{
        //     return response()->json(['error' => 'Temporary Product not found'], 404);
        // }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product = new Products;
        $product->item = $request->item;
        $product->msku = $request->msku;
        $product->asin = $request->asin;
        $product->fnsku = $request->fnsku;
        $product->pack = $request->pack;

        $product->save();

        return response()->json(['success'=>'product added Successful!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Products::where('id', $id)->first(); 
        return view('products.edit-product', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Products::where('id',$id)->first();
        $product->item = $request->item;
        $product->msku = $request->msku;
        $product->asin = $request->asin;
        // $product->fnsku = $request->fnsku;
        $product->pack = $request->pack;
        $product->save();
        return response()->json(['success'=>'product updated Successful!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function upload(Request $request)
    {
         // Validate the uploaded file
    $request->validate([
        'csv_file' => 'required|mimes:csv,txt|max:2048',
    ]);

    $file = $request->file('csv_file');
    $data = array_map('str_getcsv', file($file->getRealPath()));

    // Get the column headers
    $columns = isset($data[0]) ? $data[0] : [];
    $rows = array_slice($data, 1);

    // Define required columns for each file type
    $requiredColumnsAmazon = ['MSKU','Title', 'FNSKU','ASIN'];
    $requiredColumnsWalmart = ['Item name', 'GTIN', 'Item ID', 'SKU'];

    // Determine the type of file
    $isAmazonFile = !empty(array_intersect($columns, $requiredColumnsAmazon));
    $isWalmartFile = !empty(array_intersect($columns, $requiredColumnsWalmart));

    if (!$isAmazonFile && !$isWalmartFile) {
        return response()->json([
            'error' => 'CSV must contain columns for either Amazon (Title, MSKU, ASIN, FNSKU) or Walmart (Item name, GTIN, Item ID, SKU).'
        ], 400);
    }

    // Extract required columns based on file type
    if ($isAmazonFile) {
        $requiredColumns = $requiredColumnsAmazon;
    } else {
        $requiredColumns = $requiredColumnsWalmart;
    }

    // Get column indices
    $columnIndices = array_flip($columns);
    $filteredColumns = array_intersect($columns, $requiredColumns);

     // Filter and transform rows
     $filteredRows = array_map(function($row) use ($columnIndices, $requiredColumns, $isWalmartFile) {
        $filteredRow = array_intersect_key($row, array_flip(array_intersect_key($columnIndices, array_flip($requiredColumns))));
        // if ($isWalmartFile && isset($columnIndices['GTIN'])) {
        //     // Transform GTIN to FNSKU for frontend display
        //     $filteredRow['FNSKU'] = $row[$columnIndices['GTIN']];
        // }
        return $filteredRow;
    }, $rows);

    // Adjust the filtered columns for frontend display
    // if ($isWalmartFile && in_array('GTIN', $filteredColumns)) {
    //     $filteredColumns[] = 'FNSKU';
    //     $filteredColumns = array_diff($filteredColumns, ['GTIN']);
    // }
    return response()->json([
        'columns' => $filteredColumns,
        'rows' => $filteredRows,
    ]);

        // $request->validate([
        //     'csv_file' => 'required|mimes:csv,txt|max:2048',
        // ]);

        // $file = $request->file('csv_file');
        // $data = array_map('str_getcsv', file($file->getRealPath()));

        // $columns = isset($data[0]) ? $data[0] : [];
        // $rows = array_slice($data, 1);

        // $requiredColumns = ['Title',  'FNSKU', 'ASIN','MSKU'];
        // $columnIndices = array_flip($columns);
        // $filteredColumns = array_intersect($columns, $requiredColumns);

        // $filteredRows = array_map(function($row) use ($columnIndices, $requiredColumns) {
        //     return array_intersect_key($row, array_flip(array_intersect_key($columnIndices, array_flip($requiredColumns))));
        // }, $rows);

        // return response()->json([
        //     'columns' => $filteredColumns,
        //     'rows' => $filteredRows,
        // ]);
    }
    public function saveColumns(Request $request)
    {
        $request->validate([
            'column_mapping' => 'required|array',
            'rows' => 'required|array',
        ]);
      
        $mapping = $request->input('column_mapping');
        $rows = $request->input('rows');
        // Determine the file type based on the column mapping
        $isAmazonFile = isset($mapping['MSKU']) && isset($mapping['Title']) && isset($mapping['FNSKU']) && isset($mapping['ASIN']);
        $isWalmartFile = isset($mapping['Item name']) && isset($mapping['Item ID']) && isset($mapping['SKU']) && isset($mapping['GTIN']);
    


        // Validate that we have a valid file type
        if (!$isAmazonFile && !$isWalmartFile) {
            return response()->json(['status' => 'error', 'message' => 'Unsupported file format'], 400);
        }


        foreach ($rows as $row) {
            if ($isAmazonFile) {
                $fnsku = $row['FNSKU'];
                DB::table('products')->updateOrInsert(
                    ['fnsku' => $fnsku], // The condition to check if the record exists
                    [
                        'msku' => $row['MSKU'],
                        'item' => $row['Title'],
                        'asin' => $row['ASIN'],
                        // Update the fields with the new values
                    ]
                );
            } elseif ($isWalmartFile) {
                $fnsku = $row['GTIN'];
                DB::table('products')->updateOrInsert(
                    ['fnsku' => $fnsku], // The condition to check if the record exists
                    [
                        'item' => $row['Item name'],
                        'msku' => $row['SKU'],
                        'asin' => $row['Item ID'],
                        // Update the fields with the new values
                    ]
                );
            }
        }

        return response()->json(['status' => 'success']);
    }
}
