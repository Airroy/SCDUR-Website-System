@props([
    'type' => 'button',
    'variant' => 'primary', // primary, secondary, outline
])

@php
$classes = match($variant) {
    'primary' => 'bg-red-600 hover:bg-red-700 text-white',
    'secondary' => 'bg-gray-600 hover:bg-gray-700 text-white',
    'outline' => 'bg-white hover:bg-gray-50 text-red-600 border-2 border-red-600',
    default => 'bg-red-600 hover:bg-red-700 text-white',
};
@endphp

<button 
    type="{{ $type }}"
    {{ $attributes->merge(['class' => "px-6 py-3 rounded-lg font-medium transition-colors duration-200 {$classes}"]) }}
>
    {{ $slot }}
</button>