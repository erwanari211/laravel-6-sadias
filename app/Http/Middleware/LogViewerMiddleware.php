<?php

namespace App\Http\Middleware;

use Closure;

class LogViewerMiddleware
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
        $hasAccess = false;

        if (auth()->check()) {
            $user = auth()->user();

            $roles = env('ARCANEDEV_LOGVIEWER_ALLOWED_ROLES');
            $roles = explode(',', $roles);
            $allowedRoles = [];
            foreach ($roles as $role) {
                $allowedRoles[] = trim($role);
            }

            /** @var \App\User|null $user */
            $hasRole = $user->hasAnyRole($allowedRoles);
            if ($hasRole) {
                $hasAccess = true;
            }
        }

        if (!$hasAccess) {
            return abort(403);
        }

        return $next($request);
    }
}
