<div x-data="confirmHandler()" 
     @confirm.window="showConfirm($event.detail)"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-[9999] flex justify-center items-center bg-black bg-opacity-50"
     style="display: none;">
    
    <div @click.away="cancel()"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="relative p-4 w-full max-w-md max-h-full">
        
        <div class="relative bg-white border border-gray-200 rounded-lg shadow-sm p-4 md:p-6">
            <button @click="cancel()" 
                    type="button" 
                    class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-9 h-9 ms-auto inline-flex justify-center items-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L17.94 6M18 18L6.06 6"/>
                </svg>
                <span class="sr-only">ปิด</span>
            </button>
            
            <div class="p-4 md:p-5 text-center">
                <!-- Warning Icon -->
                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                
                <!-- Message -->
                <h3 class="mb-6 text-lg font-normal text-gray-700" x-html="message"></h3>
                
                <!-- Buttons -->
                <div class="flex items-center space-x-4 justify-center">
                    <button @click="confirm()" 
                            type="button" 
                            class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">
                        ใช่, ฉันแน่ใจ
                    </button>
                    <button @click="cancel()" 
                            type="button" 
                            class="text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">
                        ไม่, ยกเลิก
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmHandler() {
    return {
        show: false,
        message: '',
        callback: null,
        
        showConfirm(detail) {
            this.message = detail.message;
            this.callback = detail.callback;
            this.show = true;
        },
        
        confirm() {
            if (this.callback && typeof this.callback === 'function') {
                this.callback();
            }
            this.close();
        },
        
        cancel() {
            this.close();
        },
        
        close() {
            this.show = false;
            this.message = '';
            this.callback = null;
        }
    }
}
</script>
