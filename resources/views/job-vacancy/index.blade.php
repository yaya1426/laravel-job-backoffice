<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Job Vacancies') }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        <x-toast-notification />

        <!-- Tabs for Active and Archived Job Vacancies -->
        <div class="flex justify-between items-center pb-2 border-b">
            <div class="flex space-x-4">
                <!-- Active Job Vacancies Tab -->
                <a href="{{ route('job-vacancy.index', ['archived' => 'false']) }}"
                    class="px-4 py-2 rounded-lg {{ request('archived') !== 'true' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Active Job Vacancies
                </a>

                <!-- Archived Job Vacancies Tab -->
                <a href="{{ route('job-vacancy.index', ['archived' => 'true']) }}"
                    class="px-4 py-2 rounded-lg {{ request('archived') === 'true' ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Archived
                </a>
            </div>

            <!-- Add Job Vacancy Button -->
            @if(request('archived') !== 'true')
                <a href="{{ route('job-vacancy.create') }}"
                    class="items-center gap-2 px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800">
                    <span class="text-xl">+</span> Add Job Vacancy
                </a>
            @endif
        </div>

        <!-- Job Vacancies Table -->
        <table class="min-w-full bg-white rounded-lg shadow mt-4">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Title</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Company</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Location</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Type</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Salary</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jobVacancies as $job)
                    <tr class="border-b">
                        <td class="px-6 py-4 text-gray-800">
                            @if(request('archived') === 'true')
                                <span class="text-gray-600">{{ $job->title }}</span>
                            @else
                                <a href="{{ route('job-vacancy.show', $job->id) }}"
                                    class="text-blue-500 underline hover:text-blue-700">
                                    {{ $job->title }}
                                </a>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $job->company->name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $job->location }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ ucfirst($job->type) }}</td>
                        <td class="px-6 py-4 text-gray-600">${{ number_format($job->salary, 2) }}</td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                @if(request('archived') === 'true')
                                    <!-- Restore Button -->
                                    @if(auth()->user()->role === 'admin' || (auth()->user()->role === 'company-owner' && $job->company->ownerId === auth()->id()))
                                        <form action="{{ route('job-vacancy.restore', $job->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="p-2 bg-gray-100 rounded hover:bg-gray-200">🔄
                                                Restore</button>
                                        </form>
                                    @endif
                                @else
                                    <!-- Edit Button -->
                                    @if(auth()->user()->role === 'admin' || (auth()->user()->role === 'company-owner' && $job->company->ownerId === auth()->id()))
                                        <a href="{{ route('job-vacancy.edit', $job->id) }}"
                                            class="p-2 bg-gray-100 rounded hover:bg-gray-200">✏️</a>
                                    @endif

                                    <!-- Archive Button -->
                                    @if(auth()->user()->role === 'admin' || (auth()->user()->role === 'company-owner' && $job->company->ownerId === auth()->id()))
                                        <form action="{{ route('job-vacancy.destroy', $job->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to archive this job vacancy?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-gray-100 rounded hover:bg-gray-200">🗃️</button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-600">No job vacancies found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination Controls -->
        <div class="mt-4">
            {{ $jobVacancies->links() }}
        </div>
    </div>
</x-app-layout>
