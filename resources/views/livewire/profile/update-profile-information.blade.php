<div>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            ข้อมูลโปรไฟล์
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            อัปเดตข้อมูลโปรไฟล์และที่อยู่อีเมลของคุณ
        </p>
    </header>

    @if (session()->has('message'))
        <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-sm text-green-600">{{ session('message') }}</p>
        </div>
    @endif

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">ชื่อ</label>
            <input wire:model="name" 
                   id="name" 
                   type="text" 
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" 
                   required autofocus>
            @error('name') 
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">อีเมล</label>
            <input wire:model="email" 
                   id="email" 
                   type="email" 
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" 
                   required>
            @error('email') 
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-gray-800">
                        ที่อยู่อีเมลของคุณยังไม่ได้รับการยืนยัน
                        <button wire:click.prevent="sendVerification" 
                                type="button"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            คลิกที่นี่เพื่อส่งอีเมลยืนยันอีกครั้ง
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            ลิงก์ยืนยันใหม่ได้ถูกส่งไปยังที่อยู่อีเมลของคุณแล้ว
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" 
                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                บันทึก
            </button>
        </div>
    </form>
</div>
