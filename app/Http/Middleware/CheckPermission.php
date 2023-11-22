<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$role = null): Response
    {
        $roles =  auth()->user()->roles ;
        $desired_object = $roles->filter(function($item) {
           return $item->role_name == $role;
        })->first();
        if($role){
            return $next($request);
        }

        return response()->json([
            'error' => 'Provide proper details',
        ], 401);
        
    }
}