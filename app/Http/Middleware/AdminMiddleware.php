<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->hasRole('Admin')) {
            return $next($request);
        }

        // For API
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthorized. Admin only.'], 403);
        }

        // For web
        return redirect()->route('admin.login');
    }
}
