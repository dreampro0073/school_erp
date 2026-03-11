<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiTokenUser
{
   public function handle($request, Closure $next)
	{

	    $token = $request->header('Apitoken');
	   
	    if (!$token) {
	        return response()->json([
	            'status' => false,
	            'message' => 'Unauthorized'
	        ], 401);
	    }

	    return $next($request);
	}
}
