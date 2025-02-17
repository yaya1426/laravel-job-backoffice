<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Application Details') }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        <x-toast-notification />

        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('application.index') }}"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                ← Back
            </a>
        </div>

        <div class="w-full mx-auto p-6 bg-white rounded-lg shadow">
            <!-- Application Details -->
            <div class="mb-6">
                <h3 class="text-lg font-bold">Application Information</h3>
                <p><strong>Applicant Name:</strong> {{ $application->user->name }}</p>
                <p><strong>Email:</strong> {{ $application->user->email }}</p>
                <p><strong>Job Title:</strong> {{ $application->jobVacancy->title }}</p>
                <p><strong>Company:</strong> {{ $application->jobVacancy->company->name }}</p>
                <p><strong>Status:</strong>
                    <span class="
                        @if($application->status === 'pending') text-yellow-600
                        @elseif($application->status === 'rejected') text-red-600
                        @elseif($application->status === 'hired') text-green-600
                        @endif">
                        {{ ucfirst($application->status) }}
                    </span>
                </p>
            </div>

            <!-- Edit and Archive Buttons -->
            <div class="flex justify-end space-x-2 mb-6">
                <a href="{{ route('application.edit', ['application' => $application->id, 'redirectToList' => 'false']) }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Edit
                </a>
                <form action="{{ route('application.destroy', $application->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to archive this application?');">
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
                        <a href="{{ route('application.show', ['application' => $application->id, 'tab' => 'resume']) }}"
                            class="px-4 py-2 text-gray-800 font-semibold {{ request('tab') === 'resume' || request('tab') === null ? 'border-b-2 border-blue-500' : '' }}">
                            Resume
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('application.show', ['application' => $application->id, 'tab' => 'ai-feedback']) }}"
                            class="px-4 py-2 text-gray-800 font-semibold {{ request('tab') === 'ai-feedback' ? 'border-b-2 border-blue-500' : '' }}">
                            AI Feedback
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Tabs Content -->
            @if (request('tab') === 'ai-feedback')
                <div id="ai-feedback-tab" class="mb-6">
                    <h3 class="text-lg font-bold mb-3">AI-Generated Feedback</h3>
                    <p><strong>AI Score:</strong> {{ $application->aiGeneratedScore ?? 'Not Available' }}</p>
                    <p><strong>Feedback:</strong>
                        @if ($application->aiGeneratedFeedback)
                            <span class="block bg-gray-100 p-3 rounded">{{ $application->aiGeneratedFeedback }}</span>
                        @else
                            <span class="text-gray-500">No AI feedback available.</span>
                        @endif
                    </p>
                </div>
            @else
                <div id="resume-tab" class="mb-6">
                    <h3 class="text-lg font-bold mb-3">Applicant Resume</h3>
                    <p><strong>Resume File:</strong>
                        @if ($application->resume->fileUri)
                            <a href="{{ asset($application->resume->fileUri) }}" target="_blank"
                                class="text-blue-500 underline">Download Resume</a>
                        @else
                            <span class="text-gray-500">No resume uploaded.</span>
                        @endif
                    </p>
                    <p><strong>Summary:</strong> {{ $application->resume->summary }}</p>
                    <p><strong>Skills:</strong> {{ $application->resume->skills }}</p>
                    <p><strong>Experience:</strong> {{ $application->resume->experience }}</p>
                    <p><strong>Education:</strong> {{ $application->resume->education }}</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>