<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index()
    {
        $getCustomers = Customer::latest('id')->get();

        return response()->json([
            'customers' => $getCustomers,
        ], 200);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            Customer::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email, 
                'address' => $request->address, 
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Something went Wrong.', 'response' => $th->getMessage()]);
        }
        return response()->json(['message' => 'Customer Saved.', 'status' => 200]);

    }
}
