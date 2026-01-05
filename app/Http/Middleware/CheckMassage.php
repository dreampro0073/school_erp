<?php

namespace App\Http\Middleware;

use Closure,Auth,Session,DB;

use App\Models\User;

class CheckMassage
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

        if(!in_array(4,$service_ids)){
            return redirect('/error');
        }
        return $next($request);
    }

}