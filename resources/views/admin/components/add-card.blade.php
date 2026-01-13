<div {{ $attributes->merge(['class' => 'bg-white block p-6 border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow cursor-pointer group']) }}>
    <div class="flex flex-col items-center justify-center gap-4 text-center min-h-[160px]">
        <svg class="w-12 h-12 text-gray-400 group-hover:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        <h5 class="text-xl font-semibold text-gray-800 group-hover:text-red-600 transition-colors leading-7">{{ $slot }}</h5>
    </div>
</div>
