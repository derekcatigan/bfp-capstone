<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('profile');

        return view('pages.shared.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        DB::transaction(function () use ($request, $user) {

            $user->update([
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            $user->profile->update([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'suffix' => $request->suffix,
            ]);
        });

        return response()->json(['message' => 'Profile updated']);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed'
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Wrong current password'], 422);
        }

        $user->update([
            'password' => $request->new_password
        ]);

        return response()->json(['message' => 'Password changed']);
    }
}
