<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Company') }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        <div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow">
            <form action="{{ route('company.update', $company->id) }}" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="id" value="{{ $company->id }}" />


                <!-- Company Details Section -->
                <div class="mb-6 p-6 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Company Details</h3>

                    <!-- Company Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-900">Company Name</label>
                        <div class="mt-2">
                            <input type="text" name="name" id="name" value="{{ old('name', $company->name) }}"
                                class="{{ $errors->has('name') ? 'outline-red-500' : '' }} block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 placeholder:text-gray-400">
                        </div>
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-gray-900">Address</label>
                        <div class="mt-2">
                            <input type="text" name="address" id="address"
                                value="{{ old('address', $company->address) }}"
                                class="{{ $errors->has('address') ? 'outline-red-500' : '' }} block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 placeholder:text-gray-400">
                        </div>
                        @error('address')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Industry Dropdown -->
                    <div class="mb-4">
                        <label for="industry" class="block text-sm font-medium text-gray-900">Industry</label>
                        <div class="mt-2">
                            <select name="industry" id="industry"
                                class="{{ $errors->has('industry') ? 'outline-red-500' : '' }} block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 placeholder:text-gray-400">
                                <option value="">Select Industry</option>
                                @foreach ($industries as $industry)
                                    <option value="{{ $industry }}" {{ old('industry', $company->industry) == $industry ? 'selected' : '' }}>
                                        {{ $industry }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('industry')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Website -->
                    <div class="mb-4">
                        <label for="website" class="block text-sm font-medium text-gray-900">Website (optional)</label>
                        <div class="mt-2">
                            <input type="url" name="website" id="website"
                                value="{{ old('website', $company->website) }}"
                                class="{{ $errors->has('website') ? 'outline-red-500' : '' }} block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 placeholder:text-gray-400">
                        </div>
                        @error('website')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Company Owner Section -->
                <div class="mb-6 p-6 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Company Owner</h3>

                    <!-- Owner Name (Read-Only) -->
                    <div class="mb-4">
                        <label for="owner_name" class="block text-sm font-medium text-gray-900">Owner Name</label>
                        <div class="mt-2">
                            <input type="text" id="owner_name" value="{{ $company->owner->name }}" disabled
                                class="block w-full rounded-md bg-gray-100 px-3 py-1.5 text-base text-gray-500 outline outline-1 placeholder:text-gray-400 cursor-not-allowed">
                        </div>
                    </div>

                    <!-- Owner Email (Read-Only) -->
                    <div class="mb-4">
                        <label for="owner_email" class="block text-sm font-medium text-gray-900">Owner Email</label>
                        <div class="mt-2">
                            <input type="email" id="owner_email" value="{{ $company->owner->email }}" disabled
                                class="block w-full rounded-md bg-gray-100 px-3 py-1.5 text-base text-gray-500 outline outline-1 placeholder:text-gray-400 cursor-not-allowed">
                        </div>
                    </div>
                    
                    <!-- Owner Password (Optional) -->
                    <div class="mb-4 relative" x-data="{ show: false }">
                        <label for="owner_password" class="block text-sm font-medium text-gray-900">
                            Change Owner Password (Optional)
                        </label>

                        <div class="relative">
                            <input type="password" name="owner_password" id="owner_password"
                                x-bind:type="show ? 'text' : 'password'"
                                class="{{ $errors->has('owner_password') ? 'outline-red-500' : '' }} block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 pr-10 placeholder:text-gray-400">

                            <!-- Eye Icon for Show/Hide Password -->
                            <button type="button" class="absolute inset-y-0 right-2 flex items-center text-gray-500"
                                @click="show = !show">
                                <!-- Eye Open Icon (Password Visible) -->
                                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
                                </svg>

                                <!-- Eye Closed Icon (Password Hidden) -->
                                <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825a9.56 9.56 0 01-1.875.175c-4.478 0-8.268-2.943-9.542-7 1.002-3.364 3.843-6 7.542-7.575M15 12a3 3 0 00-6 0 3 3 0 006 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3l18 18" />
                                </svg>
                            </button>
                        </div>

                        @error('owner_password')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <a href="{{ route('company.index') }}"
                        class="px-4 py-2 mr-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-200">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-black text-white rounded hover:bg-neutral-800">
                        Update Company
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>