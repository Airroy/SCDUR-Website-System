<x-layouts.frontend>
    <x-slot:title>หน้าหลัก - มหาวิทยาลัยกับการพัฒนาชุมชนอย่างยั่งยืน</x-slot:title>

    <!-- Banner Slider Section -->
    <section class="pt-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <livewire:frontend.banner-slider />
        </div>
    </section>

    <!-- ARU-SCD [ปี] Banner -->
    @if($activeYear)
    <section class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-lg shadow-lg px-8 py-6 text-center">
                <h2 class="text-3xl md:text-4xl font-bold text-white">
                    ARU-SCD {{ $activeYear->year }}
                </h2>
            </div>
        </div>
    </section>
    @endif

    <!-- Content Sections Grid -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($contentSections->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($contentSections as $section)
                        <a href="#" class="group">
                            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                @if($section->image_path)
                                    <div class="aspect-video w-full overflow-hidden bg-gray-200">
                                        <img src="{{ Storage::url($section->image_path) }}" 
                                             alt="{{ $section->name }}" 
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    </div>
                                @else
                                    <div class="aspect-video w-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-red-600 transition-colors line-clamp-2">
                                        {{ $section->name }}
                                    </h3>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500">ไม่มีข้อมูลในขณะนี้</p>
                </div>
            @endif
        </div>
    </section>

</x-layouts.frontend>
