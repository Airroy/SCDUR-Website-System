@props([
    'image' => null,
    'title' => '',
    'description' => '',
    'link' => '#',
])

<a href="{{ $link }}" class="group block bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
    @if($image)
        <div class="relative h-48 overflow-hidden">
            <img 
                src="{{ $image }}" 
                alt="{{ $title }}"
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
            >
        </div>
    @endif
    
    <div class="p-6">
        @if($title)
            <h3 class="text-xl font-semibold text-gray-900 mb-2 group-hover:text-red-600 transition-colors">
                {{ $title }}
            </h3>
        @endif
        
        @if($description)
            <p class="text-gray-600 text-sm line-clamp-3">
                {{ $description }}
            </p>
        @endif
        
        {{ $slot }}
    </div>
</a>