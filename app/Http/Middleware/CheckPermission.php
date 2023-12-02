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
    public function handle(Request $request, Closure $next,$role): Response
    {

        $roles =  auth()->user()->roles ;
        $permission = $roles->filter(function($item) use ($role){
           return $item->role_name == $role;
        })->first();
        if(!$permission){
            return response()->json([
                'message' => 'You Do Not Have Permission To Access',
                'file'  => 'CheckPermission'
            ], 401);
        }
        
        return $next($request);
        
        
    }
}