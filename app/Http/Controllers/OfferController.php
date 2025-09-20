<?php

namespace App\Http\Controllers;

use App\Mail\OfferMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OfferController extends Controller
{
    public function index()
    {
        return view('admin.offer-send');
    }

    public function send(Request $request)
    {
        try {
            $request->validate([
                'discount' => 'required|numeric',
                'expiryDate' => 'required|date',
            ]);

            $users = User::select('id', 'name', 'email')->get();
            $success = 0;
            $failed = 0;

            foreach ($users as $user) {
                try {
                    Mail::to($user->email)->send(new OfferMail(
                        $user->name,
                        $request->discount,
                        '57527',
                        $request->expiryDate,
                        url('/')
                    ));
                    $success++;
                } catch (\Exception $e) {
                    $failed++;
                }
            }

             return back()->with('status', "✅ মোট {$users->count()} জনকে মেইল পাঠানো হয়েছে।
            সফল: {$success}, ব্যর্থ: {$failed}");
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
    }
}
