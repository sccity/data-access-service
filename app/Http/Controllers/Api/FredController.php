<?php

namespace App\Http\Controllers\Api;

use App\Models\WarehouseDatabase\Fred;
use Orion\Http\Controllers\Controller;
use App\Http\Resources\FredResource;
use Illuminate\Http\Request;

class FredController extends Controller
{
    /**
     * Fully-qualified model class name
     */
    protected $model = Fred::class;

    protected $resource = FredResource::class;

    // Move token check to middleware
    protected $middlewares = [
        'check.token',
        'auth:api'
    ];

    /**
     * Define which abilities should be used for each operation
     */
    protected function abilities(): array
    {
        return [
            'index' => 'viewAny',    // GET /api/fred
            'show' => 'view',        // GET /api/fred/{id}
            'create' => 'create',    // POST /api/fred
            'update' => 'update',    // PATCH/PUT /api/fred/{id}
            'destroy' => 'delete'    // DELETE /api/fred/{id}
        ];
    }

    /**
     * Determine if the user is authorized to perform an action
     */
    public function authorize(string $ability, mixed $arguments = []): bool
    {
        // Get the authenticated user
        $user = auth()->user();
        
        // Check if user has specific permissions
        return match ($ability) {
            'viewAny', 'view' => $user->email === 'allowed@example.com',  // Only this email can view
            'cpi' => $user->email === 'cpi@example.com',  // Only this email can access CPI
            default => false
        };
    }

    public function cpi(Request $request)
    {
        // Check authorization for CPI endpoint
        if (!$this->authorize('cpi', [])) {
            abort(403, 'Unauthorized to access CPI data');
        }

        $cpi = Fred::select('date', 'cpi')->get();
        return FredResource::collection($cpi);
    }
} 