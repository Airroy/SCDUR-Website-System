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

        if (file.size > 100 * 1024 * 1024) {
            alert('ไฟล์รูปภาพมีขนาดใหญ่เกิน 100MB');
            event.target.value = '';
            return;
        }

        this.originalFile = file;
        this.isAutoCropped = false;
        const reader = new FileReader();
        reader.onload = (e) => {
            this.imagePreview = e.target.result;
            this.croppedPreview = null;
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
            const targetRatio = {{ $outputWidth }} / {{ $outputHeight }};
            const imgRatio = img.width / img.height;

            let cropWidth, cropHeight, cropX, cropY;

            if (imgRatio > targetRatio) {
                cropHeight = img.height;
                cropWidth = img.height * targetRatio;
                cropX = (img.width - cropWidth) / 2;
                cropY = 0;
            } else {
                cropWidth = img.width;
                cropHeight = img.width / targetRatio;
                cropX = 0;
                cropY = (img.height - cropHeight) / 2;
            }

            const canvas = document.createElement('canvas');
            canvas.width = {{ $outputWidth }};
            canvas.height = {{ $outputHeight }};
            const ctx = canvas.getContext('2d');

            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(img, cropX, cropY, cropWidth, cropHeight, 0, 0, {{ $outputWidth }}, {{ $outputHeight }});

            const croppedImageData = canvas.toDataURL('image/png');
            this.croppedPreview = croppedImageData;
            this.isAutoCropped = true;

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

        const modalId = '{{ $name }}_cropper_modal';
        const existing = document.getElementById(modalId);
        if (existing) existing.remove();

        const isMobile = window.innerWidth < 640;

        const modal = document.createElement('div');
        modal.id = modalId;
        modal.style.cssText = 'position:fixed;inset:0;z-index:2147483647;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,0.75);padding:' + (isMobile ? '0.75rem' : '1rem') + ';';

        modal.innerHTML = `
            <div style='
                background:#fff;
                border-radius:0.75rem;
                box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);
                width:100%;
                max-width:${isMobile ? '100%' : '64rem'};
                max-height:${isMobile ? 'calc(100dvh - 1.5rem)' : '95vh'};
                display:flex;
                flex-direction:column;
                overflow:hidden;
            '>
                <!-- Header -->
                <div style='
                    padding:${isMobile ? '1rem' : '1.25rem'};
                    border-bottom:1px solid #e5e7eb;
                    background:linear-gradient(to right,var(--brand-red),var(--brand-red-medium));
                    flex-shrink:0;
                '>
                    <div style='display:flex;align-items:center;justify-content:space-between;'>
                        <div style='color:#fff;'>
                            <h3 style='font-size:${isMobile ? '1rem' : '1.25rem'};font-weight:700;margin:0;display:flex;align-items:center;gap:0.5rem;'>
                                <svg width='20' height='20' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z'/></svg>
                                ครอปรูปภาพ ({{ $aspectRatioLabel }})
                            </h3>
                            <p style='font-size:0.75rem;color:var(--brand-red-light);margin:0.25rem 0 0;'>
                                ${isMobile ? 'Pinch เพื่อซูม หรือลากเพื่อปรับตำแหน่ง' : 'ลากเพื่อปรับตำแหน่ง หรือใช้ scroll เพื่อซูม'}
                            </p>
                        </div>
                        <button id='${modalId}_close' style='color:#fff;background:rgba(255,255,255,0.2);border:none;cursor:pointer;padding:0.5rem;border-radius:9999px;display:flex;align-items:center;justify-content:center;'>
                            <svg width='20' height='20' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12'/></svg>
                        </button>
                    </div>
                </div>

                <!-- Canvas Area -->
                <div style='flex:1;overflow:auto;background:#1f2937;min-height:0;'>
                    <div style='width:100%;height:100%;min-height:${isMobile ? '40vh' : '50vh'};'>
                        <img id='${modalId}_img' style='max-width:100%;display:block;'>
                    </div>
                </div>

                <!-- Hint -->
                <div style='padding:0.625rem 1rem;background:var(--brand-red-lightest);border-top:1px solid var(--brand-red-light);flex-shrink:0;'>
                    <div style='display:flex;align-items:center;gap:0.5rem;font-size:0.75rem;color:var(--brand-red-darker);'>
                        <svg width='16' height='16' fill='currentColor' viewBox='0 0 20 20' style='flex-shrink:0;'><path fill-rule='evenodd' d='M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z' clip-rule='evenodd'/></svg>
                        <span>${isMobile ? 'ใช้นิ้วสองนิ้ว Pinch เพื่อซูม ลากเพื่อปรับตำแหน่ง' : 'ลากรูปภาพเพื่อปรับตำแหน่ง หรือใช้ลูกกลิ้งเมาส์เพื่อซูม'}</span>
                    </div>
                </div>

                <!-- Footer -->
                <div style='
                    padding:${isMobile ? '0.875rem 1rem' : '1rem 1.25rem'};
                    background:#f9fafb;
                    border-top:1px solid #e5e7eb;
                    flex-shrink:0;
                    display:flex;
                    align-items:center;
                    justify-content:${isMobile ? 'space-between' : 'space-between'};
                    gap:0.75rem;
                '>
                    <!-- Zoom Buttons -->
                    <div style='display:flex;align-items:center;gap:0.375rem;'>
                        <button id='${modalId}_zoomout' title='ซูมออก' style='padding:0.5rem;background:#fff;border:1px solid #d1d5db;border-radius:0.5rem;cursor:pointer;display:flex;'>
                            <svg width='18' height='18' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7'/></svg>
                        </button>
                        <button id='${modalId}_zoomin' title='ซูมเข้า' style='padding:0.5rem;background:#fff;border:1px solid #d1d5db;border-radius:0.5rem;cursor:pointer;display:flex;'>
                            <svg width='18' height='18' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7'/></svg>
                        </button>
                        <button id='${modalId}_reset' title='รีเซ็ต' style='padding:0.5rem;background:#fff;border:1px solid #d1d5db;border-radius:0.5rem;cursor:pointer;display:flex;'>
                            <svg width='18' height='18' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'/></svg>
                        </button>
                    </div>

                    <!-- Action Buttons -->
                    <div style='display:flex;align-items:center;gap:0.5rem;'>
                        <button id='${modalId}_cancel' style='
                            padding:${isMobile ? '0.625rem 1rem' : '0.625rem 1.25rem'};
                            background:#fff;
                            border:1px solid #d1d5db;
                            color:#374151;
                            border-radius:0.5rem;
                            cursor:pointer;
                            font-weight:500;
                            font-size:${isMobile ? '0.875rem' : '1rem'};
                        '>ยกเลิก</button>
                        <button id='${modalId}_apply' style='
                            padding:${isMobile ? '0.625rem 1rem' : '0.625rem 1.25rem'};
                            background:#dc2626;
                            color:#fff;
                            border:none;
                            border-radius:0.5rem;
                            cursor:pointer;
                            font-weight:500;
                            font-size:${isMobile ? '0.875rem' : '1rem'};
                            display:flex;
                            align-items:center;
                            gap:0.375rem;
                        '>
                            <svg width='18' height='18' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'/></svg>
                            ใช้รูปนี้
                        </button>
                    </div>
                </div>
            </div>`;

        document.body.appendChild(modal);

        // ป้องกัน scroll ของ body
        document.body.style.overflow = 'hidden';

        const imgEl = document.getElementById(modalId + '_img');
        imgEl.src = this.imagePreview;

        setTimeout(() => {
            this.cropper = new Cropper(imgEl, {
                aspectRatio: {{ $aspectRatioValue }},
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 1,
                guides: true,
                center: true,
                highlight: true,
                cropBoxMovable: !isMobile,
                cropBoxResizable: !isMobile,
                zoomOnWheel: !isMobile,
                zoomOnTouch: true,
                responsive: true,
                background: true,
                touchDragZoom: true,
            });
        }, 100);

        // Event listeners
        const closeModal = () => this.closeCropper();
        document.getElementById(modalId + '_close').onclick = closeModal;
        document.getElementById(modalId + '_cancel').onclick = closeModal;
        document.getElementById(modalId + '_zoomin').onclick = () => this.cropper && this.cropper.zoom(0.1);
        document.getElementById(modalId + '_zoomout').onclick = () => this.cropper && this.cropper.zoom(-0.1);
        document.getElementById(modalId + '_reset').onclick = () => this.cropper && this.cropper.reset();
        document.getElementById(modalId + '_apply').onclick = () => this.applyCrop();

        // ปิดเมื่อกด Escape
        this._escHandler = (e) => { if (e.key === 'Escape') closeModal(); };
        document.addEventListener('keydown', this._escHandler);

        this.showCropper = true;
    },

    applyCrop() {
        if (!this.cropper) {
            alert('ไม่พบเครื่องมือครอปรูป');
            return;
        }

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

        const croppedImageData = canvas.toDataURL('image/png');
        this.croppedPreview = croppedImageData;

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
        const modal = document.getElementById('{{ $name }}_cropper_modal');
        if (modal) modal.remove();
        document.body.style.overflow = '';
        if (this._escHandler) {
            document.removeEventListener('keydown', this._escHandler);
            this._escHandler = null;
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
}" class="space-y-3">

    {{-- รูปภาพปัจจุบัน (Edit Mode) --}}
    @if ($existingImage)
        <div x-show="!imagePreview && !croppedPreview" class="mb-3">
            <p class="text-sm text-gray-600 mb-2">รูปภาพปัจจุบัน</p>
            <img src="{{ $existingImage }}" alt="Current image"
                class="w-full h-48 object-cover rounded-lg border-2 border-gray-300">
        </div>
    @endif

    {{-- Preview รูปที่ครอปแล้ว --}}
    <div x-show="croppedPreview" x-cloak>
        <div class="bg-green-50 border-2 border-green-500 rounded-lg p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2 text-green-700">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-semibold text-sm"
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

    {{-- Hidden input --}}
    <input type="hidden" id="{{ $name }}_hidden" wire:model="{{ $name }}">

    <div x-show="!imagePreview && !croppedPreview" class="mt-2">
        @if ($helpText)
            <p class="text-xs text-gray-500">{{ $helpText }}</p>
        @else
            <p class="text-xs text-gray-500">
                รองรับไฟล์ JPG, PNG ขนาดไม่เกิน 100 MB (แนะนำขนาด {{ $aspectRatioLabel }} px)
            </p>
        @endif
    </div>

    @error($name)
        <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror

</div>