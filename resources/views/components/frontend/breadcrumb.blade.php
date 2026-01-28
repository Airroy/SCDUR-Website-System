@props(['items'])

<nav class="text-xs sm:text-sm text-gray-600 mb-4 sm:mb-6 overflow-x-auto pb-2">
    <div class="flex items-center flex-nowrap min-w-max">
        @foreach ($items as $index => $item)
            @if ($index > 0)
                <span class="mx-1 sm:mx-2 flex-shrink-0">&gt;&gt;</span>
            @endif

            @if (isset($item['url']))
                <a href="{{ $item['url'] }}" class="hover:text-red-600 transition-colors whitespace-nowrap">
                    {{ $item['label'] }}
                </a>
            @else
                <span class="text-gray-900 font-medium break-words">{{ $item['label'] }}</span>
            @endif
        @endforeach
    </div>
</nav>