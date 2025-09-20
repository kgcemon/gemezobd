<?php

namespace App\Http\Controllers;

use App\Mail\OrderDeliveredMail;
use App\Mail\OrderRefundMail;
use App\Models\Code;
use App\Models\Order;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class WebHooksController extends Controller
{
    public function OrderUpdate(Request $request)
    {
        $data = $request->input();

        if (!$data || !isset($data['uid'])) {
            return response()->json(['status' => false, 'message' => 'Invalid data'], 400);
        }

        $status = $data['status'] ?? null;
        $message = $data['message'] ?? null;
        $uid = $data['uid'];

        $order = Order::where('order_note', $uid)->first();
        $user = $order ? User::find($order->user_id) : null;

        if ($order) {
            if ($status == 'true') {
                $order->status = 'delivered';
                $order->save();
                if ($user) {
                    try {
                        Mail::to($user->email)->send(new OrderDeliveredMail(
                            $user->name,
                            $order->id,
                            now(),
                            $order->total,
                            url('/thank-you/'.$order->uid),
                            $order->item->name ?? "",
                            $order->customer_data ?? "",
                        ));
                    } catch (\Exception $e) {}
                }
            } else {
                $order->status = 'Delivery Running';
                $usedCode = Code::where('uid', $uid)->first();
                if ($usedCode) {
                    $usedCode->active = false;
                    $usedCode->note = $message ?? null;
                    $usedCode->save();
                }
            }

            if ($message != null) {
                $order->order_note = $message;
                if (Str::contains($message, 'Invalid player ID')){
                    $order->status = 'refunded';
                    if ($user != null) {
                        $user->increment('wallet', $order->total);
                        $user->save();
                        WalletTransaction::create([
                            'user_id'   => $user->id,
                            'amount'    => $order->total,
                            'type'      => 'credit',
                            'description' => 'Refund to Wallet Order id: ' . $order->id,
                            'status'    => 1,
                        ]);

                        $denom = $order->item->denom ?? '';
                        $denoms = explode(',', $denom);

                        foreach ($denoms as $d) {
                            Code::where('order_id', $order->id)
                                ->where('denom', $d)->update(['status' => 'unused']);
                        }
                        try {
                            Mail::to($user->email)->send(new OrderRefundMail(
                                $user->name,
                                $order->id,
                                now()->format('d M Y, h:i A'),
                                $order->total,
                                url('/order/'.$order->uid)
                            ));

                        }catch (\Exception $e) {}

                    }
                }
            }

            $order->save();

            return response()->json(['status' => true, 'message' => 'Order updated']);
        } else {
            return response()->json(['status' => false, 'message' => 'Order not found'], 404);
        }
    }
}
