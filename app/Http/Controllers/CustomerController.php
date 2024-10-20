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
            $message = '';
            if($request->id){
                $customer = Customer::where('id', $request->id)->first();
                if($customer){
                    $updateArr['first_name'] = $request->first_name;
                    $updateArr['last_name'] = $request->last_name;
                    $updateArr['email'] = $request->email;
                    $updateArr['address'] = $request->address;

                    $customer->update($updateArr);
                    $message = '';
                }
                $message = 'Customer Updated.';
            }else{

                Customer::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email, 
                    'address' => $request->address, 
                ]);
                $message = 'Customer Saved.';
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
        $getCustomer = Customer::where('id', $id)->first();
        if($getCustomer == null){
            return response()->json(['message' => 'Customer not found.', 'status' => 400]);
        }  
        return response()->json(['message' => 'Customer details .', 'status' => 200, 'customer' => $getCustomer]);

    }

    public function destroy($customerId)
    {
        $customer = Customer::where('id', $customerId)->first();
        if($customer == null){
            return response()->json(['status' => 500, 'message' => 'Customer not found.', 'response' => []]);
        }
        $customer->delete();
        return response()->json(['message' => 'Customer Deleted.', 'status' => 200]);
    }
}
