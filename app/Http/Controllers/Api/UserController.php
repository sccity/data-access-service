<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Orion\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    /**
     * Fully-qualified model class name
     */
    protected $model = User::class;

    protected $middlewares = ['auth:sanctum'];

    protected $resource = UserResource::class;
} 