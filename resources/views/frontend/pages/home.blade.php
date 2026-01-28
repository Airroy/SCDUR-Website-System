<x-layouts.frontend>
    <x-slot:title>หน้าหลัก - มหาวิทยาลัยกับการพัฒนาชุมชนอย่างยั่งยืน</x-slot:title>

    <!-- Banner Slider Section -->
    <section class="pt-6 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <livewire:frontend.banner-slider :yearId="$activeYear?->id" />
        </div>
    </section>

    <!-- ARU-SCD [ปี] Section -->
    @if ($activeYear && $contentSections->isNotEmpty())
        <section class="py-12 bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- White Box ครอบทั้งหมด -->
                <div class="bg-white rounded-3xl shadow-xl p-8 md:p-12">
                    <!-- Title with bottom border -->
                    <div class="mb-10">
                        <h1 class="text-4xl md:text-5xl font-bold text-[#af1a00]">
                            ARU-SCD{{ $activeYear->year }}
                        </h1>
                        <div class="mt-3 h-1 bg-[#af1a00] w-full"></div>
                    </div>

                    <!-- Content Grid -->
                    @php
                        $chunks = $contentSections->chunk(4);
                    @endphp

                    @foreach ($chunks as $chunkIndex => $chunk)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 @if($chunkIndex > 0) mt-8 @endif">
                            @foreach ($chunk as $section)
                                <a href="{{ route('content-section', [$activeYear->id, $section->id]) }}" 
                                   class="group block text-center">
                                    <div class="transition-transform duration-300 hover:-translate-y-2">
                                        <!-- รูปภาพ -->
                                        <div class="relative aspect-video bg-gray-200 overflow-hidden rounded-xl shadow-md group-hover:shadow-xl transition-shadow duration-300">
                                            @if($section->image_path)
                                                <img src="{{ Storage::url($section->image_path) }}" 
                                                     alt="{{ $section->name }}" 
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-red-100 to-red-200">
                                                    <svg class="w-16 h-16 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <!-- ชื่อด้านล่าง -->
                                        <h3 class="mt-4 text-lg font-semibold text-gray-800 group-hover:text-red-600 transition-colors duration-200">
                                            {{ $section->name }}
                                        </h3>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @elseif ($activeYear)
        <section class="py-12 bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-3xl shadow-xl p-8 md:p-12">
                    <div class="mb-10">
                        <h1 class="text-4xl md:text-5xl font-bold text-[#af1a00]">
                            ARU-SCD{{ $activeYear->year }}
                        </h1>
                        <div class="mt-3 h-1 bg-[#af1a00] w-full"></div>
                    </div>
                    <div class="text-center py-16">
                        <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="mt-4 text-xl text-gray-500">ไม่มีข้อมูลในขณะนี้</p>
                    </div>
                </div>
            </div>
        </section>
    @endif

</x-layouts.frontend>