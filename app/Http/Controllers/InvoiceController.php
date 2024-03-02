<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $getInvoice = Invoice::with([
            'customer' => fn ($q) => $q->select('id', 'first_name'),
        ])->latest('id')->get();

        return response()->json([
            'invoices' => $getInvoice,
        ], 200);
    }
}
