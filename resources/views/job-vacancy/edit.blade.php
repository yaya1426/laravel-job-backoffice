<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Job Vacancy') }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        <x-toast-notification />

        <div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow">
            <form action="{{ route('job-vacancy.update', ['job_vacancy' => $jobVacancy->id]) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Job Vacancy Details Section -->
                <div class="mb-6 p-6 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Job Vacancy Details</h3>

                    <!-- Job Title -->
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-900">Job Title</label>
                        <div class="mt-2">
                            <input type="text" name="title" id="title" value="{{ old('title', $jobVacancy->title) }}"
                                class="{{ $errors->has('title') ? 'outline-red-500' : '' }} block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 placeholder:text-gray-400">
                        </div>
                        @error('title')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Job Type -->
                    <div class="mb-4">
                        <label for="type" class="block text-sm font-medium text-gray-900">Job Type</label>
                        <div class="mt-2">
                            <select name="type" id="type"
                                class="{{ $errors->has('type') ? 'outline-red-500' : '' }} block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 placeholder:text-gray-400">
                                <option value="">Select Job Type</option>
                                <option value="full-time" {{ old('type', $jobVacancy->type) == 'full-time' ? 'selected' : '' }}>Full-Time</option>
                                <option value="part-time" {{ old('type', $jobVacancy->type) == 'part-time' ? 'selected' : '' }}>Part-Time</option>
                                <option value="remote" {{ old('type', $jobVacancy->type) == 'remote' ? 'selected' : '' }}>
                                    Remote</option>
                            </select>
                        </div>
                        @error('type')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Job Location -->
                    <div class="mb-4">
                        <label for="location" class="block text-sm font-medium text-gray-900">Location</label>
                        <div class="mt-2">
                            <input type="text" name="location" id="location"
                                value="{{ old('location', $jobVacancy->location) }}"
                                class="{{ $errors->has('location') ? 'outline-red-500' : '' }} block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 placeholder:text-gray-400">
                        </div>
                        @error('location')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Expected Salary -->
                    <div class="mb-4">
                        <label for="salary" class="block text-sm font-medium text-gray-900">Expected Salary ($)</label>
                        <div class="mt-2">
                            <input type="number" name="salary" id="salary"
                                value="{{ old('salary', $jobVacancy->salary) }}" step="0.01"
                                class="{{ $errors->has('salary') ? 'outline-red-500' : '' }} block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 placeholder:text-gray-400">
                        </div>
                        @error('salary')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Company Selection -->
                    <div class="mb-4">
                        <label for="companyId" class="block text-sm font-medium text-gray-900">Company</label>
                        <div class="mt-2">
                            <select name="companyId" id="companyId"
                                class="{{ $errors->has('companyId') ? 'outline-red-500' : '' }} block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 placeholder:text-gray-400">
                                <option value="">Select Company</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}" {{ old('companyId', $jobVacancy->companyId) == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('companyId')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Category Selection -->
                    <div class="mb-4">
                        <label for="categoryId" class="block text-sm font-medium text-gray-900">Category</label>
                        <div class="mt-2">
                            <select name="categoryId" id="categoryId"
                                class="{{ $errors->has('categoryId') ? 'outline-red-500' : '' }} block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 placeholder:text-gray-400">
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('categoryId', $jobVacancy->categoryId) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('categoryId')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Job Description -->
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-900">Job Description</label>
                        <div class="mt-2">
                            <textarea name="description" id="description" rows="4"
                                class="{{ $errors->has('description') ? 'outline-red-500' : '' }} block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 placeholder:text-gray-400">{{ old('description', $jobVacancy->description) }}</textarea>
                        </div>
                        @error('description')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <a href="{{ route('job-vacancy.index') }}"
                        class="px-4 py-2 mr-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-200">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-black text-white rounded hover:bg-neutral-800">
                        Update Job Vacancy
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>