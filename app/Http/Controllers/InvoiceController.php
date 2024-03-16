<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceCreateRequest;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function create()
    {
        $sampleFormData =  [
            'date' => date('Y-m-d'),
            'due_date' => date('Y-m-d'),
            'discount' => 0,
            'invoice_no' => rand(99999, 999999),
            'terms_and_condition' => 'Tax added in total'
        ];
        return response()->json(['form_data' => $sampleFormData], 200);
    }

    // Save invoice with invoice items 
    public function store(InvoiceCreateRequest $request)
    {
        DB::beginTransaction();
        try {
            // Save invoice into table
            $invoiceArr = $request->only('customer_id', 'date', 'due_date', 'discount', 'reference', 'sub_total', 'total', 'terms_and_condition');
            $invoiceArr['number'] = $request->invoice_no;
            $invoiceCreate = Invoice::create($invoiceArr);

            // Save invoice items info
            if ($invoiceCreate) {
                $invoiceItems = $request->cart_items;
                if ($invoiceItems) {
                    $invoiceItemArr = [];
                    foreach ($invoiceItems as $key => $item) {
                        $invoiceItemArr[] = [
                            'invoice_id' => $invoiceCreate->id,
                            'product_id' => $item['id'],
                            'unit_price' => $item['price'],
                            'quantity' => $item['quantity'],
                            'created_at' => now()->format('Y-m-d H:i:s'),
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                        ];
                    }

                    if (!empty($invoiceItemArr)) {
                        InvoiceItem::insert($invoiceItemArr);
                    }
                }
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Something went Wrong.', 'response' => $th->getMessage()]);
        }
        return response()->json(['message' => 'Invoice Saved.', 'status' => 200]);
    }
}
