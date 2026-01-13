@props(['title', 'maxWidth' => 'md'])

@php
$maxWidthClass = match($maxWidth) {
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
    default => 'max-w-md'
};
@endphp

<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" x-data>
    <div class="bg-white rounded-lg shadow-xl {{ $maxWidthClass }} w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="bg-red-600 text-white px-6 py-4 rounded-t-lg">
            <h3 class="text-xl font-bold">{{ $title }}</h3>
        </div>

        <!-- Modal Body -->
        {{ $slot }}
    </div>
</div>
