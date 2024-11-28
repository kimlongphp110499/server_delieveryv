<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckCtAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         if (Auth::check() && Auth::user()->is_ct_account) {
            if (!$request->is('reports/summary') && !$request->is('logout')) {
                return redirect()->route('reports.summary');
                // abort(404);
            }
        }

        return $next($request);
    }
}
