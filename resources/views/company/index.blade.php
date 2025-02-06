<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Companies') }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        <div class="flex justify-end pb-2">
            <a href="{{ route('company.create') }}"
                class="items-center gap-2 px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800">
                <span class="text-xl">+</span> Add Company
            </a>
        </div>

        <table class="min-w-full bg-white rounded-lg shadow">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Name</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Industry</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Address</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Website</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($companies as $company)
                    <tr class="border-b">
                        <td class="px-6 py-4 text-gray-800">
                            <a href="{{ route('company.show', $company->id) }}"
                                class="text-blue-500 underline hover:text-blue-700">
                                {{ $company->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $company->industry }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $company->address }}</td>
                        <td class="px-6 py-4 text-gray-600">
                            @if ($company->website)
                                <a href="{{ $company->website }}" target="_blank" class="text-blue-500 underline">Visit
                                    Website</a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <!-- Edit Button -->
                                <a href="{{ route('company.edit', $company->id) }}"
                                    class="p-2 bg-gray-100 rounded hover:bg-gray-200">✏️</a>

                                <!-- Delete Button -->
                                <form action="{{ route('company.destroy', $company->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this company?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-gray-100 rounded hover:bg-gray-200">🗑️</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-600">No companies found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination Controls -->
        <div class="mt-4">
            {{ $companies->links() }}
        </div>
    </div>
</x-app-layout>