<div>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            อัปเดตรหัสผ่าน
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            ตรวจสอบให้แน่ใจว่าบัญชีของคุณใช้รหัสผ่านที่ยาวและสุ่มเพื่อความปลอดภัย
        </p>
    </header>

    @if (session()->has('message'))
        <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-sm text-green-600">{{ session('message') }}</p>
        </div>
    @endif

    <form wire:submit="updatePassword" class="mt-6 space-y-6">
        <div>
            <label for="current_password" class="block text-sm font-medium text-gray-700">รหัสผ่านปัจจุบัน</label>
            <input wire:model="current_password" 
                   id="current_password" 
                   type="password" 
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" 
                   autocomplete="current-password">
            @error('current_password') 
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">รหัสผ่านใหม่</label>
            <input wire:model="password" 
                   id="password" 
                   type="password" 
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" 
                   autocomplete="new-password">
            @error('password') 
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">ยืนยันรหัสผ่าน</label>
            <input wire:model="password_confirmation" 
                   id="password_confirmation" 
                   type="password" 
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" 
                   autocomplete="new-password">
            @error('password_confirmation') 
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" 
                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                บันทึก
            </button>
        </div>
    </form>
</div>
