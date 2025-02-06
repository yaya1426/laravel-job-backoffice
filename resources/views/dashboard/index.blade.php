<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-6 bg-gray-100">
        <!-- Total Companies Box -->
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500 text-sm">Total Companies</p>
            <h2 class="text-3xl font-bold">245</h2>
            <p class="text-green-600 text-sm mt-1">+20% from last month</p>
        </div>

        <!-- Active Job Listings Box -->
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500 text-sm">Active Job Listings</p>
            <h2 class="text-3xl font-bold">873</h2>
            <p class="text-green-600 text-sm mt-1">+12% from last month</p>
        </div>

        <!-- New Applications Box -->
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500 text-sm">New Applications</p>
            <h2 class="text-3xl font-bold">573</h2>
            <p class="text-green-600 text-sm mt-1">+42% from last month</p>
        </div>

        <!-- Registered Users Box -->
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500 text-sm">Registered Users</p>
            <h2 class="text-3xl font-bold">1,842</h2>
            <p class="text-green-600 text-sm mt-1">+18% from last month</p>
        </div>
    </div>
</x-app-layout>