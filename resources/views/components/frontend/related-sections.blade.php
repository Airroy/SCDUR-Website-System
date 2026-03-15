@props(['sections', 'title' => 'หมวดหมู่อื่นๆ'])

@if ($sections->count() > 0)
    <section class="mb-8">
        <h2 class="text-2xl font-semibold text-gray-900 mb-6">{{ $title }}</h2>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach ($sections as $section)
                @php
                    $sectionSlug = \Illuminate\Support\Str::slug($section->name) ?: 'section-' . $section->id;
                @endphp
                <a href="{{ route('scd.section', [$section->scdYear->year, $sectionSlug]) }}"
                    class="group bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1">

                    {{-- รูปภาพ --}}
                    @if ($section->image_path)
                        <img src="{{ Storage::url($section->image_path) }}" alt="{{ $section->name }}"
                            class="w-full h-32 object-cover">
                    @else
                        <div
                            class="w-full h-32 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                            <span class="text-4xl font-bold text-gray-400">
                                {{ mb_substr($section->name, 0, 1) }}
                            </span>
                        </div>
                    @endif

                    {{-- ชื่อหมวดหมู่ --}}
                    <div class="p-3">
                        <p
                            class="text-sm font-semibold text-gray-800 text-center group-hover:text-brand-red transition-colors line-clamp-2">
                            {{ $section->name }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endif
