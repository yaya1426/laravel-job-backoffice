<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            @if (auth()->user()->role === 'admin')
                {{ __('Dashboard Analytics') }}
            @else
                {{ __('Company Dashboard') }}
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Overview Cards -->
            <div class="grid grid-cols-1 gap-4 mb-8 md:grid-cols-3">
                @if (auth()->user()->role === 'admin')
                <div class="p-6 bg-white rounded-lg shadow-sm">
                    <h3 class="text-lg font-medium text-gray-900">Active Users</h3>
                    <p class="mt-2 text-3xl font-bold text-indigo-600">{{ number_format($analytics['active_users']) }}</p>
                    <p class="text-sm text-gray-500">Last 30 days</p>
                </div>
                @endif
                <div class="p-6 bg-white rounded-lg shadow-sm">
                    <h3 class="text-lg font-medium text-gray-900">Active Job Postings</h3>
                    <p class="mt-2 text-3xl font-bold text-indigo-600">{{ number_format($analytics['active_jobs']) }}</p>
                    <p class="text-sm text-gray-500">Currently active</p>
                </div>
                <div class="p-6 bg-white rounded-lg shadow-sm">
                    <h3 class="text-lg font-medium text-gray-900">Total Applications</h3>
                    <p class="mt-2 text-3xl font-bold text-indigo-600">{{ number_format($analytics['total_applications']) }}</p>
                    <p class="text-sm text-gray-500">All time</p>
                </div>
                @if (auth()->user()->role === 'company-owner')
                <div class="p-6 bg-white rounded-lg shadow-sm">
                    <h3 class="text-lg font-medium text-gray-900">Pending Applications</h3>
                    <p class="mt-2 text-3xl font-bold text-indigo-600">{{ number_format($analytics['pending_applications']) }}</p>
                    <p class="text-sm text-gray-500">Awaiting review</p>
                </div>
                @endif
            </div>

            <!-- Most Applied Jobs -->
            <div class="p-6 mb-8 bg-white rounded-lg shadow-sm">
                <h3 class="mb-4 text-lg font-medium text-gray-900">
                    @if (auth()->user()->role === 'admin')
                        Most Applied Jobs
                    @else
                        Your Most Applied Jobs
                    @endif
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Job Title</th>
                                @if (auth()->user()->role === 'admin')
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Company</th>
                                @endif
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Applications</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($mostAppliedJobs as $job)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $job->title }}</td>
                                @if (auth()->user()->role === 'admin')
                                <td class="px-6 py-4 whitespace-nowrap">{{ $job->company->name }}</td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($job->applications_count) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Conversion Rates -->
            <div class="p-6 mb-8 bg-white rounded-lg shadow-sm">
                <h3 class="mb-4 text-lg font-medium text-gray-900">
                    @if (auth()->user()->role === 'admin')
                        Top Converting Job Posts
                    @else
                        Your Top Converting Job Posts
                    @endif
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Job Title</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Views</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Applications</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Conversion Rate</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($jobsWithApplications as $job)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $job->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($job->view_count) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($job->applications_count) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($job->conversion_rate, 1) }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>