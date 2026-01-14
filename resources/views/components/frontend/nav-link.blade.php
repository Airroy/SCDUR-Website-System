@props(['href' => '#', 'active' => false])

<a 
    href="{{ $href }}" 
    {{ $attributes->merge([
        'class' => 'block py-[18px] px-5 text-white no-underline text-[15px] font-normal transition-all duration-300 hover:bg-[#ff9f8e] hover:text-black whitespace-nowrap ' . 
        ($active ? 'bg-[#ff9f8e] text-black' : '')
    ]) }}
>
    {{ $slot }}
</a>