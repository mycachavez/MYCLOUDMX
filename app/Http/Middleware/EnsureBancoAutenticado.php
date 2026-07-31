<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBancoAutenticado
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('auth_banco')) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        return $next($request);
    }
}