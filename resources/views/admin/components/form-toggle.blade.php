@props(['label', 'name', 'hint' => null])

<div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg" x-data="{ checked: @entangle($attributes->wire('model')) }">
    <div>
        <label class="block text-sm font-semibold text-gray-700">
            {{ $label }}
        </label>
        @if($hint)
            <p class="text-xs text-gray-500 mt-1">{{ $hint }}</p>
        @endif
    </div>
    <button type="button"
            @click="checked = !checked"
            :class="checked ? 'bg-green-500' : 'bg-gray-300'"
            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors">
        <span :class="checked ? 'translate-x-6' : 'translate-x-1'" class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
    </button>
</div>
