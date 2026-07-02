<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\ForgotPasswordMail;

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
        $remember = $request->has('remember');

        // Check if user exists, is active, and is not deleted first
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Email address not registered.');
        }

        if ($user->is_deleted == 1) {
            return redirect()->back()->with('error', 'This account has been deleted.');
        }

        // Attempt login
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ], $remember)) {

            // User is authenticated, check role
            if (Auth::user()->is_role == 1 || Auth::user()->is_role == 2) {
                \App\Models\ActivityLogsModel::log('User logged in successfully');
                return redirect()->intended('admin/dashboard');
            } else {
                Auth::logout();
                return redirect()->back()->with('error', 'Unauthorized role.');
            }

        } else {
            return redirect()->back()->with('error', 'Invalid password.');
        }
    }

    public function forgot()
    {
        return view('auth.forgot');
    }

    public function forgot_post(Request $request)
    {
        // Validate email
        $request->validate([
            'email' => 'required|email',
        ]);

        // Check if active & non-deleted user exists with this email
        $user = User::where('email', $request->email)
                    ->where('is_deleted', 0)
                    ->first();

        if ($user) {
            // Generate token
            $user->remember_token = Str::random(60);
            $user->save();

            // Send email
            Mail::to($user->email)->send(new ForgotPasswordMail($user));

            return redirect()->back()->with('success', 'We have e-mailed your password reset link!');
        } else {
            return redirect()->back()->with('error', 'Email address not found or inactive.');
        }
    }

    public function resetPassword($token)
    {
        $user = User::where('remember_token', $token)
                    ->where('is_deleted', 0)
                    ->first();

        if ($user) {
            return view('auth.reset', compact('user', 'token'));
        } else {
            return redirect('/')->with('error', 'Reset token is invalid or expired.');
        }
    }

    public function resetPasswordPost($token, Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::where('remember_token', $token)
                    ->where('is_deleted', 0)
                    ->first();

        if ($user) {
            $user->password = Hash::make($request->password);
            $user->remember_token = Str::random(60); // Invalidate token after use
            $user->save();

            return redirect('/')->with('success', 'Password reset successfully. Please login with your new password.');
        } else {
            return redirect('/')->with('error', 'Reset token is invalid or expired.');
        }
    }

    public function logout(Request $request)
    {
        \App\Models\ActivityLogsModel::log('User logged out');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}