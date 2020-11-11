<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ReceptionistMiddleware
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
        if(Auth::user()->role->name === "RECEPCIONISTA" || Auth::user()->role->name === "ADMIN" || Auth::user()->role->name === "SUPERADMIN")
            return $next($request);
        else
            return abort(403, "No puede ingresar no tiene privilegios por ser un " . Auth::user()->role->name);
    }
}
