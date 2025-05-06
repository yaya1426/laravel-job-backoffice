<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $company->name }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        <x-toast-notification />
        <!-- Back Button -->
        <div class="mb-4">
            @if (auth()->user()->role === 'admin')
            <a href="{{ route('company.index') }}"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                ← Back
            </a>
            @else
            <a href="{{ route('dashboard') }}"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                ← Back to Dashboard
            </a>
            @endif
        </div>

        <div class="w-full mx-auto p-6 bg-white rounded-lg shadow">
            <!-- Company Details -->
            <div class="mb-6">
                <h3 class="text-lg font-bold">Company Information</h3>
                <p><strong>Name:</strong> {{ $company->name }}</p>
                <p><strong>Industry:</strong> {{ $company->industry }}</p>
                <p><strong>Address:</strong> {{ $company->address }}</p>
                <p><strong>Owner:</strong> {{ $company->owner->name }} ({{ $company->owner->email }})</p>
                <p><strong>Website:</strong>
                    @if ($company->website)
                        <a href="{{ $company->website }}" target="_blank" class="text-blue-500 underline">Visit Website</a>
                    @else
                        N/A
                    @endif
                </p>
            </div>

            <!-- Edit and Delete Buttons -->
            <div class="flex justify-end space-x-2 mb-6">
                @if (auth()->user()->role === 'admin')
                <a href="{{ route('company.edit', ['company' => $company->id, 'redirectToList' => 'false']) }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Edit
                </a>
                <form action="{{ route('company.destroy', $company->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this company?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-violet-600 text-white rounded hover:bg-violet-700">
                        Archive
                    </button>
                </form>
                @else
                <a href="{{ route('company-owner.company.edit') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Edit
                </a>
                @endif
            </div>

            @if (auth()->user()->role === 'admin')
            <!-- Tabs Navigation -->
            <div class="mb-6">
                <ul class="flex space-x-4">
                    <li>
                        <a href="{{ route('company.show', ['company' => $company->id, 'tab' => 'jobs']) }}"
                            class="px-4 py-2 text-gray-800 font-semibold {{ request('tab') === 'jobs' || request('tab') === null ? 'border-b-2 border-blue-500' : '' }}">
                            Jobs
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('company.show', ['company' => $company->id, 'tab' => 'applicants']) }}"
                            class="px-4 py-2 text-gray-800 font-semibold {{ request('tab') === 'applicants' ? 'border-b-2 border-blue-500' : '' }}">
                            Applicants
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Tabs Content -->
            @if (request('tab') === 'applicants')
                <div id="applicants-tab" class="mb-6">
                    <h3 class="text-lg font-bold mb-3">Applicants</h3>
                    <table class="min-w-full bg-gray-50 rounded-lg shadow">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Applicant Name</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Job Title</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($applicants as $application)
                                <tr class="border-b">
                                    <td class="px-6 py-4">{{ $application->user->name }}</td>
                                    <td class="px-6 py-4">{{ $application->jobVacancy->title }}</td>
                                    <td class="px-6 py-4">{{ ucfirst($application->status) }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('application.show', $application->id) }}"
                                            class="text-blue-500 underline">View Application</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-600">No applicants for this company.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div id="jobs-tab" class="mb-6">
                    <h3 class="text-lg font-bold mb-3">Job Vacancies</h3>
                    <table class="min-w-full bg-gray-50 rounded-lg shadow">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Title</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Type</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Location</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($company->jobVacancies as $job)
                                <tr class="border-b">
                                    <td class="px-6 py-4">{{ $job->title }}</td>
                                    <td class="px-6 py-4">{{ ucfirst($job->type) }}</td>
                                    <td class="px-6 py-4">{{ $job->location }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('job-vacancy.show', $job->id) }}"
                                            class="text-blue-500 underline">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-600">No jobs available for this
                                        company.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
            @endif
        </div>
    </div>
</x-app-layout>