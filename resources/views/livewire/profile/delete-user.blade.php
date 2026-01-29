<div>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            ลบบัญชี
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            เมื่อบัญชีของคุณถูกลบ ทรัพยากรและข้อมูลทั้งหมดจะถูกลบอย่างถาวร ก่อนลบบัญชี กรุณาดาวน์โหลดข้อมูลหรือข้อมูลใดๆ ที่คุณต้องการเก็บไว้
        </p>
    </header>

    <div class="mt-6">
        <button type="button" 
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
            ลบบัญชี
        </button>
    </div>

    <!-- ใช้ modal-alpine แทน modal -->
    <x-backend.modal-alpine name="confirm-user-deletion" maxWidth="md">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                คุณแน่ใจหรือไม่ว่าต้องการลบบัญชีของคุณ?
            </h3>

            <p class="mt-1 text-sm text-gray-600 mb-4">
                เมื่อบัญชีของคุณถูกลบ ทรัพยากรและข้อมูลทั้งหมดจะถูกลบอย่างถาวร กรุณากรอกรหัสผ่านเพื่อยืนยันว่าคุณต้องการลบบัญชีของคุณอย่างถาวร
            </p>

            <div class="mt-4">
                <label for="password" class="sr-only">รหัสผ่าน</label>
                <input wire:model="password"
                       id="password"
                       type="password"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                       placeholder="รหัสผ่าน">
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button 
                type="button"
                wire:click="deleteUser"
                wire:loading.attr="disabled"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50"
            >
                <span wire:loading.remove wire:target="deleteUser">
                    ลบบัญชี
                </span>
                <span wire:loading wire:target="deleteUser">
                    กำลังลบ...
                </span>
            </button>
            <button 
                type="button"
                x-on:click="$dispatch('close')"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
            >
                ยกเลิก
            </button>
        </div>
    </x-backend.modal-alpine>
</div>