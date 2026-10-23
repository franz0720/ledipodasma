<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (strtolower(trim((string) ($user->role ?? ''))) !== 'super_admin') {
            abort(403, 'Unauthorized. Super Admin access only.');
        }

        return $next($request);
    }
}
