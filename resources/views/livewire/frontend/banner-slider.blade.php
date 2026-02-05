<div class="banner-slider">
    @php
        $slides = $banners->count() > 0 ? $banners : collect([]);
        $slideCount = $slides->count();
    @endphp

    @if ($slideCount > 0)
        {{-- Preload Images --}}
        @foreach ($slides as $banner)
            @if ($banner->image_path)
                <link rel="preload" as="image" href="{{ Storage::url($banner->image_path) }}">
            @endif
        @endforeach

        <div class="relative w-full overflow-hidden shadow-lg" x-data="infiniteSlider({{ $slideCount }})" x-init="init()"
            @mouseenter="stopAutoPlay()" @mouseleave="startAutoPlay()">

            {{-- Slides Container --}}
            <div class="flex" x-ref="slider"
                :style="`transform: translateX(-${currentPosition}%); transition: ${isAnimating ? 'transform 0.5s ease-in-out' : 'none'}`">

                {{-- Clone รูปสุดท้ายไว้ข้างหน้า (สำหรับเลื่อนย้อนกลับ) --}}
                @php $lastBanner = $slides->last(); @endphp
                <div class="min-w-full flex-shrink-0 relative">
                    @if ($lastBanner->image_path)
                        <div class="w-full relative" style="aspect-ratio: 1140/428;">
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-gray-200 via-gray-300 to-gray-200 animate-pulse">
                            </div>
                            <img src="{{ Storage::url($lastBanner->image_path) }}" alt="Banner"
                                class="w-full h-full object-cover absolute inset-0" loading="eager"
                                onload="this.style.opacity=1" style="opacity: 0; transition: opacity 0.3s ease-in-out;">
                        </div>
                    @else
                        <div class="w-full bg-gray-200" style="aspect-ratio: 1140/428;"></div>
                    @endif
                    @if ($lastBanner->link_type === 'url' && $lastBanner->link_url)
                        <a href="{{ $lastBanner->link_url }}" target="_blank" rel="noopener noreferrer"
                            class="absolute inset-0 left-[15%] right-[15%] bg-black/0 hover:bg-black/10 transition-all duration-300 cursor-pointer z-10"></a>
                    @elseif($lastBanner->link_type === 'pdf' && $lastBanner->pdf_path)
                        <a href="{{ Storage::url($lastBanner->pdf_path) }}" target="_blank" rel="noopener noreferrer"
                            class="absolute inset-0 left-[15%] right-[15%] bg-black/0 hover:bg-black/10 transition-all duration-300 cursor-pointer z-10"></a>
                    @endif
                </div>

                {{-- รูปจริงทั้งหมด --}}
                @foreach ($slides as $index => $banner)
                    <div class="min-w-full flex-shrink-0 relative">
                        @if ($banner->image_path)
                            <div class="w-full relative" style="aspect-ratio: 1140/428;">
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-gray-200 via-gray-300 to-gray-200 animate-pulse">
                                </div>
                                <img src="{{ Storage::url($banner->image_path) }}" alt="Banner {{ $index + 1 }}"
                                    class="w-full h-full object-cover absolute inset-0" loading="eager"
                                    onload="this.style.opacity=1"
                                    style="opacity: 0; transition: opacity 0.3s ease-in-out;">
                            </div>
                        @else
                            <div class="w-full bg-gray-200" style="aspect-ratio: 1140/428;"></div>
                        @endif
                        @if ($banner->link_type === 'url' && $banner->link_url)
                            <a href="{{ $banner->link_url }}" target="_blank" rel="noopener noreferrer"
                                class="absolute inset-0 left-[15%] right-[15%] bg-black/0 hover:bg-black/10 transition-all duration-300 cursor-pointer z-10"></a>
                        @elseif($banner->link_type === 'pdf' && $banner->pdf_path)
                            <a href="{{ Storage::url($banner->pdf_path) }}" target="_blank" rel="noopener noreferrer"
                                class="absolute inset-0 left-[15%] right-[15%] bg-black/0 hover:bg-black/10 transition-all duration-300 cursor-pointer z-10"></a>
                        @endif
                    </div>
                @endforeach

                {{-- Clone รูปแรกไว้ท้ายสุด (สำหรับเลื่อนไปข้างหน้า) --}}
                @php $firstBanner = $slides->first(); @endphp
                <div class="min-w-full flex-shrink-0 relative">
                    @if ($firstBanner->image_path)
                        <div class="w-full relative" style="aspect-ratio: 1140/428;">
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-gray-200 via-gray-300 to-gray-200 animate-pulse">
                            </div>
                            <img src="{{ Storage::url($firstBanner->image_path) }}" alt="Banner"
                                class="w-full h-full object-cover absolute inset-0" loading="eager"
                                onload="this.style.opacity=1" style="opacity: 0; transition: opacity 0.3s ease-in-out;">
                        </div>
                    @else
                        <div class="w-full bg-gray-200" style="aspect-ratio: 1140/428;"></div>
                    @endif
                    @if ($firstBanner->link_type === 'url' && $firstBanner->link_url)
                        <a href="{{ $firstBanner->link_url }}" target="_blank" rel="noopener noreferrer"
                            class="absolute inset-0 left-[15%] right-[15%] bg-black/0 hover:bg-black/10 transition-all duration-300 cursor-pointer z-10"></a>
                    @elseif($firstBanner->link_type === 'pdf' && $firstBanner->pdf_path)
                        <a href="{{ Storage::url($firstBanner->pdf_path) }}" target="_blank" rel="noopener noreferrer"
                            class="absolute inset-0 left-[15%] right-[15%] bg-black/0 hover:bg-black/10 transition-all duration-300 cursor-pointer z-10"></a>
                    @endif
                </div>
            </div>

            {{-- Previous Button Area - กดพื้นที่ซ้ายทั้งหมด --}}
            <button @click="prev()"
                class="absolute top-0 left-0 bottom-0 w-[30%] flex items-center justify-start pl-8 z-20 group"
                style="background: transparent; transition: all 0.3s ease-out;"
                onmouseenter="this.style.background='linear-gradient(to right, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.15) 50%, rgba(0,0,0,0) 100%)';"
                onmouseleave="this.style.background='transparent';"
                aria-label="Previous slide">
                <svg class="w-10 h-10 text-white drop-shadow-lg
                           group-hover:scale-110 group-hover:-translate-x-1
                           transition-all duration-300" 
                     fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>

            {{-- Next Button Area - กดพื้นที่ขวาทั้งหมด --}}
            <button @click="next()"
                class="absolute top-0 right-0 bottom-0 w-[30%] flex items-center justify-end pr-8 z-20 group"
                style="background: transparent; transition: all 0.3s ease-out;"
                onmouseenter="this.style.background='linear-gradient(to left, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.15) 50%, rgba(0,0,0,0) 100%)';"
                onmouseleave="this.style.background='transparent';"
                aria-label="Next slide">
                <svg class="w-10 h-10 text-white drop-shadow-lg
                           group-hover:scale-110 group-hover:translate-x-1
                           transition-all duration-300" 
                     fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            {{-- Dots Navigation --}}
            @if ($slideCount > 1)
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2.5 z-20">
                    @foreach ($slides as $index => $banner)
                        <button @click="goTo({{ $index }})"
                            class="w-3 h-3 rounded-full cursor-pointer transition-all duration-300 shadow-sm"
                            :class="currentSlide === {{ $index }} ? 'bg-[#af1a00] scale-125' :
                                'bg-white/60 hover:bg-white'"
                            aria-label="Go to slide {{ $index + 1 }}">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    @else
        {{-- No Banner --}}
        <div class="relative w-full overflow-hidden shadow-lg">
            <div class="w-full bg-gradient-to-r from-gray-100 to-gray-200 flex items-center justify-center"
                style="aspect-ratio: 1140/428;">
                <div class="text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <p class="text-gray-500 font-medium">ไม่มี Banner ให้แสดง</p>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
    <script>
        function infiniteSlider(totalSlides) {
            return {
                totalSlides: totalSlides,
                currentSlide: 0,
                currentPosition: 100, // เริ่มที่ 100% เพราะมี clone ข้างหน้า 1 รูป
                isAnimating: false,
                autoPlayInterval: null,
                isPaused: false,

                init() {
                    if (this.totalSlides > 1) {
                        this.startAutoPlay();
                    }
                },

                next() {
                    if (this.isAnimating) return;
                    this.isAnimating = true;
                    this.currentSlide++;
                    this.currentPosition += 100;

                    setTimeout(() => {
                        if (this.currentSlide >= this.totalSlides) {
                            this.isAnimating = false;
                            this.currentSlide = 0;
                            this.currentPosition = 100;
                        }
                        this.isAnimating = false;
                    }, 520);
                },

                prev() {
                    if (this.isAnimating) return;
                    this.isAnimating = true;
                    this.currentSlide--;
                    this.currentPosition -= 100;

                    setTimeout(() => {
                        if (this.currentSlide < 0) {
                            this.isAnimating = false;
                            this.currentSlide = this.totalSlides - 1;
                            this.currentPosition = this.totalSlides * 100;
                        }
                        this.isAnimating = false;
                    }, 520);
                },

                goTo(index) {
                    if (this.isAnimating || this.currentSlide === index) return;
                    this.isAnimating = true;
                    this.currentSlide = index;
                    this.currentPosition = (index + 1) * 100;

                    setTimeout(() => {
                        this.isAnimating = false;
                    }, 520);
                },

                startAutoPlay() {
                    this.isPaused = false;
                    if (this.autoPlayInterval) {
                        clearInterval(this.autoPlayInterval);
                    }
                    if (this.totalSlides > 1) {
                        this.autoPlayInterval = setInterval(() => {
                            if (!this.isPaused && !this.isAnimating) {
                                this.next();
                            }
                        }, 4000);
                    }
                },

                stopAutoPlay() {
                    this.isPaused = true;
                    if (this.autoPlayInterval) {
                        clearInterval(this.autoPlayInterval);
                        this.autoPlayInterval = null;
                    }
                }
            }
        }
    </script>
@endpush