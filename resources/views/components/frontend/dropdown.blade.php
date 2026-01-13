@props(['title' => 'Menu'])

<div class="relative group">
    <button class="text-white hover:bg-red-700 px-4 py-3 text-sm font-medium transition-colors inline-flex items-center border-b-2 border-transparent hover:border-white">
        {{ $title }}
        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    <div class="absolute left-0 top-full w-56 bg-white shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
        {{ $slot }}
    </div>
</div>
