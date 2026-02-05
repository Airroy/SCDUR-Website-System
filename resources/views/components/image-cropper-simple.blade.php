@props([
    'name' => 'croppedImage',
    'label' => 'รูปภาพ',
    'required' => false,
    'existingImage' => null,
    'aspectRatio' => '1140/428',
    'outputWidth' => 1140,
    'outputHeight' => 428,
    'helpText' => null,
])

@php
    $aspectRatioLabel = $outputWidth . 'x' . $outputHeight;
    $aspectRatioValue = $outputWidth / $outputHeight;
@endphp

<div x-data="{
    cropper: null,
    showCropper: false,
    imagePreview: null,
    croppedPreview: null,
    originalFile: null,
    isAutoCropped: false,

    init() {
        console.log('Image cropper initialized');
    },

    loadImage(event) {
        const file = event.target.files[0];
        if (!file) return;

        if (!file.type.startsWith('image/')) {
            alert('กรุณาเลือกไฟล์รูปภาพ');
            event.target.value = '';
            return;
        }

        if (file.size > 10 * 1024 * 1024) {
            alert('ไฟล์รูปภาพมีขนาดใหญ่เกิน 10MB');
            event.target.value = '';
            return;
        }

        this.originalFile = file;
        this.isAutoCropped = false;
        const reader = new FileReader();
        reader.onload = (e) => {
            this.imagePreview = e.target.result;
            this.croppedPreview = null;
            // ครอปอัตโนมัติหลังจากโหลดรูปเสร็จ
            this.$nextTick(() => {
                this.autoCrop();
            });
        };
        reader.readAsDataURL(file);
    },

    autoCrop() {
        if (!this.imagePreview) return;

        const img = new Image();
        img.onload = () => {
            // คำนวณขนาดและตำแหน่งครอปอัตโนมัติ (center crop)
            const targetRatio = {{ $outputWidth }} / {{ $outputHeight }};
            const imgRatio = img.width / img.height;

            let cropWidth, cropHeight, cropX, cropY;

            if (imgRatio > targetRatio) {
                // รูปกว้างกว่า target ratio -> ครอปซ้ายขวา
                cropHeight = img.height;
                cropWidth = img.height * targetRatio;
                cropX = (img.width - cropWidth) / 2;
                cropY = 0;
            } else {
                // รูปสูงกว่า target ratio -> ครอปบนล่าง
                cropWidth = img.width;
                cropHeight = img.width / targetRatio;
                cropX = 0;
                cropY = (img.height - cropHeight) / 2;
            }

            // สร้าง canvas สำหรับครอป
            const canvas = document.createElement('canvas');
            canvas.width = {{ $outputWidth }};
            canvas.height = {{ $outputHeight }};
            const ctx = canvas.getContext('2d');

            // เติมพื้นหลังขาว
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // วาดรูปที่ครอปแล้ว
            ctx.drawImage(
                img,
                cropX, cropY, cropWidth, cropHeight,
                0, 0, {{ $outputWidth }}, {{ $outputHeight }}
            );

            // บันทึกผลลัพธ์
            const croppedImageData = canvas.toDataURL('image/png');
            this.croppedPreview = croppedImageData;
            this.isAutoCropped = true;

            // ส่งข้อมูลไปยัง Livewire
            const hiddenInput = document.getElementById('{{ $name }}_hidden');
            if (hiddenInput) {
                hiddenInput.value = croppedImageData;
                hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
            }
        };
        img.src = this.imagePreview;
    },

    openCropper() {
        if (!this.imagePreview) return;

        if (typeof Cropper === 'undefined') {
            alert('ไม่สามารถโหลดเครื่องมือครอปรูปได้ กรุณารีเฟรชหน้าเว็บ');
            return;
        }

        this.showCropper = true;
        this.$nextTick(() => {
            setTimeout(() => this.initCropper(), 100);
        });
    },

    initCropper() {
        const image = this.$refs.cropperImage;
        if (!image) return;

        if (this.cropper) {
            this.cropper.destroy();
        }

        this.cropper = new Cropper(image, {
            aspectRatio: {{ $aspectRatioValue }},
            viewMode: 2,
            dragMode: 'move',
            autoCropArea: 1,
            guides: true,
            center: true,
            highlight: true,
            cropBoxMovable: true,
            cropBoxResizable: true,
            zoomOnWheel: true,
            responsive: true,
            background: true
        });
    },

    applyCrop() {
        if (!this.cropper) {
            alert('ไม่พบเครื่องมือครอปรูป');
            return;
        }

        // บังคับ output เป็นขนาดที่กำหนดเสมอ (1140x428)
        // จะ upscale ให้อัตโนมัติถ้ารูปเล็กกว่า
        const canvas = this.cropper.getCroppedCanvas({
            width: {{ $outputWidth }},
            height: {{ $outputHeight }},
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
            fillColor: '#ffffff'
        });

        if (!canvas) {
            alert('ไม่สามารถครอปรูปภาพได้');
            return;
        }

        // ใช้ PNG เพื่อรักษาคุณภาพสูงสุด (lossless)
        const croppedImageData = canvas.toDataURL('image/png');
        this.croppedPreview = croppedImageData;

        // ส่งข้อมูลไปยัง Livewire ผ่าน hidden input
        const hiddenInput = document.getElementById('{{ $name }}_hidden');
        if (hiddenInput) {
            hiddenInput.value = croppedImageData;
            hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
        }

        this.closeCropper();
    },

    closeCropper() {
        this.showCropper = false;
        if (this.cropper) {
            this.cropper.destroy();
            this.cropper = null;
        }
    },

    clearImage() {
        this.imagePreview = null;
        this.croppedPreview = null;
        this.originalFile = null;
        this.isAutoCropped = false;
        if (this.$refs.fileInput) {
            this.$refs.fileInput.value = '';
        }
        this.$wire.set('{{ $name }}', '');
    },

    zoomIn() {
        if (this.cropper) this.cropper.zoom(0.1);
    },

    zoomOut() {
        if (this.cropper) this.cropper.zoom(-0.1);
    },

    resetCrop() {
        if (this.cropper) this.cropper.reset();
    }
}" class="space-y-3">

    {{-- รูปภาพปัจจุบัน (Edit Mode) --}}
    @if ($existingImage)
        <div x-show="!imagePreview && !croppedPreview" class="mb-3">
            <p class="text-sm text-gray-600 mb-2">รูปภาพปัจจุบัน</p>
            <img src="{{ $existingImage }}" alt="Current image"
                class="w-full h-48 object-cover rounded-lg border-2 border-gray-300">
        </div>
    @endif

    {{-- Preview รูปที่ครอปอัตโนมัติแล้ว --}}
    <div x-show="croppedPreview" x-cloak>
        <div class="bg-green-50 border-2 border-green-500 rounded-lg p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2 text-green-700">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-semibold"
                        x-text="isAutoCropped ? 'ครอปอัตโนมัติเรียบร้อย ({{ $aspectRatioLabel }} px)' : 'ครอปรูปภาพเรียบร้อยแล้ว ({{ $aspectRatioLabel }} px)'"></span>
                </div>
                <button type="button" @click="clearImage()"
                    class="px-3 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    ลบรูป
                </button>
            </div>
            <img :src="croppedPreview" alt="Cropped preview" class="w-full h-48 object-cover rounded-lg">

            <div class="mt-3 flex justify-end">
                <button type="button" @click="openCropper()"
                    class="px-4 py-2 bg-gray-600 text-white text-sm rounded-lg hover:bg-gray-700 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z">
                        </path>
                    </svg>
                    ปรับครอปเอง
                </button>
            </div>
        </div>
    </div>

    {{-- File Input --}}
    <input type="file" x-ref="fileInput" @change="loadImage($event)" accept="image/*"
        x-show="!imagePreview && !croppedPreview"
        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">

    {{-- Hidden input สำหรับ sync ข้อมูล base64 กับ Livewire --}}
    <input type="hidden" id="{{ $name }}_hidden" wire:model="{{ $name }}">

    <div x-show="!imagePreview && !croppedPreview" class="mt-2">
        @if ($helpText)
            <p class="text-xs text-gray-500">{{ $helpText }}</p>
        @else
            <p class="text-xs text-gray-500">
                รองรับไฟล์ JPG, PNG ขนาดไม่เกิน 10 MB (แนะนำขนาด {{ $aspectRatioLabel }} px สูงสุด 10MB)
            </p>
        @endif
    </div>

    {{-- Error Message --}}
    @error($name)
        <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror

    {{-- Cropper Modal --}}
    <div x-show="showCropper" x-cloak
        class="fixed inset-0 z-[60] flex items-center justify-center bg-black bg-opacity-75 p-4"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        @keydown.escape.window="closeCropper()" style="display: none;">

        <div class="bg-white rounded-xl shadow-2xl max-w-5xl w-full max-h-[95vh] flex flex-col"
            @click.outside="closeCropper()">

            {{-- Header --}}
            <div class="p-5 border-b bg-gradient-to-r from-red-600 to-red-700 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <div class="text-white">
                        <h3 class="text-xl font-bold flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z">
                                </path>
                            </svg>
                            ครอปรูปภาพ ({{ $aspectRatioLabel }})
                        </h3>
                        <p class="text-red-100 text-sm mt-1">ลากเพื่อปรับตำแหน่ง หรือใช้ scroll เพื่อซูม</p>
                    </div>
                    <button type="button" @click="closeCropper()"
                        class="text-white hover:bg-white/20 rounded-full p-2 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Image Container --}}
            <div class="flex-1 p-5 overflow-auto bg-gray-100">
                <div class="bg-white rounded-lg shadow-inner p-2" style="max-height: 60vh;">
                    <img x-ref="cropperImage" :src="imagePreview" class="max-w-full block mx-auto"
                        style="max-height: 55vh;">
                </div>
            </div>

            {{-- Instructions --}}
            <div class="px-5 py-3 bg-red-50 border-t border-b">
                <div class="flex items-center gap-2 text-sm text-red-800">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span><strong>คำแนะนำ:</strong> ลากรูปภาพเพื่อปรับตำแหน่ง
                        หรือใช้ลูกกลิ้งเมาส์/ปุ่มด้านล่างเพื่อซูมเข้า-ออก</span>
                </div>
            </div>

            {{-- Controls --}}
            <div class="p-5 bg-gray-50 rounded-b-xl">
                <div class="flex items-center justify-between">
                    {{-- Zoom Controls --}}
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600 mr-2">ซูม:</span>
                        <button type="button" @click="zoomOut()"
                            class="p-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition"
                            title="ซูมออก">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"></path>
                            </svg>
                        </button>
                        <button type="button" @click="zoomIn()"
                            class="p-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition"
                            title="ซูมเข้า">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"></path>
                            </svg>
                        </button>
                        <button type="button" @click="resetCrop()"
                            class="p-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition"
                            title="รีเซ็ต">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                        </button>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center gap-3">
                        <button type="button" @click="closeCropper()"
                            class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition font-medium">
                            ยกเลิก
                        </button>
                        <button type="button" @click="applyCrop()"
                            class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            ใช้รูปนี้
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
