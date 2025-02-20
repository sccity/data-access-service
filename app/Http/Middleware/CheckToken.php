<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiToken;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class CheckToken
{
    public function handle(Request $request, Closure $next)
    {
        // Add debug header to see if middleware is running
        header('X-Debug: CheckToken middleware is running');

        Log::info('CheckToken middleware running', [
            'path' => $request->path(),
            'method' => $request->method(),
            'token' => $request->token,
            'full_url' => $request->fullUrl(),
            'middleware' => $request->route()->middleware(),
            'is_web' => $request->hasSession(),
        ]);

        if (!$request->has('token')) {
            return response()->json([
                'error' => 'No token provided'
            ], 401);
        }

        $token = ApiToken::where('token', $request->token)
                        ->where(function ($query) {
                            $query->whereNull('expires_at')
                                ->orWhere('expires_at', '>', now());
                        })
                        ->first();

        if (!$token) {
            return response()->json([
                'error' => 'Invalid or expired token'
            ], 401);
        }

        // Check endpoint permissions
        $endpoint = $this->getEndpoint($request);
        $ability = $this->getAbility($request);

        Log::info('Checking permissions', [
            'endpoint' => $endpoint,
            'ability' => $ability,
            'token_permissions' => $token->permissions
        ]);

        if (!$token->hasPermission($endpoint, $ability)) {
            return response()->json([
                'error' => 'Token does not have required permissions',
                'required' => [
                    'endpoint' => $endpoint,
                    'ability' => $ability
                ]
            ], 403);
        }

        // Set up auth for the request (using first user for now)
        auth()->setUser(User::first());

        return $next($request);
    }

    private function getEndpoint(Request $request): string
    {
        $path = $request->path();
        // Remove 'api/' prefix if it exists and get the first segment
        $path = str_replace('api/', '', $path);
        $segments = explode('/', $path);
        return $segments[0];
    }

    private function getAbility(Request $request): string
    {
        $path = $request->path();
        $segments = explode('/', ltrim($path, 'api/'));

        // If it's a GET request, check if it's for a single resource
        if ($request->method() === 'GET') {
            // If there's an ID segment, it's a 'view' operation
            return isset($segments[1]) ? 'view' : 'viewAny';
        }

        return match ($request->method()) {
            'POST' => 'create',
            'PUT', 'PATCH' => 'update',
            'DELETE' => 'delete',
            default => 'view'
        };
    }
}
