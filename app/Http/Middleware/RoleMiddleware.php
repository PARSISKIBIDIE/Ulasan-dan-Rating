<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!session()->has('user_id')) {
            return redirect('/login');
        }

        if (session('role') != $role) {
            return redirect('/');
        }

        return $next($request);
    }
}