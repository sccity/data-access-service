<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
    protected $fillable = [
        'name',
        'token',
        'permissions', // Stored as JSON
        'expires_at'
    ];

    protected $casts = [
        'permissions' => 'array',
        'expires_at' => 'datetime'
    ];

    public function hasPermission(string $endpoint, string $ability): bool
    {
        return isset($this->permissions[$endpoint]) && 
               in_array($ability, $this->permissions[$endpoint]);
    }
} 