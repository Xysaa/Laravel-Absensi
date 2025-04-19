<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
{
    \Log::info('AdminMiddleware is being called for user: ' . (Auth::check() ? Auth::user()->email : 'Guest'));
    if (!Auth::check() || !Auth::user()->is_admin) {
        \Log::info('Access denied: User is not admin or not authenticated');
        abort(403, 'Unauthorized action.');
    }
    return $next($request);
}
}