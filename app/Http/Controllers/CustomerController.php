<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $getCustomers = Customer::latest('id')->get();

        return response()->json([
            'customers' => $getCustomers,
        ], 200);
    }
}
