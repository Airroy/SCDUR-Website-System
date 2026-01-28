<div class="banner-slider">
    @php
        $slides = $banners->count() > 0 ? $banners : collect([]);
        $slideCount = $slides->count();
    @endphp

    @if($slideCount > 0)
        {{-- Preload Images --}}
        @foreach($slides as $banner)
            @if($banner->image_path)
                <link rel="preload" as="image" href="{{ Storage::url($banner->image_path) }}">
            @endif
        @endforeach

        <div class="relative w-full overflow-hidden rounded-xl shadow-lg" 
             x-data="bannerSlider({{ $slideCount }})"
             @mouseenter="stopAutoPlay()"
             @mouseleave="startAutoPlay()">

            {{-- Slides Container --}}
            <div class="flex transition-transform duration-700 ease-out"
                :class="{ 'transition-transform duration-700 ease-out': isTransitioning, 'transition-none': !isTransitioning }"
                :style="`transform: translateX(-${currentSlide * 100}%)`"
                @transitionend="handleTransitionEnd()">
                
                @foreach ($slides as $index => $banner)
                    <div class="min-w-full relative bg-gray-200">
                        @if ($banner->image_path)
                            <div class="w-full aspect-[21/9] relative">
                                {{-- Loading Skeleton --}}
                                <div class="absolute inset-0 bg-gradient-to-r from-gray-200 via-gray-300 to-gray-200 animate-pulse"></div>
                                
                                {{-- Image --}}
                                <img src="{{ Storage::url($banner->image_path) }}" 
                                    alt="Banner {{ $index + 1 }}"
                                    class="w-full h-full object-cover absolute inset-0"
                                    loading="eager"
                                    fetchpriority="high"
                                    onload="this.style.opacity=1"
                                    style="opacity: 0; transition: opacity 0.3s ease-in-out;">
                            </div>
                        @endif

                        {{-- แสดงลิงค์ตามประเภท --}}
                        @if($banner->link_type === 'url' && $banner->link_url)
                            <a href="{{ $banner->link_url }}" 
                                target="_blank" 
                                rel="noopener noreferrer"
                                class="absolute inset-0 bg-black/0 hover:bg-black/10 transition-all duration-300 cursor-pointer"></a>
                        @elseif($banner->link_type === 'pdf' && $banner->pdf_path)
                            <a href="{{ Storage::url($banner->pdf_path) }}" 
                                target="_blank" 
                                rel="noopener noreferrer"
                                class="absolute inset-0 bg-black/0 hover:bg-black/10 transition-all duration-300 cursor-pointer"></a>
                        @endif
                    </div>
                @endforeach

                {{-- Clone รูปแรกไว้ท้ายสุด สำหรับ infinite loop --}}
                @php $firstBanner = $slides->first(); @endphp
                <div class="min-w-full relative bg-gray-200">
                    @if ($firstBanner->image_path)
                        <div class="w-full aspect-[21/9] relative">
                            {{-- Loading Skeleton --}}
                            <div class="absolute inset-0 bg-gradient-to-r from-gray-200 via-gray-300 to-gray-200 animate-pulse"></div>
                            
                            {{-- Image --}}
                            <img src="{{ Storage::url($firstBanner->image_path) }}" 
                                alt="Banner 1"
                                class="w-full h-full object-cover absolute inset-0"
                                loading="eager"
                                fetchpriority="high"
                                onload="this.style.opacity=1"
                                style="opacity: 0; transition: opacity 0.3s ease-in-out;">
                        </div>
                    @endif

                    {{-- แสดงลิงค์ตามประเภท --}}
                    @if($firstBanner->link_type === 'url' && $firstBanner->link_url)
                        <a href="{{ $firstBanner->link_url }}" 
                            target="_blank" 
                            rel="noopener noreferrer"
                            class="absolute inset-0 bg-black/0 hover:bg-black/10 transition-all duration-300 cursor-pointer"></a>
                    @elseif($firstBanner->link_type === 'pdf' && $firstBanner->pdf_path)
                        <a href="{{ Storage::url($firstBanner->pdf_path) }}" 
                            target="_blank" 
                            rel="noopener noreferrer"
                            class="absolute inset-0 bg-black/0 hover:bg-black/10 transition-all duration-300 cursor-pointer"></a>
                    @endif
                </div>
            </div>

            {{-- Previous Button --}}
            <button @click="previousSlide()"
                class="absolute top-1/2 left-4 -translate-y-1/2 bg-[#af1a00]/50 hover:bg-[#af1a00]/80 text-white rounded-full p-3 transition-all duration-300 shadow-md hover:shadow-lg hover:scale-110 z-10"
                aria-label="Previous slide">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>

            {{-- Next Button --}}
            <button @click="nextSlide()"
                class="absolute top-1/2 right-4 -translate-y-1/2 bg-[#af1a00]/50 hover:bg-[#af1a00]/80 text-white rounded-full p-3 transition-all duration-300 shadow-md hover:shadow-lg hover:scale-110 z-10"
                aria-label="Next slide">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            {{-- Dots Navigation --}}
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2.5 z-10">
                @foreach($slides as $index => $banner)
                    <button @click="currentSlide = {{ $index }}; isTransitioning = true;"
                        class="w-3 h-3 rounded-full cursor-pointer transition-all duration-300 shadow-sm"
                        :class="currentSlide === {{ $index }} || (currentSlide === {{ $slideCount }} && {{ $index }} === 0) ? 'bg-[#af1a00] scale-125' : 'bg-white/60 hover:bg-white'"
                        :aria-label="`Go to slide ${{{ $index + 1 }}}`">
                    </button>
                @endforeach
            </div>
        </div>
    @else
        {{-- Default Slides --}}
        <div class="relative w-full overflow-hidden rounded-xl shadow-lg">
            <div class="w-full aspect-[16/6] bg-gradient-to-r from-gray-100 to-gray-200 flex items-center justify-center">
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
    function bannerSlider(totalSlides) {
        return {
            currentSlide: 0,
            totalSlides: totalSlides,
            autoPlayInterval: null,
            isPaused: false,
            isTransitioning: true,
            imagesLoaded: false,

            init() {
                // Preload images
                this.preloadImages();
                
                if (this.totalSlides > 1) {
                    this.startAutoPlay();
                }
            },

            preloadImages() {
                const container = this.$el;
                const images = container.querySelectorAll('img');
                let loadedCount = 0;
                const totalImages = images.length;

                if (totalImages === 0) {
                    this.imagesLoaded = true;
                    return;
                }

                images.forEach(img => {
                    if (img.complete) {
                        loadedCount++;
                    } else {
                        img.addEventListener('load', () => {
                            loadedCount++;
                            if (loadedCount === totalImages) {
                                this.imagesLoaded = true;
                            }
                        });
                    }
                });

                if (loadedCount === totalImages) {
                    this.imagesLoaded = true;
                }
            },

            nextSlide() {
                this.isTransitioning = true;
                this.currentSlide++;
            },

            previousSlide() {
                if (this.currentSlide === 0) {
                    this.isTransitioning = false;
                    this.currentSlide = this.totalSlides;
                    setTimeout(() => {
                        this.isTransitioning = true;
                        this.currentSlide = this.totalSlides - 1;
                    }, 50);
                } else {
                    this.isTransitioning = true;
                    this.currentSlide--;
                }
            },

            handleTransitionEnd() {
                if (this.currentSlide >= this.totalSlides) {
                    this.isTransitioning = false;
                    this.currentSlide = 0;
                }
            },

            startAutoPlay() {
                this.isPaused = false;
                if (this.autoPlayInterval) {
                    clearInterval(this.autoPlayInterval);
                }
                if (this.totalSlides > 1) {
                    this.autoPlayInterval = setInterval(() => {
                        if (!this.isPaused) {
                            this.nextSlide();
                        }
                    }, 5000);
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