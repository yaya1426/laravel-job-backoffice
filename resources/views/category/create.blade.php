<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Category') }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        <div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow">
            <form action="{{ route('category.store') }}" method="POST">
                @csrf

                <!-- Category Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-900">Category Name</label>
                    <div class="mt-2">
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="{{ $errors->has('name') ? 'outline-red-500' : '' }} block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 placeholder:text-gray-400">
                    </div>
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <a href="{{ route('category.index') }}"
                        class="px-4 py-2 mr-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-200">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-black text-white rounded hover:bg-neutral-800">
                        Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>