@props(['title' => 'Menu'])

<div class="relative group">
    <button class="block py-[18px] px-5 text-white no-underline text-[15px] font-normal transition-all duration-300 hover:bg-[#ff9f8e] hover:text-black whitespace-nowrap inline-flex items-center">
        {{ $title }}
        <span class="ml-1 text-xs">▾</span>
    </button>
    
    <!-- Dropdown Menu -->
    <div class="absolute top-full left-0 bg-white min-w-[250px] list-none py-2.5 px-0 m-0 shadow-[0_4px_15px_rgba(0,0,0,0.2)] opacity-0 invisible translate-y-[-10px] group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 transition-all duration-300 rounded-b-lg z-50">
        {{ $slot }}
    </div>
</div>