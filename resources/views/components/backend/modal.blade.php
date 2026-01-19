@props(['show' => false, 'title' => '', 'maxWidth' => 'lg', 'closeEvent' => 'showModal'])

@php
$maxWidthClass = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
][$maxWidth];
@endphp

@if($show)
<div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="$set('{{ $closeEvent }}', false)"></div>

    <!-- Modal panel -->
    <div class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all w-full {{ $maxWidthClass }}">
        {{ $slot }}
    </div>
</div>
@endif
