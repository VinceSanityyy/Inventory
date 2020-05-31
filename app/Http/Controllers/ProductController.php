<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use App\StockHistory;
use Carbon\Carbon;
use App\Events\ProductsEvent;
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
            // 'image' => 'required|image64:jpeg,jpg,png'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ],422);

        } else {
            $product->barcode = $request->barcode;
            $product->product_name = $request->product_name;
            $product->price = $request->price;
            $product->quantity = 0;
            $product->category = $request->category;
            $product->supplier_id = $request->supplier;
            $imageData = $request->image;
            $fileName = time().'.'. explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';'))) [1])[1];
            \Image::make($request->image)->save(public_path('img/').$fileName);
            $product->image = $fileName;
            $product->save();;

            broadcast(new ProductsEvent(\Auth::user()->name, 'add', $product))->toOthers();
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

        $currentImage = $product->image;

         if ($request->image != $currentImage){
             $name = time().'.'. explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';'))) [1])[1];
             \Image::make($request->image)->save(public_path('img/').$name);

             $image = public_path('img/').$currentImage;
             if(file_exists($image)){
                @unlink($image);
             }
         }else{
             $name = $currentImage;
         }
        
        //  dd($currentImage);
        $product->image = $name;
        $product->save();
        broadcast(new ProductsEvent(\Auth::user()->name, 'update', $product))->toOthers();
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
        // broadcast(new ProductsEvent(\Auth::user()->name, $product));
        broadcast(new ProductsEvent(\Auth::user()->name, 'delete', $product))->toOthers();
        $product->delete();

    }

    public function stockIn(Request $request){

        $stock_history = new StockHistory;
        $stock_history->user_id = \Auth::user()->id;
        $stock_history->product_id = $request->product_id;
        $stock_history->stock_added = $request->stocks;
        $stock_history->date_added = Carbon::now()->toDateTimeString();
        $stock_history->save();

        $product_id = $request->product_id;
        $product = Product::findOrFail($product_id);
        $product->increment('quantity',$request->stocks);
        // dd($request->all());
        $product->save();
        broadcast(new ProductsEvent(\Auth::user()->name, 'stockin', $product))->toOthers();
    }
}
