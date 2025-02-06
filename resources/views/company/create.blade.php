<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Company') }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        <div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow">
            <form action="{{ route('company.store') }}" method="POST">
                @csrf

                <!-- Company Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Company Name</label>
                    <input type="text" name="name" id="name" class="w-full p-2 border border-gray-300 rounded mt-1"
                        value="{{ old('name') }}" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div class="mb-4">
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <input type="text" name="address" id="address"
                        class="w-full p-2 border border-gray-300 rounded mt-1" value="{{ old('address') }}" required>
                    @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Industry -->
                <div class="mb-4">
                    <label for="industry" class="block text-sm font-medium text-gray-700">Industry</label>
                    <input type="text" name="industry" id="industry"
                        class="w-full p-2 border border-gray-300 rounded mt-1" value="{{ old('industry') }}" required>
                    @error('industry')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Website -->
                <div class="mb-4">
                    <label for="website" class="block text-sm font-medium text-gray-700">Website (optional)</label>
                    <input type="url" name="website" id="website" class="w-full p-2 border border-gray-300 rounded mt-1"
                        value="{{ old('website') }}">
                    @error('website')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Owner ID -->
                <div class="mb-4">
                    <label for="ownerId" class="block text-sm font-medium text-gray-700">Owner (User ID)</label>
                    <input type="text" name="ownerId" id="ownerId"
                        class="w-full p-2 border border-gray-300 rounded mt-1" value="{{ old('ownerId') }}" required>
                    @error('ownerId')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <a href="{{ route('company.index') }}"
                        class="px-4 py-2 mr-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-200">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-black text-white rounded hover:bg-neutral-800">
                        Create Company
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>