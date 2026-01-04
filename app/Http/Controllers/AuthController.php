<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{

    public function showProfile()
    {
        return view('profile');
    }

    // Update Profile Logic
    public function updateProfile(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => ['required', 'email', \Illuminate\Validation\Rule::unique('users')->ignore($user->id)],
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        'current_password' => 'nullable|required_with:new_password',
        'new_password' => 'nullable|min:8|confirmed',
    ]);

    // 1. Update Basic Info
    $user->name = $request->name;
    $user->email = $request->email;

    // 2. Handle Avatar Uploadresources/views/profile.blade.php
    if ($request->hasFile('avatar')) {
        // Delete old avatar if exists (optional cleanup)
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        
        // Store new file in 'avatars' folder inside 'public' disk
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $path;
    }

    // 3. Update Password
    if ($request->filled('new_password')) {
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match.']);
        }
        $user->password = Hash::make($request->new_password);
    }

    $user->save();

    return back()->with('success', 'Profile updated successfully!');
}
    // Show Login Form
    public function showLogin() {
        return view('login');
    }

    // Process Login
    public function login(Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        
        // CHECK ROLE
        if (Auth::user()->role !== 'admin') {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Access restricted to Administrators only.',
            ]);
        }

        $request->session()->regenerate();
        return redirect()->route('dashboard')->with('success', 'Welcome back, Admin!');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
}

    // Logout
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // 1. Show the Forgot Password View
    public function showForgotPassword()
    {
        return view('forgot-password');
    }

    // 2. Handle the Form Submission (Send Link)
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Send link using the 'log' driver configured in .env
        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Reset link sent! Check your logs.');
        }

        return back()->withErrors(['email' => __($status)]);
    }

    // 3. Show the Reset Password Form (After clicking link)
    public function showResetForm(Request $request, $token = null)
    {
        return view('reset-password')->with(['token' => $token, 'email' => $request->email]);
    }

    // 4. Handle the Password Update
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Password has been reset!');
        }

        return back()->withErrors(['email' => __($status)]);
    }
}