<?php

namespace App\Http\Middleware;

use Closure,Auth,Session,DB;

use App\Models\User;

class CheckRecliner
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
        $service_ids = User::getServiceIds();

        if(!in_array(7,$service_ids)){
            return redirect('/error');
        }
        return $next($request);
    }

}