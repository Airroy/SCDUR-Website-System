<div class="banner-slider">
    @if($banners->count() > 0)
        <div class="relative h-96 overflow-hidden rounded-lg" x-data="bannerSlider({{ $banners->count() }})">
            <!-- Slides -->
            @foreach($banners as $index => $banner)
                <div 
                    x-show="currentSlide === {{ $index }}"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 transform translate-x-full"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    x-transition:leave="transition ease-in duration-500"
                    x-transition:leave-start="opacity-100 transform translate-x-0"
                    x-transition:leave-end="opacity-0 transform -translate-x-full"
                    class="absolute inset-0"
                    style="display: none;"
                >
                    @if($banner->image_path)
                        <img 
                            src="{{ Storage::url($banner->image_path) }}" 
                            alt="Banner {{ $index + 1 }}"
                            class="w-full h-full object-cover"
                        >
                    @endif

                    @if($banner->link_type !== 'none' && $banner->link_url)
                        <a 
                            href="{{ $banner->link_url }}" 
                            @if($banner->link_type === 'external') target="_blank" rel="noopener noreferrer" @endif
                            class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-10 transition-all"
                        ></a>
                    @endif
                </div>
            @endforeach

            <!-- Navigation Arrows -->
            @if($banners->count() > 1)
                <button 
                    @click="previousSlide()"
                    class="absolute left-4 top-1/2 -translate-y-1/2 bg-white bg-opacity-50 hover:bg-opacity-75 rounded-full p-3 transition-all"
                >
                    <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>

                <button 
                    @click="nextSlide()"
                    class="absolute right-4 top-1/2 -translate-y-1/2 bg-white bg-opacity-50 hover:bg-opacity-75 rounded-full p-3 transition-all"
                >
                    <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                <!-- Indicators -->
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                    @foreach($banners as $index => $banner)
                        <button 
                            @click="currentSlide = {{ $index }}"
                            :class="currentSlide === {{ $index }} ? 'bg-white' : 'bg-white bg-opacity-50'"
                            class="w-3 h-3 rounded-full transition-all"
                        ></button>
                    @endforeach
                </div>
            @endif
        </div>
    @else
        <div class="h-96 bg-gray-200 rounded-lg flex items-center justify-center">
            <p class="text-gray-500">ไม่มี Banner ให้แสดง</p>
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

            init() {
                if (this.totalSlides > 1) {
                    this.startAutoPlay();
                }
            },

            nextSlide() {
                this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
            },

            previousSlide() {
                this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
            },

            startAutoPlay() {
                this.autoPlayInterval = setInterval(() => {
                    this.nextSlide();
                }, 5000);
            },

            stopAutoPlay() {
                if (this.autoPlayInterval) {
                    clearInterval(this.autoPlayInterval);
                }
            }
        }
    }
</script>
@endpush
