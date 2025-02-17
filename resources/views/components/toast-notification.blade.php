<div class="absolute inset-x-0 bottom-0 z-50">
    @if(session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
            class="bg-green-500 text-white px-6 py-3 shadow-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
            class="bg-red-500 text-white px-6 py-3 shadow-lg mt-2">
            {{ session('error') }}
        </div>
    @endif
</div>