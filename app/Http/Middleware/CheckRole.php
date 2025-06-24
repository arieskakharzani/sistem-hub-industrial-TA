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
        // Cek apakah user sudah login
        if (!Auth::check()) {
            // Untuk API request
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Please login first.'
                ], 401);
            }
            // Untuk web request
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.'
                ], 401);
            }
            return redirect()->route('login');
        }

        // Ambil role user
        $userRole = $user->role ?? null;

        if (!$userRole) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'User role not defined'
                ], 403);
            }
            abort(403, 'User role not defined');
        }

        // Check if user role matches any of the required roles
        $hasAccess = false;
        foreach ($roles as $role) {
            // Handle comma separated roles
            if (strpos($role, ',') !== false) {
                $roleArray = array_map('trim', explode(',', $role));
                if (in_array($userRole, $roleArray)) {
                    $hasAccess = true;
                    break;
                }
            } else {
                if ($userRole === $role) {
                    $hasAccess = true;
                    break;
                }
            }
        }

        if (!$hasAccess) {
            $errorMessage = 'Unauthorized access - Your role (' . $userRole . ') does not have permission to access this page';

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 403);
            }
            abort(403, $errorMessage);
        }

        return $next($request);
    }
}
