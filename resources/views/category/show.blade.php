<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Category Details') }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        <x-toast-notification />
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('category.index') }}"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                ← Back
            </a>
        </div>

        <div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Category Information</h3>
            <p><strong>Name:</strong> {{ $category->name }}</p>

            <!-- Edit and Delete Buttons -->
            <div class="flex justify-end space-x-2 mt-6">
                <a href="{{ route('category.edit', $category->id) }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Edit
                </a>
                <form action="{{ route('category.destroy', $category->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this category?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>