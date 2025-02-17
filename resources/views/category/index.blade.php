<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Categories') }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        <x-toast-notification />

        <!-- Tabs for Active and Archived Categories -->
        <div class="flex justify-between items-center pb-2 border-b">
            <div class="flex space-x-4">
                <!-- Active Categories Tab -->
                <a href="{{ route('category.index', ['archived' => 'false']) }}"
                    class="px-4 py-2 rounded-lg {{ request('archived') !== 'true' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Active Categories
                </a>

                <!-- Archived Categories Tab -->
                <a href="{{ route('category.index', ['archived' => 'true']) }}"
                    class="px-4 py-2 rounded-lg {{ request('archived') === 'true' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Archived
                </a>
            </div>

            <!-- Add Category Button -->
            @if(request('archived') !== 'true')
                <a href="{{ route('category.create') }}"
                    class="items-center gap-2 px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800">
                    <span class="text-xl">+</span> Add Category
                </a>
            @endif
        </div>

        <!-- Categories Table -->
        <table class="min-w-full bg-white rounded-lg shadow mt-4">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Category Name</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr class="border-b">
                        <td class="px-6 py-4 text-gray-800">
                            @if(request('archived') === 'true')
                                <span class="text-gray-600">{{ $category->name }}</span>
                            @else
                                <a href="{{ route('category.show', $category->id) }}"
                                    class="text-blue-500 underline hover:text-blue-700">
                                    {{ $category->name }}
                                </a>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                @if(request('archived') === 'true')
                                    <!-- Restore Button -->
                                    <form action="{{ route('category.restore', $category->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-2 bg-gray-100 rounded hover:bg-gray-200">🔄
                                            Restore</button>
                                    </form>
                                @else
                                    <!-- Edit Button -->
                                    <a href="{{ route('category.edit', $category->id) }}"
                                        class="p-2 bg-gray-100 rounded hover:bg-gray-200">✏️</a>

                                    <!-- Archive Button -->
                                    <form action="{{ route('category.destroy', $category->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to archive this category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-gray-100 rounded hover:bg-gray-200">🗃️</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-6 py-4 text-center text-gray-600">No categories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination Controls -->
        <div class="mt-4">
            {{ $categories->links() }}
        </div>
    </div>
</x-app-layout>