@props(['label', 'name', 'type' => 'text', 'required' => false, 'hint' => null])

<div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    
    <input type="{{ $type }}" 
           wire:model="{{ $name }}"
           {{ $attributes->merge(['class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent']) }}
           @if($required) required @endif
           @error($name) 
               {{ $attributes->merge(['class' => 'border-red-500']) }}
           @enderror>
    
    @error($name)
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
    
    @if($hint)
        <p class="text-xs text-gray-500 mt-1">{{ $hint }}</p>
    @endif
</div>
