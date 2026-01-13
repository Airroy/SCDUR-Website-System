@props([
    'title' => '',
    'subtitle' => '',
])

<section {{ $attributes->merge(['class' => 'py-12 bg-white']) }}>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($title || $subtitle)
            <div class="text-center mb-8">
                @if($title)
                    <h2 class="text-3xl font-bold text-gray-900 mb-3">{{ $title }}</h2>
                @endif
                @if($subtitle)
                    <p class="text-lg text-gray-600">{{ $subtitle }}</p>
                @endif
            </div>
        @endif

        <div>
            {{ $slot }}
        </div>
    </div>
</section>
