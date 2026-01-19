<div>
    @if($selectedYear)
        <!-- Tab Navigation -->
        <x-backend.year-tabs :selectedYear="$selectedYear" :currentPage="$currentPage" />

        <!-- Page Header -->
        <div class="p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">เนื้อหาประจำปี {{ $selectedYear->year }}</h1>
                <p class="mt-1 text-gray-600">เพิ่ม แก้ไข และจัดการเนื้อหาหลักในแต่ละหัวข้อ</p>
            </div>

            <!-- Breadcrumbs -->
            @if(!empty($breadcrumbs))
                <div class="mb-4 flex items-center space-x-2 text-sm">
                    <button 
                        wire:click="navigateBack(null)"
                        class="text-gray-600 hover:text-gray-800 hover:underline"
                    >
                        หน้าแรก
                    </button>
                    @foreach($breadcrumbs as $crumb)
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        @if(!$loop->last)
                            <button 
                                wire:click="navigateBack({{ $crumb['id'] }})"
                                class="text-gray-600 hover:text-gray-800 hover:underline"
                            >
                                {{ $crumb['name'] }}
                            </button>
                        @else
                            <span class="text-red-600 font-medium">{{ $crumb['name'] }}</span>
                        @endif
                    @endforeach
                </div>
            @endif

            @include('livewire.backend.partials.contents-table', [
                'selectedYear' => $selectedYear,
                'contents' => $contents,
                'hasFolders' => $hasFolders,
                'hasFiles' => $hasFiles,
            ])
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">กรุณาเลือกปี</h3>
            <p class="mt-2 text-sm text-gray-500">กรุณาเลือกปีจากเมนูด้านซ้ายเพื่อเริ่มจัดการเนื้อหา</p>
        </div>
    @endif
</div>
