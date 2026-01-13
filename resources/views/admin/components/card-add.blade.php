@props([
    'title' => 'เพิ่มใหม่',
    'subtitle' => 'คลิกเพื่อเพิ่ม',
    'color' => 'red', // red, blue, green, orange, etc.
])

@php
$colorClasses = [
    'red' => [
        'border' => 'hover:border-red-500',
        'bg' => 'hover:bg-red-50',
        'iconBg' => 'bg-gray-100 group-hover:bg-gray-200',
        'iconColor' => 'text-gray-600',
        'titleColor' => 'group-hover:text-red-600',
    ],
    'blue' => [
        'border' => 'hover:border-blue-500',
        'bg' => 'hover:bg-blue-50',
        'iconBg' => 'bg-gray-100 group-hover:bg-gray-200',
        'iconColor' => 'text-gray-600',
        'titleColor' => 'group-hover:text-blue-600',
    ],
    'green' => [
        'border' => 'hover:border-green-500',
        'bg' => 'hover:bg-green-50',
        'iconBg' => 'bg-gray-100 group-hover:bg-gray-200',
        'iconColor' => 'text-gray-600',
        'titleColor' => 'group-hover:text-green-600',
    ],
];

$colors = $colorClasses[$color] ?? $colorClasses['red'];
@endphp

<button 
    {{ $attributes->merge([
        'class' => "bg-white border-2 border-dashed border-gray-300 rounded-lg 
                    {$colors['border']} {$colors['bg']} 
                    transition-all duration-200 group 
                    min-h-[80px] flex items-center justify-between p-4 sm:p-6"
    ]) }}
>
    <div class="flex items-center gap-3 sm:gap-4 flex-1">
        <div class="w-10 h-10 sm:w-12 sm:h-12 {{ $colors['iconBg'] }} rounded-lg flex items-center justify-center flex-shrink-0 transition-colors">
            <svg class="w-5 h-5 sm:w-6 sm:h-6 {{ $colors['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
        </div>
        <div class="text-left flex-1 min-w-0">
            <h3 class="text-sm sm:text-base font-semibold text-gray-700 {{ $colors['titleColor'] }} transition-colors truncate">
                {{ $title }}
            </h3>
            <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $subtitle }}</p>
        </div>
    </div>
    
</button>
