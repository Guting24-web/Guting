<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // Add this line

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            // If user is already authenticated and tries to access login/register, redirect them based on their role
            if ($request->is('login') || $request->is('register')) {
                if (Auth::user()->role === 'admin') {
                    return redirect('/admin/Charedashboard');
                } else {
                    return redirect('/user/Charedashboard');
                }
            }
        }

        return $next($request);
    }
}
