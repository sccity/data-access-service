<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create API Token
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('tokens.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                                Token Name
                            </label>
                            <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Permissions
                            </label>
                            @foreach($availableEndpoints as $endpoint => $abilities)
                                <div class="mb-4">
                                    <h4 class="font-semibold mb-2">{{ $endpoint }}</h4>
                                    @foreach($abilities as $ability)
                                        <label class="inline-flex items-center mr-4">
                                            <input type="checkbox" name="permissions[{{ $endpoint }}][]" value="{{ $ability }}" class="form-checkbox">
                                            <span class="ml-2">{{ $ability }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="expires_at">
                                Expires At (optional)
                            </label>
                            <input type="date" name="expires_at" id="expires_at" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Create Token
                            </button>
                            <a href="{{ route('tokens.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 