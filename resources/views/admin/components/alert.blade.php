@props(['type' => 'success', 'message'])

@if($message)
<div class="mb-4 px-4 py-3 rounded relative border
    {{ $type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700' }}" 
    role="alert">
    <span class="block sm:inline">{{ $message }}</span>
</div>
@endif
