<!-- Notification Component -->
<div x-data="{ 
        show: false, 
        message: '', 
        type: 'success',
        init() {
            window.addEventListener('notify', event => {
                this.message = event.detail.message || event.detail[0]?.message || 'Success';
                this.type = event.detail.type || event.detail[0]?.type || 'success';
                this.show = true;
                setTimeout(() => this.show = false, 3000);
            });
            
            @if(session()->has('message'))
                this.message = '{{ session('message') }}';
                this.type = 'success';
                this.show = true;
                setTimeout(() => this.show = false, 3000);
            @endif
        }
    }" 
    x-show="show"
    class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4"
    style="display: none;"
    @keydown.escape.window="show = false">
    
    <!-- Backdrop -->
    <div x-show="show"
         class="fixed inset-0 bg-gray-500 bg-opacity-75" 
         @click="show = false">
    </div>

    <!-- Modal Content -->
    <div x-show="show"
         class="relative w-full max-w-md bg-white rounded-2xl overflow-hidden pointer-events-auto">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200"
             :class="{
                'bg-green-50': type === 'success',
                'bg-red-50': type === 'error',
                'bg-blue-50': type === 'info',
                'bg-yellow-50': type === 'warning'
             }">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <!-- Icon -->
                    <div class="flex-shrink-0">
                        <svg x-show="type === 'success'" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <svg x-show="type === 'error'" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <svg x-show="type === 'info'" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <svg x-show="type === 'warning'" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    
                    <!-- Title -->
                    <h3 class="text-lg font-medium"
                        :class="{
                            'text-green-900': type === 'success',
                            'text-red-900': type === 'error',
                            'text-blue-900': type === 'info',
                            'text-yellow-900': type === 'warning'
                        }">
                        <span x-show="type === 'success'">สำเร็จ</span>
                        <span x-show="type === 'error'">ข้อผิดพลาด</span>
                        <span x-show="type === 'info'">ข้อมูล</span>
                        <span x-show="type === 'warning'">คำเตือน</span>
                    </h3>
                </div>
                
                <!-- Close Button -->
                <button @click="show = false" 
                        class="flex-shrink-0 text-gray-400 hover:text-gray-500 focus:outline-none">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Message Body -->
        <div class="px-6 py-4">
            <p class="text-sm text-gray-700" x-text="message"></p>
        </div>

        <!-- Footer with OK Button -->
        <div class="px-6 py-4 bg-gray-50 flex justify-end">
            <button @click="show = false" 
                    class="px-4 py-2 text-sm font-medium text-white rounded-lg focus:outline-none focus:ring-2"
                    :class="{
                        'bg-green-600 hover:bg-green-700 focus:ring-green-500': type === 'success',
                        'bg-red-600 hover:bg-red-700 focus:ring-red-500': type === 'error',
                        'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500': type === 'info',
                        'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500': type === 'warning'
                    }">
                ตกลง
            </button>
        </div>
    </div>
</div>
