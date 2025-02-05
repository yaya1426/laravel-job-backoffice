<nav class="w-[250px] bg-white h-screen border-r border-gray-200">
    <!-- Logo Section -->
    <div class="flex items-center px-6 border-b border-gray-200 py-4">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
            <x-application-logo class="h-6 w-auto fill-current text-gray-800" />
            <span class="text-lg font-semibold text-gray-800"> {{ __('Shaghalni') }}</span>
        </a>
    </div>

    <!-- Navigation Links -->
    <ul class="flex flex-col px-4 py-6 space-y-4">
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('Dashboard') }}
        </x-nav-link>

        <x-nav-link :href="route('company.index')" :active="request()->routeIs('company.*')">
            {{ __('Companies') }}
        </x-nav-link>

        <x-nav-link :href="route('application.index')" :active="request()->routeIs('application.*')">
            {{ __('Applications') }}
        </x-nav-link>

        <x-nav-link :href="route('category.index')" :active="request()->routeIs('category.*')">
            {{ __('Categories') }}
        </x-nav-link>

        <x-nav-link :href="route('job-vacancy.index')" :active="request()->routeIs('job-vacancy.*')">
            {{ __('Job Vacancies') }}
        </x-nav-link>

        <x-nav-link :href="route('user.index')" :active="request()->routeIs('user.*')">
            {{ __('Users') }}
        </x-nav-link>
        <hr />
        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <x-nav-link class="text-red-500" :href="route('logout')"
                onclick="event.preventDefault(); this.closest('form').submit();">
                {{ __('Log Out') }}
            </x-nav-link>
        </form>
    </ul>
</nav>