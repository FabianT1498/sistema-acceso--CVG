<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class BaseReceptionistMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::user()->role->id === 4 || Auth::user()->role->id === 5 || Auth::user()->role->id === 1|| Auth::user()->role->id === 2)
            return $next($request);
        else
            return abort(403, "No puede ingresar no tiene privilegios por ser un " . Auth::user()->role->name);
    }
}
