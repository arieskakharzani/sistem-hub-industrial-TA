<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Pastikan user object ada
        if (!$user) {
            return redirect()->route('login');
        }

        // Manual check role instead of using hasRole method
        $userRole = $user->getRole();

        if (!$userRole) {
            abort(403, 'User role not defined');
        }

        // Check if user role matches any of the required roles
        $hasAccess = false;
        foreach ($roles as $role) {
            // Handle comma-separated roles
            if (strpos($role, ',') !== false) {
                $roleArray = array_map('trim', explode(',', $role));
                if (in_array($userRole, $roleArray)) {
                    $hasAccess = true;
                    break;
                }
            } else {
                // Single role check
                if ($userRole === $role) {
                    $hasAccess = true;
                    break;
                }
            }
        }

        if (!$hasAccess) {
            abort(403, 'Unauthorized access - Your role (' . $userRole . ') does not have permission to access this page');
        }

        return $next($request);
    }
}
