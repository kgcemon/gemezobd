<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\PaymentSms;
use App\Models\Product;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepositController extends Controller
{
    public function userHistory(Request $request)
    {
        $user = $request->user();
        $history = WalletTransaction::where('user_id', $user->id)->orderBy('id', 'desc')->get();
        return response()->json([
            'status' => true,
            'history' => $history,
        ]);
    }

    public function depositStore(Request $request)
    {
        $validated = $request->validate([
            'amount'         => 'required|numeric|min:10',
            'payment_id'     => 'required|integer',
            'transaction_id' => 'required|string|min:5',
            'payment_number' => 'nullable|string|min:10',
        ]);

        try {
            $user   = $request->user();
            $amount = (int) $validated['amount'];

            return DB::transaction(function () use ($user, $validated, $amount) {
                $status = 'hold';

                if (Order::where('transaction_id', $validated['transaction_id'])->exists()) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'This transaction ID is already used.',
                    ], 409);
                }

                // Auto match with PaymentSms
                $paySMS = PaymentSms::where('trxID', $validated['transaction_id'])
                    ->where('amount', '>=', $amount)
                    ->where('status', 0)->first();

                if ($paySMS) {
                    $status        = 'delivered';
                    $trxID         = $paySMS->trxID;
                    $user->wallet += $amount;
                    $paySMS->status = 1;
                    WalletTransaction::create([
                        'user_id'   => $user->id,
                        'amount'    => $amount,
                        'type'      => 'credit',
                        'description' => 'Deposit to Wallet' . $trxID,
                        'status'    => 1,
                    ]);
                    $paySMS->save();
                    $user->save();
                }else {
                    if (empty($validated['transaction_id']) || empty($validated['payment_number'])) {
                        return response()->json([
                            'status'  => false,
                            'message' => 'Transaction ID and payment number are required for this payment method.',
                        ], 422);
                    }
                }

                // Wallet product check
                $product = Product::where('name', 'Wallet')->first();
                if (!$product) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Deposit temporarily unavailable.',
                    ], 503);
                }

                // Order create
                $order =  Order::create([
                    'user_id'        => $user->id,
                    'name'           => $user->name,
                    'email'          => $user->email,
                    'product_id'     => $product->id,
                    'quantity'       => 1,
                    'total'          => $amount,
                    'customer_data'  => "Deposit ৳{$amount} from {$validated['payment_number']}",
                    'payment_method' => $validated['payment_id'],
                    'transaction_id' => $trxID ?? $validated['transaction_id'],
                    'number'         => $validated['payment_number'],
                    'status'         => $status,
                ]);

                return response()->json([
                    'status'  => true,
                    'message' => $status === 'delivered'
                        ? 'Deposit successful and added to wallet.'
                        : 'Deposit request submitted. Waiting for confirmation.',
                    'order'    => $order,
                ]);
            });
        } catch (\Exception $exception) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong: ' . $exception->getMessage(),
            ], 500);
        }
    }
}
