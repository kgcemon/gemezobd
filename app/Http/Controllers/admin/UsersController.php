<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Mail\MoneyReceivedMail;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Handle the search functionality
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('email', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Paginate the results
        $users = $query->latest()->paginate(10);

        // This passes the $users variable to your view
        return view('admin.pages.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'wallet' => 'nullable|numeric|min:0',
            'password' => 'nullable|string|min:8',
        ]);


        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $oldBalance = $user->wallet;
        if (isset($validatedData['wallet'])) {

            $amount = $validatedData['wallet'];

            $user->wallet = $amount;

            if($oldBalance > $amount) {
                WalletTransaction::create([
                    'user_id'   => $user->id,
                    'amount'    => $oldBalance - $amount,
                    'type'      => 'debit',
                    'description' => 'Admin debit balance',
                    'status'    => 1,
                ]);
                try {
                    Mail::to($user->email)->send(new MoneyReceivedMail(
                        $user->name,
                        'TXN-' . strtoupper(rand(10000,10000)),
                        now()->format('d M Y, h:i A'),
                        $amount,
                        url('/')
                    ));
                }catch (\Exception $exception){}

            }else{
                WalletTransaction::create([
                    'user_id'   => $user->id,
                    'amount'    => $amount-$oldBalance,
                    'type'      => 'credit',
                    'description' => 'Admin added menualy your',
                    'status'    => 1,
                ]);
                try {
                    Mail::to($user->email)->send(new MoneyReceivedMail(
                        $user->name,
                        'TXN-' . strtoupper(rand(10000,10000)),
                        now()->format('d M Y, h:i A'),
                        $amount,
                        url('/')
                    ));
                }catch (\Exception $exception){}
            }
        }

        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        return back()->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
