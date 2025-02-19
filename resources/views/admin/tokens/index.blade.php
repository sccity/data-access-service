<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            API Tokens
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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

                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left">Name</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left">Permissions</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left">Expires At</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tokens as $token)
                                <tr>
                                    <td class="px-6 py-4 border-b border-gray-200">{{ $token->name }}</td>
                                    <td class="px-6 py-4 border-b border-gray-200">
                                        @foreach($token->permissions as $endpoint => $abilities)
                                            <div class="mb-1">
                                                <span class="font-semibold">{{ $endpoint }}:</span>
                                                {{ implode(', ', $abilities) }}
                                            </div>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 border-b border-gray-200">
                                        {{ $token->expires_at ? $token->expires_at->format('Y-m-d') : 'Never' }}
                                    </td>
                                    <td class="px-6 py-4 border-b border-gray-200">
                                        <form action="{{ route('tokens.destroy', $token) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
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
</x-app-layout> 