<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id'     => 'required|exists:products,id',
            'quantity'       => 'required|integer|min:1',
            'items_id'       => 'sometimes|exists:items,id',
            'customer_data'  => 'required|string|max:255',
            'others'         => 'nullable|string',
            'method_id'      => 'required|integer',
            'number'         => 'nullable|string|min:1|max:13',
            'transaction_id' => 'nullable|string|max:14|unique:orders,transaction_id',
        ];
    }

    public function withValidator(\Illuminate\Validation\Validator $validator)
    {
        $validator->sometimes('name', 'required|string|max:255', function () {
            return auth()->guest();
        });

        $validator->sometimes('phone', 'required|string|max:20', function () {
            return auth()->guest();
        });

        $validator->sometimes('email', 'nullable|email|max:255', function () {
            return auth()->guest();
        });
    }

    public function messages(): array
    {
        return [
            'product_id.required'     => 'product_id প্রোডাক্ট আইডি দিতে হবে।',
            'product_id.exists'       => 'product_id এই প্রোডাক্টটি খুঁজে পাওয়া যায়নি।',
            'quantity.required'       => 'পরিমাণ দিতে হবে।',
            'quantity.integer'        => 'পরিমাণ একটি সংখ্যা হতে হবে।',
            'customer_data.required'  => 'গ্রাহকের তথ্য দিতে হবে।',
            'method_id.required'      => 'পেমেন্ট মেথড নির্বাচন করতে হবে।',
            'transaction_id.unique'   => 'এই লেনদেন আইডি ইতিমধ্যে ব্যবহার করা হয়েছে।',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => $validator->errors()->first(),
            'errors' => $validator->errors()
        ], 422));
    }
}
