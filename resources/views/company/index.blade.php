<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Companies') }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-50 px-3 py-2">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 px-3 py-2">
                {{ session('error') }}
            </div>
        @endif

        <!-- Tabs for Active and Archived Companies -->
        <div class="flex justify-between items-center pb-2 border-b">
            <div class="flex space-x-4">
                <!-- Active Companies Tab -->
                <a href="{{ route('company.index', ['archived' => 'false']) }}"
                    class="px-4 py-2 rounded-lg {{ request('archived') !== 'true' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Active Companies
                </a>

                <!-- Archived Companies Tab -->
                <a href="{{ route('company.index', ['archived' => 'true']) }}"
                    class="px-4 py-2 rounded-lg {{ request('archived') === 'true' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Archived
                </a>
            </div>

            <!-- Add Company Button -->
            @if(request('archived') !== 'true')
                <a href="{{ route('company.create') }}"
                    class="items-center gap-2 px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800">
                    <span class="text-xl">+</span> Add Company
                </a>
            @endif
        </div>

        <!-- Companies Table -->
        <table class="min-w-full bg-white rounded-lg shadow mt-4">
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
                            @if(request('archived') === 'true')
                                <span class="text-gray-600">{{ $company->name }}</span>
                            @else
                                <a href="{{ route('company.show', $company->id) }}"
                                    class="text-blue-500 underline hover:text-blue-700">
                                    {{ $company->name }}
                                </a>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $company->industry }}</td>
                        <td class="px-6 py-4 text-gray-600 max-w-xs truncate" title="{{ $company->address }}">
                            <span class="whitespace-normal block">
                                {{ Str::limit($company->address, 30) }}
                            </span>
                        </td>
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
                                @if(request('archived') === 'true')
                                    <!-- Restore Button -->
                                    <form action="{{ route('company.restore', $company->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-2 bg-gray-100 rounded hover:bg-gray-200">🔄
                                            Restore</button>
                                    </form>
                                @else
                                    <!-- Edit Button -->
                                    <a href="{{ route('company.edit', $company->id) }}"
                                        class="p-2 bg-gray-100 rounded hover:bg-gray-200">✏️</a>

                                    <!-- Archive Button -->
                                    <form action="{{ route('company.destroy', $company->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to archive this company?');">
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