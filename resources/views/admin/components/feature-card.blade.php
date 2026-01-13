@props(['title', 'description', 'color' => 'blue', 'href' => '#'])

@php
    $buttonClasses = match($color) {
        'blue' => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-300',
        'purple' => 'bg-purple-600 hover:bg-purple-700 focus:ring-purple-300',
        'orange' => 'bg-orange-600 hover:bg-orange-700 focus:ring-orange-300',
        'green' => 'bg-green-600 hover:bg-green-700 focus:ring-green-300',
        'red' => 'bg-red-600 hover:bg-red-700 focus:ring-red-300',
        default => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-300',
    };
@endphp

<a href="{{ $href }}" class="block">
    <div class="bg-white block p-6 border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
        <h5 class="mb-3 text-2xl font-semibold tracking-tight text-gray-900 leading-8">{{ $title }}</h5>
        <p class="text-gray-600 mb-6">{{ $description }}</p>
        
        <!-- Slot for additional content -->
        @if($slot->isNotEmpty())
            <div class="mb-6">
                {{ $slot }}
            </div>
        @endif
        
        <div class="inline-flex items-center text-white {{ $buttonClasses }} border border-transparent focus:ring-4 shadow-sm font-medium leading-5 rounded-lg text-sm px-4 py-2.5 focus:outline-none transition-colors">
            จัดการ
            <svg class="w-4 h-4 ms-1.5 -me-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4"/>
            </svg>
        </div>
    </div>
</a>
