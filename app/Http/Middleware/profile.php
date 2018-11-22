<?php

namespace Bahdcasts\Http\Middleware;

use Closure;
use function redirect;

class profile
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
        $user = $request->route()->parameter('user');

        if(auth()->check() && (auth()->user()->id == $user->id)  ){

            return $next($request);
        }

        return redirect('/');

    }
}
