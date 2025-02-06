<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Applications') }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        <table class="min-w-full bg-white rounded-lg shadow">
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
                <!-- Row 1 -->
                <tr class="border-b">
                    <td class="px-6 py-4 text-gray-800">John Doe</td>
                    <td class="px-6 py-4 text-gray-600">Software Engineer</td>
                    <td class="px-6 py-4 text-gray-600">Acme Corp</td>
                    <td class="px-6 py-4 text-yellow-600">Pending</td>
                    <td class="px-6 py-4">
                        <button class="p-2 bg-gray-100 rounded hover:bg-gray-200">
                            ✏️ <!-- Edit Icon -->
                        </button>
                        <button class="p-2 bg-gray-100 rounded hover:bg-gray-200">
                            🗑️ <!-- Delete Icon -->
                        </button>
                    </td>
                </tr>
                <!-- Row 2 -->
                <tr class="border-b">
                    <td class="px-6 py-4 text-gray-800">Jane Smith</td>
                    <td class="px-6 py-4 text-gray-600">Product Manager</td>
                    <td class="px-6 py-4 text-gray-600">Globex</td>
                    <td class="px-6 py-4 text-blue-600">Interviewed</td>
                    <td class="px-6 py-4">
                        <button class="p-2 bg-gray-100 rounded hover:bg-gray-200">
                            ✏️
                        </button>
                    </td>
                </tr>
                <!-- Row 3 -->
                <tr class="border-b">
                    <td class="px-6 py-4 text-gray-800">Bob Johnson</td>
                    <td class="px-6 py-4 text-gray-600">Data Analyst</td>
                    <td class="px-6 py-4 text-gray-600">Initech</td>
                    <td class="px-6 py-4 text-red-600">Rejected</td>
                    <td class="px-6 py-4">
                        <button class="p-2 bg-gray-100 rounded hover:bg-gray-200">
                            ✏️
                        </button>
                    </td>
                </tr>
                <!-- Row 4 -->
                <tr class="border-b">
                    <td class="px-6 py-4 text-gray-800">Alice Brown</td>
                    <td class="px-6 py-4 text-gray-600">UX Designer</td>
                    <td class="px-6 py-4 text-gray-600">Hooli</td>
                    <td class="px-6 py-4 text-green-600">Hired</td>
                    <td class="px-6 py-4">
                        <button class="p-2 bg-gray-100 rounded hover:bg-gray-200">
                            ✏️
                        </button>
                    </td>
                </tr>
                <!-- Row 5 -->
                <tr>
                    <td class="px-6 py-4 text-gray-800">Charlie Wilson</td>
                    <td class="px-6 py-4 text-gray-600">Marketing Specialist</td>
                    <td class="px-6 py-4 text-gray-600">Umbrella Corp</td>
                    <td class="px-6 py-4 text-yellow-600">Pending</td>
                    <td class="px-6 py-4">
                        <button class="p-2 bg-gray-100 rounded hover:bg-gray-200">
                            ✏️
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</x-app-layout>