<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
    protected $fillable = [
        'name',
        'token',
        'permissions', // Stored as JSON
        'expires_at',
        'user_id'
    ];

    protected $casts = [
        'permissions' => 'array',
        'expires_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hasPermission(string $endpoint, string $ability): bool
    {
        // Ensure permissions is an array
        $permissions = is_array($this->permissions) ? $this->permissions : [];
        
        // Check if endpoint exists in permissions
        if (!isset($permissions[$endpoint])) {
            return false;
        }

        // Check if ability exists in endpoint permissions
        $endpointPermissions = $permissions[$endpoint];
        if (!is_array($endpointPermissions)) {
            return false;
        }

        return in_array($ability, $endpointPermissions);
    }
} 