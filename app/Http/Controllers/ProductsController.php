<?php

namespace App\Http\Controllers;

use App\Models\DailyInputDetail;
use App\Models\Products;
use Illuminate\Http\Request;
use League\Csv\Reader;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $sumQty = DailyInputDetail::whereHas('product')
        // ->whereColumn('daily_input_details.fnsku', 'products.fnsku')
        // ->sum('qty');
        
        $products = Products::with(['dailyInputDetails' => function($query) {
            $query->select('fnsku')
                ->selectRaw('SUM(qty) as total_qty')
                ->groupBy('fnsku');
        }])->get();
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
}
