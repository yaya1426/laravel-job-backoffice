<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $jobVacancy->title }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        <x-toast-notification />
        
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('job-vacancy.index') }}"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                ← Back
            </a>
        </div>

        <div class="w-full mx-auto p-6 bg-white rounded-lg shadow">
            <!-- Job Vacancy Details -->
            <div class="mb-6">
                <h3 class="text-lg font-bold">Job Details</h3>
                <p><strong>Company:</strong> {{ $jobVacancy->company->name }}</p>
                <p><strong>Location:</strong> {{ $jobVacancy->location }}</p>
                <p><strong>Type:</strong> {{ ucfirst($jobVacancy->type) }}</p>
                <p><strong>Salary:</strong> ${{ number_format($jobVacancy->salary, 2) }}</p>
                <p><strong>Description:</strong> {{ $jobVacancy->description }}</p>
            </div>

            <!-- Edit and Archive Buttons -->
            <div class="flex justify-end space-x-2 mb-6">
                <a href="{{ route('job-vacancy.edit', ['job_vacancy' => $jobVacancy->id, 'redirectToList' => 'false']) }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Edit
                </a>
                <form action="{{ route('job-vacancy.destroy', $jobVacancy->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to archive this job vacancy?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-violet-600 text-white rounded hover:bg-violet-700">
                        Archive
                    </button>
                </form>
            </div>

            <!-- Tabs Navigation -->
            <div class="mb-6">
                <ul class="flex space-x-4">
                    <li>
                        <a href="{{ route('job-vacancy.show', ['job_vacancy' => $jobVacancy->id, 'tab' => 'applicants']) }}"
                            class="px-4 py-2 text-gray-800 font-semibold {{ request('tab') === 'applicants' || request('tab') === null ? 'border-b-2 border-blue-500' : '' }}">
                            Applicants
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Tabs Content -->
            @if (request('tab') === 'applicants' || request('tab') === null)
                <div id="applicants-tab" class="mb-6">
                    <h3 class="text-lg font-bold mb-3">Applicants</h3>
                    <table class="min-w-full bg-gray-50 rounded-lg shadow">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Applicant Name</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($jobVacancy->jobApplications as $application)
                                <tr class="border-b">
                                    <td class="px-6 py-4">{{ $application->user->name }}</td>
                                    <td class="px-6 py-4">
                                        <span class="
                                            @if($application->status === 'pending') text-yellow-600 
                                            @elseif($application->status === 'rejected') text-red-600 
                                            @elseif($application->status === 'hired') text-green-600 
                                            @endif">
                                            {{ ucfirst($application->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('application.show', $application->id) }}"
                                            class="text-blue-500 underline">View Application</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-600">No applicants for this job vacancy.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
