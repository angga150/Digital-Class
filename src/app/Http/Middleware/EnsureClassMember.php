<?php

namespace App\Http\Middleware;

use App\Support\CurrentClass;
use Closure;
use Illuminate\Http\Request;

class EnsureClassMember
{
    public function handle(Request $request, Closure $next)
    {
        if (! CurrentClass::membership($request->user())) {
            return redirect()->route('join');
        }

        return $next($request);
    }
}
