<div x-data="notificationHandler()" 
     x-init="init()"
     @notify.window="notify($event.detail)"
     x-show="show" 
     class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
     style="display: none;"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    
    <!-- Backdrop with smooth transition -->
    <div @click="close()" 
         class="absolute inset-0 bg-gray-900 bg-opacity-50"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"></div>
    
    <!-- Modal content with slide animation -->
    <div class="relative bg-white border rounded-lg shadow-2xl p-4 md:p-6 max-w-md w-full mx-4"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4">
        <button @click="close()" 
                type="button"
                class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-9 h-9 ms-auto inline-flex justify-center items-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L17.94 6M18 18L6.06 6"/>
            </svg>
            <span class="sr-only">ปิด</span>
        </button>
        
        <div class="p-4 md:p-5 text-center">
            <!-- Icon -->
            <div class="mx-auto mb-4 w-12 h-12 flex items-center justify-center">
                <!-- Success Icon -->
                <svg x-show="type === 'success'" class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <!-- Error Icon -->
                <svg x-show="type === 'error'" class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <!-- Warning Icon -->
                <svg x-show="type === 'warning'" class="w-12 h-12 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <!-- Info Icon -->
                <svg x-show="type === 'info'" class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            
            <!-- Message -->
            <h3 class="mb-6 text-lg font-normal text-gray-700" x-text="message"></h3>
            
            <!-- Close Button -->
            <button @click="close()" 
                    type="button"
                    :class="{
                        'bg-green-600 hover:bg-green-700 focus:ring-green-300': type === 'success',
                        'bg-red-600 hover:bg-red-700 focus:ring-red-300': type === 'error',
                        'bg-blue-600 hover:bg-blue-700 focus:ring-blue-300': type === 'info',
                        'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-300': type === 'warning'
                    }"
                    class="text-white font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none focus:ring-4">
                ตกลง
            </button>
        </div>
    </div>
</div>

<script>
function notificationHandler() {
    return {
        show: false,
        message: '',
        type: 'success',
        timeout: null,
        
        init() {
            // Check for Laravel flash messages on page load
            @if(session()->has('message'))
                this.notify({
                    message: @js(session('message')),
                    type: 'success'
                });
            @endif
            
            @if(session()->has('success'))
                this.notify({
                    message: @js(session('success')),
                    type: 'success'
                });
            @endif
            
            @if(session()->has('error'))
                this.notify({
                    message: @js(session('error')),
                    type: 'error'
                });
            @endif
            
            @if(session()->has('warning'))
                this.notify({
                    message: @js(session('warning')),
                    type: 'warning'
                });
            @endif
            
            @if(session()->has('info'))
                this.notify({
                    message: @js(session('info')),
                    type: 'info'
                });
            @endif
        },
        
        notify(detail) {
            this.message = detail.message;
            this.type = detail.type || 'success';
            this.show = true;
            
            // Clear existing timeout
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            
            // Auto close after 5 seconds
            this.timeout = setTimeout(() => {
                this.close();
            }, 5000);
        },
        
        close() {
            this.show = false;
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
        }
    }
}
</script>
