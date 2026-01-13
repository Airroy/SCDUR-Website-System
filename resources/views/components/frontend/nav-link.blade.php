@props(['href' => '#', 'active' => false])

<a 
    href="{{ $href }}" 
    {{ $attributes->merge(['class' => 'text-white hover:bg-red-700 px-4 py-3 text-sm font-medium transition-colors border-b-2 border-transparent hover:border-white']) }}
>
    {{ $slot }}
</a>
