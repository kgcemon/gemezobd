<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->with('error', 'User not found');
        }


        if ($user->role != 'admin') {
            return back()->withErrors([
                'error' => 'Sorry, you are not authorized to access this page.',
            ]);
        }

        if (Hash::check($credentials['password'], $user->password)) {
            return route('admin.dashboard');
        }

        return back()->withErrors([
            'error' => 'Sorry, you are not authorized to access this page.',
        ]);
    }
}
