<?php

namespace App\Http\Controllers\api\auth;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class UserOrderController extends Controller
{
    public function userOrder(Request $request)
    {
        $request->validate(['uid' => 'required|string',]);

        $uid = $request->input('uid');
        $orders = Order::where('uid',$uid)->with('paymentMethod','item')->orderBy('created_at','desc')->paginate(15);
        return response()->json([
            'status'  => true,
            'message' => 'order list',
            'data'    => $orders->items(),
            'total'   => $orders->total(),
            'current' => $orders->currentPage(),
            'lastpage' => $orders->lastPage(),
            'first'    => $orders->firstItem(),
            'from'     => $orders->firstItem(),
            'perPage'  => $orders->perPage(),
        ]);
    }
}
