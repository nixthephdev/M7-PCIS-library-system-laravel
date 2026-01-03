<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

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
}