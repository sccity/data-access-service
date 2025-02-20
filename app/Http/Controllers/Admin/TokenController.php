<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiToken;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TokenController extends Controller
{
    public function index()
    {
        $tokens = ApiToken::all();
        return view('admin.tokens.index', compact('tokens'));
    }

    public function createForm()
    {
        $availableEndpoints = [
            'cpi' => ['viewAny', 'view', 'create', 'update', 'delete'],
            'users' => ['viewAny', 'view', 'create', 'update', 'delete'],
        ];
        
        return view('admin.tokens.create', compact('availableEndpoints'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'permissions' => 'required|array',
            'expires_at' => 'nullable|date'
        ]);

        $token = ApiToken::create([
            'name' => $validated['name'],
            'token' => Str::random(64),
            'permissions' => $validated['permissions'],
            'expires_at' => $validated['expires_at']
        ]);

        return redirect()->route('tokens.index')
            ->with('success', 'Token created successfully. Please copy your token: ' . $token->token);
    }

    public function destroy(ApiToken $token)
    {
        $token->delete();
        return redirect()->route('tokens.index')
            ->with('success', 'Token deleted successfully');
    }
} 