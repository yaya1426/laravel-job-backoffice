<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Job Applications') }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        <x-toast-notification />
        <!-- Tabs for Active and Archived Applications -->
        <div class="flex justify-between items-center pb-2 border-b">
            <div class="flex space-x-4">
                <!-- Active Applications Tab -->
                <a href="{{ route('application.index', ['archived' => 'false']) }}"
                    class="px-4 py-2 rounded-lg {{ request('archived') !== 'true' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Active Applications
                </a>

                <!-- Archived Applications Tab -->
                <a href="{{ route('application.index', ['archived' => 'true']) }}"
                    class="px-4 py-2 rounded-lg {{ request('archived') === 'true' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Archived
                </a>
            </div>
        </div>

        <!-- Applications Table -->
        <table class="min-w-full bg-white rounded-lg shadow mt-4">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Applicant</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Position</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Company</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($applications as $application)
                    <tr class="border-b">
                        <td class="px-6 py-4 text-gray-800">
                            @if(request('archived') === 'true')
                                <span class="text-gray-600">{{ $application->user?->name ?? 'User Deleted' }}</span>
                            @else
                                <a href="{{ route('application.show', $application->id) }}"
                                    class="text-blue-500 underline hover:text-blue-700">
                                    {{ $application->user?->name ?? 'User Deleted' }}
                                </a>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $application->jobVacancy?->title ?? 'Position Deleted' }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $application->jobVacancy?->company?->name ?? 'Company Deleted' }}</td>
                        <td class="px-6 py-4 text-gray-600">
                            <span class="
                                                @if($application->status === 'pending') text-yellow-600
                                                @elseif($application->status === 'interviewed') text-blue-600
                                                @elseif($application->status === 'rejected') text-red-600
                                                @elseif($application->status === 'hired') text-green-600
                                                @endif">
                                {{ ucfirst($application->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                @if(request('archived') === 'true')
                                    <!-- Restore Button -->
                                    <form action="{{ route('application.restore', $application->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-2 bg-gray-100 rounded hover:bg-gray-200">🔄
                                            Restore</button>
                                    </form>
                                @else
                                    <!-- Edit Button -->
                                    <a href="{{ route('application.edit', $application->id) }}"
                                        class="p-2 bg-gray-100 rounded hover:bg-gray-200">✏️</a>

                                    <!-- Archive Button -->
                                    <form action="{{ route('application.destroy', $application->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to archive this application?');">
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
                        <td colspan="5" class="px-6 py-4 text-center text-gray-600">No job applications found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination Controls -->
        <div class="mt-4">
            {{ $applications->links() }}
        </div>
    </div>
</x-app-layout>
