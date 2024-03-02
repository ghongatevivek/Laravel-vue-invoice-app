<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $getInvoice = Invoice::with([
            'customer' => fn ($q) => $q->select('id', 'first_name'),
        ])
        ->when($request->search != null, function($query) use ($request){
            $query->where('number', 'like', '%' . $request->search . '%');
        })
        ->latest('id')->get();

        return response()->json([
            'invoices' => $getInvoice,
        ], 200);
    }
}
