<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest('id')->get();
        return response()->json([
            'products' => $products
        ], 200);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        $message = '';
        try {
            if($request->id){
                $product = Product::where('id', $request->id)->first();
                if($product){
                    $updateArr['item_code'] = $request->code;
                    $updateArr['description'] = $request->description;
                    $updateArr['price'] = $request->price;

                    $product->update($updateArr);
                    $message = 'Product Updated.';
                }
            }else{
                Product::create([
                    'item_code' => $request->code,
                    'description' => $request->description,
                    'price' => $request->price, 
                ]);
                $message = 'Product Saved.';
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Something went Wrong.', 'response' => $th->getMessage()]);
        }
        return response()->json(['message' => $message, 'status' => 200]);
        
    }

    public function show($id)
    {
        $getProduct = Product::where('id', $id)->first();
        if($getProduct == null){
            return response()->json(['message' => 'Product not found.', 'status' => 400]);
        }  
        return response()->json(['message' => 'Product details .', 'status' => 200, 'product' => $getProduct]);
    }
    
    public function destroy($productId)
    {
        $product = Product::where('id', $productId)->first();
        if($product == null){
            return response()->json(['status' => 500, 'message' => 'Product not found.', 'response' => []]);
        }
        $product->delete();
        return response()->json(['message' => 'Product Deleted.', 'status' => 200]);
    }
}
