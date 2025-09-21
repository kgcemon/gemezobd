<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index(Request $request){
        $payment_methods = PaymentMethod::where('name', '!=', 'Wallet')->get();
        return response()->json([
            'status' => true,
            'data' => $payment_methods,
        ]);
    }
}
