@props([
    'icon' => null,
    'title' => '',
    'subtitle' => '',
])

<div {{ $attributes->merge(['class' => 'bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow min-h-[80px] flex items-center p-4 sm:p-6 relative group']) }}>
    <div class="flex items-center gap-3 sm:gap-4 flex-1 min-w-0">
        {{-- Icon/Image Section --}}
        @if($icon ?? false)
            <div class="flex-shrink-0">
                {{ $icon }}
            </div>
        @endif

        {{-- Content Section --}}
        <div class="flex-1 min-w-0">
            {{-- Title with truncation --}}
            <h3 class="text-sm sm:text-base font-semibold text-gray-900 truncate" title="{{ $title }}">
                {{ $title }}
            </h3>
            
            {{-- Subtitle (optional) --}}
            @if($subtitle)
                <p class="text-xs text-gray-500 mt-0.5 truncate">
                    {{ $subtitle }}
                </p>
            @endif

            {{-- Additional content slot --}}
            @if(isset($content))
                <div class="mt-0.5">
                    {{ $content }}
                </div>
            @endif
        </div>
    </div>

    {{-- Actions Section (three-dot menu, buttons, chevron, etc.) --}}
    @if(isset($actions))
        <div class="flex-shrink-0 ml-2">
            {{ $actions }}
        </div>
    @else
        {{-- Default chevron icon if no actions provided --}}
        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-400 group-hover:text-gray-600 transition-colors flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    @endif
</div>
