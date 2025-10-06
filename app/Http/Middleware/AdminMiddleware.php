<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!empty(Auth::check()))
        {
            $user = Auth::user();
            if($user->is_admin == 1 || $user->role === 'lecturer')
            {
                return $next($request);
            }
            else
            {
                Auth::logout();
                return redirect('admin');
            }

        }
        else
        {
            Auth::logout();
            return redirect('admin');
        }

    }
}
