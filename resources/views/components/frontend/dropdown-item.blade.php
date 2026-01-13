@props(['href' => '#'])

<a 
    href="{{ $href }}" 
    {{ $attributes->merge(['class' => 'block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-red-600 transition-colors']) }}
>
    {{ $slot }}
</a>
