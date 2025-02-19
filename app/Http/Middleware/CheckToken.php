<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiToken;

class CheckToken
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('token')) {
            $token = ApiToken::where('token', $request->token)
                           ->where(function ($query) {
                               $query->whereNull('expires_at')
                                   ->orWhere('expires_at', '>', now());
                           })
                           ->first();

            if (!$token) {
                abort(401, 'Invalid or expired token');
            }

            // Check endpoint permissions
            $endpoint = $this->getEndpoint($request);
            $ability = $this->getAbility($request);

            if (!$token->hasPermission($endpoint, $ability)) {
                abort(403, 'Token does not have required permissions');
            }

            $request->headers->set('Authorization', 'Bearer ' . $request->token);
        }
        
        return $next($request);
    }

    private function getEndpoint(Request $request): string
    {
        // Extract endpoint from path (e.g., 'fred' or 'fred/cpi')
        return trim($request->path(), 'api/');
    }

    private function getAbility(Request $request): string
    {
        // Map HTTP method to ability
        return match ($request->method()) {
            'GET' => $request->route()->getName() === 'show' ? 'view' : 'viewAny',
            'POST' => 'create',
            'PUT', 'PATCH' => 'update',
            'DELETE' => 'delete',
            default => 'view'
        };
    }
} 