<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderDeliveredMail;
use App\Mail\OrderFailedMail;
use App\Models\Order;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

        if ($request->filled('filter')) {
            $query->where('status', $request->filter);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('id', $searchTerm)
                    ->orWhere('name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('phone', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('transaction_id', 'LIKE', "%{$searchTerm}%");
            });
        }

        $orders = $query->orderByDesc('id')->paginate(10)->appends($request->all());
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        // Fetch the order, optionally with related user/product/item data
        $order = Order::findOrFail($id);

        // Pass the order to the Blade view
        return view('admin.orders.view', compact('order'));
    }


    public function update(Request $request, Order $order)
    {
        try {
            // ✅ Validate request
            $validated = $request->validate([
                'status' => 'required|in:hold,processing,Delivery Running,delivered,cancelled,refunded',
                'order_note' => 'nullable|string|max:500',
            ]);

            // পুরানো status store করা হলো condition check করার জন্য
            $previousStatus = $order->status;

            // ✅ Order note update
            if (!empty($validated['order_note'])) {
                $order->order_note = $validated['order_note'];
            }

            // ✅ Update status
            $order->status = $validated['status'];

            // ✅ Wallet handling
            $user = User::find($order->user_id);


            if ($order->product->name === 'Wallet') {
                // Wallet type order → Add balance only when moving to delivered
                if ($previousStatus == 'hold' || $previousStatus == 'processing') {
                    if ($validated['status'] == 'delivered') {
                        $user->increment('wallet', $order->total);
                        WalletTransaction::create([
                            'user_id'   => $user->id,
                            'amount'    => $order->total,
                            'type'      => 'credit',
                            'description' => 'Deposit to Wallet order ID' . $order->id,
                            'status'    => 1,
                        ]);
                    }
                }
            } else if ($validated['status'] === 'refunded' && $order->user->id != null) {
                // Non-wallet product → Refund case
                if ($previousStatus === 'processing' || $previousStatus === 'Delivery Running') {
                    $user->increment('wallet', $order->total);
                    WalletTransaction::create([
                        'user_id'   => $user->id,
                        'amount'    => $order->total,
                        'type'      => 'credit',
                        'description' => 'Refund to Wallet Order id: ' . $order->id,
                        'status'    => 1,
                    ]);
                }
            }

            if ($validated['status'] === 'delivered' && $user) {
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
            if ($validated['status'] === 'cancelled' && $user) {
                try {
                    Mail::to($user->email)->send(new OrderFailedMail(
                        $user->name,
                        $order->id,
                        now()->format('d M Y, h:i A'),
                        $order->total,
                        url('/')
                    ));
                }catch (\Exception $e) {
                    back()->with('error', $e->getMessage());
                }
            }


            // ✅ Save order
            $order->save();

            return redirect()
                ->route('admin.orders.index')
                ->with('success', 'Order status updated successfully.');

        } catch (\Throwable $exception) {

            return back()->with('error', 'Failed to update order: ' . $exception->getMessage());
        }
    }


    public function editFrom($id){

        $order = Order::findOrFail($id);

        $statuses = ['hold', 'processing', 'Delivery Running', 'delivered', 'cancelled'];

        return view('admin.orders.edit', compact('order', 'statuses'));
    }

    public function edit(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);

            // সব field update
            $order->update($request->input());

            return redirect()->route('admin.orders.index')
                ->with('success', '✅ Order updated successfully.');
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
    }


    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|string',
            'order_ids' => 'required|array'
        ]);

        $orders = Order::whereIn('id', $request->order_ids);

        switch ($request->action) {
            case 'delivered':
                $orders->update(['status' => 'delivered']);
                break;
            case 'processing':
                $orders->update(['status' => 'processing']);
                break;
            case 'cancelled':
                $orders->update(['status' => 'cancelled']);
                break;
            case 'delete':
                $orders->delete();
                break;
        }

        return redirect()->back()->with('success', 'Bulk action applied successfully.');
    }

}
