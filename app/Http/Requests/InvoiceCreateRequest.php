<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => 'required',
            'due_date' => 'required',
            'discount' => 'required',
            'sub_total' => 'required',
            'total' => 'required',
            'customer_id' => 'required',
            'invoice_no' => 'required',
            'reference' => 'nullable',
            'terms_and_condition' => 'required',
            'cart_items.*' => 'required|array',
            'cart_items.*.id' => 'required',
            'cart_items.*.item_code' => 'required',
            'cart_items.*.description' => 'required',
            'cart_items.*.quantity' => 'required',
        ];
    }
}
