<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTeacher
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $priv = $this->resolvePrivilege($user);

        if ($priv !== 3) {
            abort(403, 'Forbidden. Teacher access only.');
        }

        return $next($request);
    }

    private function resolvePrivilege(object $user): int
    {
        foreach (['priv', 'privillage', 'privilege', 'privilege_id', 'role_id', 'user_type'] as $column) {
            if (isset($user->{$column}) && $user->{$column} !== null) {
                return (int) $user->{$column};
            }
        }

        return 0;
    }
}
