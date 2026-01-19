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

    <!-- Delete User Confirmation Modal -->
    <x-backend.modal name="confirm-user-deletion" :show="false" maxWidth="md">
        <form wire:submit="deleteUser" class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                คุณแน่ใจหรือไม่ว่าต้องการลบบัญชีของคุณ?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                เมื่อบัญชีของคุณถูกลบ ทรัพยากรและข้อมูลทั้งหมดจะถูกลบอย่างถาวร กรุณากรอกรหัสผ่านเพื่อยืนยันว่าคุณต้องการลบบัญชีของคุณอย่างถาวร
            </p>

            <div class="mt-6">
                <label for="password" class="sr-only">รหัสผ่าน</label>
                <input wire:model="password"
                       id="password"
                       type="password"
                       class="mt-1 block w-3/4 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                       placeholder="รหัสผ่าน">
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button"
                        x-on:click="$dispatch('close')"
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    ยกเลิก
                </button>

                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    ลบบัญชี
                </button>
            </div>
        </form>
    </x-backend.modal>
</div>
