<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Orion\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Fully-qualified model class name
     */
    protected $model = User::class;

    protected $resource = UserResource::class;

    /**
     * Determine if the user is authorized to perform an action.
     */
    public function authorize(string $ability, mixed $arguments = []): bool
    {
        // Anyone can view user data
        if (in_array($ability, ['viewAny', 'view'])) {
            return true;
        }

        // Only admin can modify
        if (in_array($ability, ['create', 'update', 'delete'])) {
            return auth()->user()->email === 'admin@santaclarautah.gov';
        }

        return false;
    }
} 