<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login dan is_admin == true
        if (auth()->check() && auth()->user()->is_admin) {
            return $next($request); // Lanjut ke route
        }

        // Kalau bukan admin, bisa redirect atau abort
        return abort(403, 'Kamu tidak memiliki akses ke halaman ini.');
    }
}
