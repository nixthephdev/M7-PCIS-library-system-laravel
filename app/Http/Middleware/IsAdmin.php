<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
{
    // Check if user is logged in AND is an admin
    if (auth()->check() && auth()->user()->role === 'admin') {
        return $next($request);
    }

    // If not admin, log them out and redirect to home
    auth()->logout();
    return redirect()->route('login')->with('error', 'Access Denied. Admins only.');
}
}
