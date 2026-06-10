<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function login_post(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Detect remember me
        $remember = $request->has('remember') ? true : false;

        // Attempt login
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ], $remember)) {

            // Check role
            if (Auth::user()->is_role == 1) {

                return redirect()->intended('admin/dashboard');

            } else {

                Auth::logout();
                return redirect()->back()->with('error', 'You are not an admin');
            }

        } else {

            return redirect()->back()->with('error', 'Invalid email or password');
        }
    }

    public function forgot_post(Request $request)
{
    // Validate email
    $request->validate([
        'email' => 'required|email',
    ]);

    // Check if email exists
    $count = User::where('email', $request->email)->count();

    if ($count > 0) {

        // Get the user
        $user = User::where('email', $request->email)->first();

        // Generate token
        $user->remember_token = Str::random(60);
        $user->save();

        // Send email
        Mail::to($user->email)->send(new ForgotPasswordMail($user));

        return redirect()->back()->with('success', 'We have e-mailed your password reset link!');

    } else {

        return redirect()->back()->with('error', 'Email address not found');
    }
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function forgot()
    {
        return view('auth.forgot');
    }
}