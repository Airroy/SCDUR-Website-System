@props(['href' => '#', 'active' => false])

<a 
    href="{{ $href }}" 
    {{ $attributes->merge([
        'class' => 'block px-5 py-4 text-white no-underline text-nav font-normal transition-all duration-300 hover:bg-pink hover:text-black whitespace-nowrap ' . 
        ($active ? 'bg-pink text-black' : '')
    ]) }}
>
    {{ $slot }}
</a>