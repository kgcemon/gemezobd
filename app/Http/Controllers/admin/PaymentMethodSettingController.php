<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentMethodSettingController extends Controller
{
    // ✅ All Payment Methods List
    public function index()
    {
        $methods = PaymentMethod::latest()->paginate(10);
        return view('admin.payment.payment', compact('methods'));
    }

    // ✅ Store New Payment Method
    public function store(Request $request)
    {
        $request->validate([
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'method' => 'required|string|max:100',
            'description' => 'nullable|string',
            'number' => 'required|string|max:50',
        ]);

        try {
            $iconUrl = null;
            if ($request->hasFile('icon')) {
                // ফাইলকে storage/app/public/payment_methods এ সেভ করবে
                $path = $request->file('icon')->store('payment_methods', 'public');

                // full URL তৈরি হবে
                $iconUrl = asset('storage/' . $path);
            }

            PaymentMethod::create([
                'icon' => $iconUrl, // এখানে full URL save হবে
                'method' => $request->input('method'),
                'description' => $request->input('description'),
                'number' => $request->input('number'),
                'status' => 1,
            ]);

            return back()->with('success', 'Payment method added successfully!');
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'method' => 'required|string|max:100',
            'description' => 'nullable|string',
            'number' => 'required|string|max:50',
            'status' => 'nullable|in:0,1',
        ]);

        $method = PaymentMethod::findOrFail($id);

        $iconUrl = $method->icon; // পুরোনো ইমেজ ধরে রাখা হলো

        if ($request->hasFile('icon')) {
            // পুরোনো ইমেজ ডিলিট
            if ($method->icon && str_contains($method->icon, '/storage/')) {
                $oldPath = str_replace(asset('storage') . '/', '', $method->icon);
                Storage::disk('public')->delete($oldPath);
            }

            // নতুন ইমেজ আপলোড
            $path = $request->file('icon')->store('payment_methods', 'public');
            $iconUrl = asset('storage/' . $path);
        }

        // ডাটাবেজ আপডেট
        $method->update([
            'icon' => $iconUrl,
            'method' => $request->input('method'),
            'description' => $request->input('description'),
            'number' => $request->input('number'),
            'status' => $request->input('status', 1),
        ]);

        return back()->with('success', 'Payment method updated successfully!');
    }


    // ✅ Delete Payment Method
    public function destroy($id)
    {
        $method = PaymentMethod::findOrFail($id);
        $method->delete();

        return back()->with('success', 'Payment method deleted successfully!');
    }

    // ✅ Toggle Status
    public function toggleStatus($id)
    {
        $method = PaymentMethod::findOrFail($id);
        $method->status = $method->status ? 0 : 1;
        $method->save();

        return back()->with('success', 'Status updated successfully!');
    }

    // ✅ Copy Number (Ajax based)
    public function copyNumber($id)
    {
        $method = PaymentMethod::findOrFail($id);
        return response()->json([
            'number' => $method->number
        ]);
    }
}
