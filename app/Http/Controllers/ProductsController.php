<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Products::all();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.add-product');
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
        $product->fnsku = $request->fnsku;
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
