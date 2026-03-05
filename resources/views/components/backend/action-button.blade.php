@props([
    'color' => 'gray',
    'action' => null,
    'label' => '',
    'title' => null,
    'dispatch' => false,
    'href' => null,
    'confirm' => null,
    'target' => '_self',
])
@php
    $configs = [
        'yellow' => [
            'class' => 'text-white bg-yellow-500 border-transparent hover:bg-yellow-600 focus:ring-yellow-500',
            'paths' => ['M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z'],
            'alpine' => false,
        ],
        'yellow-outline' => [
            'class' => 'focus:ring-yellow-500 border rounded-lg',
            'paths' => [
                'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
            ],
            'alpine' => true,
            'alpine_default' => 'background-color: white; color: #CA8A04; border-color: #CA8A04;',
            'alpine_hover' => 'background-color: #EAB308; color: white; border-color: #EAB308;',
        ],
        'red' => [
            'class' => 'text-white bg-red-600 border-transparent hover:bg-red-700 focus:ring-red-500',
            'paths' => ['M12 4v16m8-8H4'],
            'alpine' => false,
        ],
        'red-outline' => [
            'class' => 'text-red-600 bg-white border-red-600 hover:bg-red-600 hover:text-white focus:ring-red-500',
            'paths' => [
                'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16',
            ],
            'alpine' => false,
        ],
        'gray' => [
            'class' => 'text-gray-700 bg-white border-gray-300 hover:bg-gray-50 focus:ring-red-500',
            'paths' => ['M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4'],
            'alpine' => false,
        ],
        'blue' => [
            'class' => 'text-blue-600 bg-white border-blue-600 hover:bg-blue-600 hover:text-white focus:ring-blue-500',
            'paths' => ['M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z'],
            'alpine' => false,
        ],
        'blue-link' => [
            'class' => 'text-blue-600 bg-white border-blue-600 hover:bg-blue-600 hover:text-white focus:ring-blue-500',
            'paths' => [
                'M15 12a3 3 0 11-6 0 3 3 0 016 0z',
                'M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z',
            ],
            'alpine' => false,
        ],
        // 🔵 URL Badge — พื้นเทา ตัวหนังสือน้ำเงิน
        'url-badge' => [
            'class' =>
                'text-blue-600 bg-gray-100 border-transparent hover:bg-gray-200 focus:ring-blue-400 rounded-full px-3 py-1 text-xs font-medium',
            'paths' => [
                'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1',
            ],
            'alpine' => false,
            'badge' => true,
        ],
        // 🔴 PDF Badge — พื้นเทา ตัวหนังสือแดง
        'pdf-badge' => [
            'class' =>
                'text-red-500 bg-gray-100 border-transparent hover:bg-gray-200 focus:ring-red-400 rounded-full px-3 py-1 text-xs font-medium',
            'paths' => [
                'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z',
            ],
            'alpine' => false,
            'badge' => true,
        ],
    ];
    $config = $configs[$color] ?? $configs['gray'];
    $isBadge = $config['badge'] ?? false;
    $baseClass = $isBadge
        ? "inline-flex items-center gap-1 border focus:outline-none focus:ring-2 transition-colors {$config['class']}"
        : "inline-flex items-center px-3 py-2 text-sm font-medium border focus:outline-none focus:ring-2 transition-colors rounded-lg {$config['class']}";
@endphp

@if ($href)
    @if ($config['alpine'])
        <a href="{{ $href }}" target="{{ $target }}" title="{{ $title ?? $label }}" x-data="{ hovered: false }"
            @mouseenter="hovered = true" @mouseleave="hovered = false"
            :style="hovered ? '{{ $config['alpine_hover'] }}' : '{{ $config['alpine_default'] }}'"
            style="{{ $config['alpine_default'] }}" class="{{ $baseClass }}">
            <svg class="w-4 h-4 {{ $isBadge ? '' : 'mr-1.5' }} flex-shrink-0" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                @foreach ($config['paths'] as $path)
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}" />
                @endforeach
            </svg>
            {{ $label }}
        </a>
    @else
        <a href="{{ $href }}" target="{{ $target }}" title="{{ $title ?? $label }}"
            class="{{ $baseClass }}">
            <svg class="w-4 h-4 {{ $isBadge ? '' : 'mr-1.5' }} flex-shrink-0" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                @foreach ($config['paths'] as $path)
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}" />
                @endforeach
            </svg>
            {{ $label }}
        </a>
    @endif
@elseif($confirm)
    {{-- ปุ่มที่มี Custom Confirm Modal --}}
    <div x-data="{ showConfirm: false }" class="inline-flex">
        {{-- ปุ่มที่แสดงให้กดเปิด modal --}}
        <button @click.prevent="showConfirm = true" title="{{ $title ?? $label }}" class="{{ $baseClass }}"
            type="button">
            <svg class="w-4 h-4 {{ $isBadge ? '' : 'mr-1.5' }} flex-shrink-0" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                @foreach ($config['paths'] as $path)
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}" />
                @endforeach
            </svg>
            {{ $label }}
        </button>

        {{-- ปุ่มซ่อนที่มี wire:click จริงๆ --}}
        <button x-ref="realActionBtn"
            @if ($dispatch) wire:click="$dispatch('{{ $action }}')"
            @else
                wire:click="{{ $action }}" @endif
            type="button" style="position:absolute;width:0;height:0;overflow:hidden;opacity:0;pointer-events:none;"
            tabindex="-1" aria-hidden="true">
        </button>

        {{-- Custom Confirm Modal (teleport ไป body) --}}
        <template x-teleport="body">
            <div x-show="showConfirm" style="display:none;"
                class="fixed inset-0 z-[200] flex items-center justify-center p-4">
                {{-- Overlay --}}
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showConfirm = false">
                </div>
                {{-- Modal Box --}}
                <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-sm p-6" style="z-index:201;"
                    @click.stop>
                    {{-- ไอคอนถังขยะ --}}
                    <div class="flex items-center justify-center w-14 h-14 rounded-full bg-red-100 mx-auto mb-4">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">ยืนยันการลบ</h3>
                    <p class="text-sm text-gray-500 text-center mb-6">{{ $confirm }}</p>
                    {{-- ปุ่ม --}}
                    <div class="flex gap-3">
                        <button @click="showConfirm = false" type="button"
                            class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none transition-colors">
                            ยกเลิก
                        </button>
                        <button @click="showConfirm = false; $nextTick(() => { $refs.realActionBtn.click() })"
                            type="button"
                            class="flex-1 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none transition-colors">
                            ลบ
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
@else
    @if ($config['alpine'])
        <button
            @if ($action) @if ($dispatch)
                    wire:click="$dispatch('{{ $action }}')"
                @else
                    wire:click="{{ $action }}" @endif
            @endif
            title="{{ $title ?? $label }}"
            x-data="{ hovered: false }"
            @mouseenter="hovered = true"
            @mouseleave="hovered = false"
            :style="hovered ? '{{ $config['alpine_hover'] }}' : '{{ $config['alpine_default'] }}'"
            style="{{ $config['alpine_default'] }}"
            class="{{ $baseClass }}">
            <svg class="w-4 h-4 {{ $isBadge ? '' : 'mr-1.5' }} flex-shrink-0" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                @foreach ($config['paths'] as $path)
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}" />
                @endforeach
            </svg>
            {{ $label }}
        </button>
    @else
        <button
            @if ($action) @if ($dispatch)
                    wire:click="$dispatch('{{ $action }}')"
                @else
                    wire:click="{{ $action }}" @endif
            @endif
            title="{{ $title ?? $label }}"
            class="{{ $baseClass }}">
            <svg class="w-4 h-4 {{ $isBadge ? '' : 'mr-1.5' }} flex-shrink-0" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                @foreach ($config['paths'] as $path)
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}" />
                @endforeach
            </svg>
            {{ $label }}
        </button>
    @endif
@endif
