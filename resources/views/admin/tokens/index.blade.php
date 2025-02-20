<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            API Tokens
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[95%] mx-auto px-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Manage Tokens</h3>
                        <a href="{{ route('tokens.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Create New Token
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full table-fixed">
                            <thead>
                                <tr>
                                    <th class="w-1/6 px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="w-1/4 px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Token</th>
                                    <th class="w-2/5 px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">API Endpoints</th>
                                    <th class="w-1/12 px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expires</th>
                                    <th class="w-1/12 px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($tokens as $token)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $token->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="font-mono text-sm text-gray-600 break-all">{{ $token->token }}</span>
                                                <button onclick="copyToClipboard('{{ $token->token }}')" 
                                                        class="ml-2 text-blue-600 hover:text-blue-800 flex-shrink-0">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                              d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="space-y-3">
                                                @foreach($token->permissions as $endpoint => $abilities)
                                                    <div class="p-3 bg-gray-50 rounded-lg mb-3">
                                                        <div class="font-medium text-gray-700">{{ $endpoint }}</div>
                                                        @foreach($abilities as $ability)
                                                            <div class="mt-2 group relative">
                                                                <div class="text-sm text-gray-600 mb-1">{{ $ability }}:</div>
                                                                <code class="text-sm bg-gray-100 px-3 py-2 rounded block overflow-x-auto">
                                                                    @switch($ability)
                                                                        @case('viewAny')
                                                                            {{ url("/api/{$endpoint}?token={$token->token}") }}
                                                                            @break
                                                                        @case('view')
                                                                            {{ url("/api/{$endpoint}/{id}?token={$token->token}") }}
                                                                            @break
                                                                        @case('create')
                                                                            {{ url("/api/{$endpoint}?token={$token->token}") }} (POST)
                                                                            @break
                                                                        @case('update')
                                                                            {{ url("/api/{$endpoint}/{id}?token={$token->token}") }} (PUT/PATCH)
                                                                            @break
                                                                        @case('delete')
                                                                            {{ url("/api/{$endpoint}/{id}?token={$token->token}") }} (DELETE)
                                                                            @break
                                                                    @endswitch
                                                                </code>
                                                                <button onclick="copyToClipboard(this.previousElementSibling.textContent)"
                                                                        class="absolute right-2 top-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                                    <svg class="w-4 h-4 text-gray-500 hover:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $token->expires_at ? $token->expires_at->format('Y-m-d') : 'Never' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <form action="{{ route('tokens.destroy', $token) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Copied to clipboard!');
        });
    }
    </script>
</x-app-layout> 