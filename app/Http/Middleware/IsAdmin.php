<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if (!is_null($user)) {
            if (!$user->hasRole(['Admin'])) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'You are not allowed to access this route']);
                } else {
                    return redirect(route('login'));
                }

            }
            return $next($request);
        } else {
            return redirect(route('login'));
        }
    }
}
