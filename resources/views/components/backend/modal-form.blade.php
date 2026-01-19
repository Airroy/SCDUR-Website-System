@props(['title' => '', 'submitLabel' => 'บันทึก', 'cancelLabel' => 'ยกเลิก', 'closeEvent' => 'showModal'])

<form {{ $attributes }}>
    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
        @if($title)
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">{{ $title }}</h3>
        @endif
        
        {{ $slot }}
    </div>

    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
        <button 
            type="submit"
            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
            wire:loading.attr="disabled"
        >
            <span wire:loading.remove wire:target="{{ $attributes->get('wire:submit') }}">
                {{ $submitLabel }}
            </span>
            <span wire:loading wire:target="{{ $attributes->get('wire:submit') }}">
                กำลังบันทึก...
            </span>
        </button>
        <button 
            type="button"
            wire:click="$set('{{ $closeEvent }}', false)"
            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
        >
            {{ $cancelLabel }}
        </button>
    </div>
</form>
