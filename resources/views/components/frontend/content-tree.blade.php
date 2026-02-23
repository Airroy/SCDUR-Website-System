@props(['items', 'level' => 0, 'isTopLevel' => true])

@php
    $level = $level ?? 0;

    // ประกาศตัวแปรที่หายไปให้ครบถ้วน
    $indentPx = $level * 24;
    $indentStyle = $level > 0 ? "padding-left: {$indentPx}px;" : 'padding-left: 1.5rem;';
@endphp

@foreach ($items as $index => $item)
    @if ($item->isFolder())
        {{-- ================= FOLDER (หัวข้อ) ================= --}}
        <div class="border-b border-gray-300 bg-gray-100">
            <div class="py-4 pr-4" style="{{ $indentStyle }}">
                <span class="font-bold text-black text-base md:text-lg block">
                    {{ $item->name }}
                </span>
            </div>
        </div>

        @if ($item->children->count() > 0)
            <x-frontend.content-tree :items="$item->children" :level="$level + 1" :isTopLevel="false" />
        @endif
    @else
        {{-- ================= FILE ================= --}}
        <div
            class="group bg-white border-b border-gray-300 last:border-0 hover:bg-red-50/20 transition-all duration-200 relative overflow-hidden">
            {{-- เส้นขอบแดงด้านซ้ายที่จะชัดขึ้นเวลาเอาเมาส์ชี้ (Hover Effect) --}}
            <div
                class="absolute left-0 top-0 bottom-0 w-1 bg-[#af1a00] opacity-0 group-hover:opacity-100 transition-opacity">
            </div>

            <div class="flex flex-col md:flex-row md:items-center justify-between py-4 pr-4 gap-4"
                style="{{ $indentStyle }}">

                <div class="flex-1 min-w-0">
                    {{-- ชื่อไฟล์: เน้นเลขลำดับให้เด่นด้วยสีดำ --}}
                    <div class="text-gray-900 text-sm md:text-base break-words mb-2.5">
                        <span
                            class="font-medium group-hover:text-[#af1a00] transition-colors">{{ $item->name }}</span>
                    </div>

                    {{-- สถิติ: ออกแบบให้เป็นระเบียบ ไม่รกตา --}}
                    <div class="flex flex-wrap gap-3 text-[11px] md:text-xs text-gray-500">
                        <span class="items-center gap-1.5 px-2 py-0.5 rounded-full bg-gray-100 border border-gray-200">
                            <span class="w-1 h-1 rounded-full bg-gray-400"></span>
                            เข้าชม {{ number_format($item->view_count ?? 0) }} ครั้ง
                        </span>
                        <span
                            class="items-center gap-1.5 px-2 py-0.5 rounded-full bg-red-50 text-red-700 border border-red-100">
                            <span class="w-1 h-1 rounded-full bg-red-500"></span>
                            ดาวน์โหลด {{ number_format($item->download_count ?? 0) }} ครั้ง
                        </span>
                    </div>
                </div>

                {{-- ปุ่ม Actions (สีแดงตาม Theme) --}}
                @if ($item->file_path)
                    @php
                        $source = match (get_class($item)) {
                            \App\Models\Announcement::class => 'announcement',
                            \App\Models\Order::class => 'directive',
                            \App\Models\ContentSection::class => 'content',
                            default => 'content',
                        };
                    @endphp
                    <div class="flex items-center gap-2.5 pl-4 md:pl-0 shrink-0">
                        <a href="{{ route('file.view', ['source' => $source, 'id' => $item->id, 'filename' => basename($item->file_path)]) }}"
                            target="_blank"
                            class="px-5 py-2 bg-[#af1a00] hover:bg-[#8b1500] text-white text-xs md:text-sm font-bold rounded shadow-sm transition-all active:scale-95">
                            เปิดดู
                        </a>

                        <a href="{{ route('file.download', ['source' => $source, 'id' => $item->id]) }}"
                            class="px-5 py-2 bg-white border-2 border-[#af1a00] text-[#af1a00] hover:bg-red-50 text-xs md:text-sm font-bold rounded shadow-sm transition-all active:scale-95">
                            ดาวน์โหลด
                        </a>
                    </div>
                @else
                    <span
                        class="text-gray-400 text-xs italic pl-4 md:pl-0 font-light px-3 py-1 bg-gray-50 rounded">ไม่มีไฟล์แนบ</span>
                @endif
            </div>
        </div>
    @endif
@endforeach
