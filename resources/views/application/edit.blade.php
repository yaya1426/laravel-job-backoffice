<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Application') }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        <div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow">
            <form
                action="{{ route('application.update', ['application' => $application->id, 'redirectToList' => request('redirectToList')]) }}"
                method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="id" value="{{ $application->id }}" />

                <!-- Application Details Section -->
                <div class="mb-6 p-6 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Application Details</h3>

                    <!-- Applicant Name (Read-Only) -->
                    <div class="mb-4">
                        <label for="applicant_name" class="block text-sm font-medium text-gray-900">Applicant
                            Name</label>
                        <div class="mt-2">
                            <input type="text" id="applicant_name" value="{{ $application->user->name }}" disabled
                                class="block w-full rounded-md bg-gray-100 px-3 py-1.5 text-base text-gray-500 outline outline-1 placeholder:text-gray-400 cursor-not-allowed">
                        </div>
                    </div>

                    <!-- Job Position (Read-Only) -->
                    <div class="mb-4">
                        <label for="job_position" class="block text-sm font-medium text-gray-900">Job Position</label>
                        <div class="mt-2">
                            <input type="text" id="job_position" value="{{ $application->jobVacancy->title }}" disabled
                                class="block w-full rounded-md bg-gray-100 px-3 py-1.5 text-base text-gray-500 outline outline-1 placeholder:text-gray-400 cursor-not-allowed">
                        </div>
                    </div>

                    <!-- Company Name (Read-Only) -->
                    <div class="mb-4">
                        <label for="company_name" class="block text-sm font-medium text-gray-900">Company</label>
                        <div class="mt-2">
                            <input type="text" id="company_name" value="{{ $application->jobVacancy->company->name }}"
                                disabled
                                class="block w-full rounded-md bg-gray-100 px-3 py-1.5 text-base text-gray-500 outline outline-1 placeholder:text-gray-400 cursor-not-allowed">
                        </div>
                    </div>

                    <!-- Application Status Dropdown -->
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-900">Application Status</label>
                        <div class="mt-2">
                            <select name="status" id="status"
                                class="{{ $errors->has('status') ? 'outline-red-500' : '' }} block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 placeholder:text-gray-400">
                                <option value="pending" {{ old('status', $application->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="hired" {{ old('status', $application->status) == 'hired' ? 'selected' : '' }}>Hired</option>
                                <option value="rejected" {{ old('status', $application->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        @error('status')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- AI-Generated Feedback Section (Read-Only) -->
                <div class="mb-6 p-6 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">AI-Generated Feedback</h3>

                    <!-- AI Score -->
                    <div class="mb-4">
                        <label for="aiGeneratedScore" class="block text-sm font-medium text-gray-900">AI Score</label>
                        <div class="mt-2">
                            <input type="text" id="aiGeneratedScore"
                                value="{{ $application->aiGeneratedScore ?? 'Not Available' }}" disabled
                                class="block w-full rounded-md bg-gray-100 px-3 py-1.5 text-base text-gray-500 outline outline-1 placeholder:text-gray-400 cursor-not-allowed">
                        </div>
                    </div>

                    <!-- AI Feedback -->
                    <div class="mb-4">
                        <label for="aiGeneratedFeedback" class="block text-sm font-medium text-gray-900">AI
                            Feedback</label>
                        <div class="mt-2">
                            <textarea id="aiGeneratedFeedback" disabled
                                class="block w-full rounded-md bg-gray-100 px-3 py-1.5 text-base text-gray-500 outline outline-1 placeholder:text-gray-400 cursor-not-allowed"
                                rows="4">{{ $application->aiGeneratedFeedback ?? 'No feedback available' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <a href="{{ route('application.index') }}"
                        class="px-4 py-2 mr-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-200">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-black text-white rounded hover:bg-neutral-800">
                        Update Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>