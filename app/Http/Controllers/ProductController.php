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
        try {
            Product::create([
                'item_code' => $request->code,
                'description' => $request->description,
                'price' => $request->price, 
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Something went Wrong.', 'response' => $th->getMessage()]);
        }
        return response()->json(['message' => 'Product Saved.', 'status' => 200]);
        
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
