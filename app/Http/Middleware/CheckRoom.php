<?php

namespace App\Http\Middleware;

use Closure,Auth,Session,DB;

use App\Models\User;

class CheckRoom
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

        if(!in_array(8,$service_ids)){
            return redirect('/error');
        }
        return $next($request);
    }

}