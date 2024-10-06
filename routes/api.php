<?php

use App\Http\Controllers\{CustomerController, InvoiceController, ProductController};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Invoice
Route::get('/get_all_invoices', [InvoiceController::class, 'index']);
Route::get('/create_invoice', [InvoiceController::class, 'create']);
Route::post('/save-invoice', [InvoiceController::class, 'store']); // store invoice

// Customer
Route::get('/customers', [CustomerController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::post('/save-product', [ProductController::class, 'store']);
