<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    /**
     * Display Dashboard
     */
    public function dashboard()
    {
        $data['header_title'] = 'Dashboard';

        return view('admin.dashboard.dashboard', $data);
    }

    /**
     * Display My Account Page
     */
    public function my_account()
    {
        $data['header_title'] = 'My Account';
        $data['getRecord'] = Auth::user();

        return view('admin.dashboard.my_account', $data);
    }

    /**
     * Update My Account
     */
    public function update_account(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/')->with('error', 'Please login first.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user->name = trim($request->name);
        $user->email = trim($request->email);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'My Account updated successfully.');
    }
}
