<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with('supplier')->get();

        return response()->json($products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       

        // $validate = $request->validate([
        //     'product_name' => 'required',
        //     'barcode' => 'required|unique:products',
        //     'price'=> 'required',
        //     'category' => 'required',
        //     'supplier_id' => 'required',
        // ]);

      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = new Product;
       
        $validator = \Validator::make($request->all(), [
            'product_name' => 'required',
            'barcode' => 'required|unique:products',
            'price'=> 'required',
            'category' => 'required',
            'supplier' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = json_decode($validator->errors());
            return response()->json([
                'success' => false,
                'message' => $errors
            ],422);
            
        } else {
            $product->barcode = $request->barcode;
            $product->product_name = $request->product_name;
            $product->price = $request->price;
            $product->quantity = 0;
            $product->category = $request->category;
            $product->supplier_id = $request->supplier;
            // dd($request->all());
            $product->save();
        }

      
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $product = Product::findOrFail($request->product_id);
        $product->barcode = $request->barcode;
        $product->product_name = $request->product_name;
        $product->price = $request->price;
        $product->category = $request->category;
        $product->supplier_id = $request->supplier;
        $product->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product_id,Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        
        $product->delete();
        
    }

    public function stockIn(Request $request){
        $product_id = $request->product_id;
        $product = Product::findOrFail($product_id);
        $product->increment('quantity',$request->stocks); 
        // dd($request->all());
        $product->save();
    }
}
