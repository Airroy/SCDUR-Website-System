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
            
            <!-- Breadcrumbs Navigation -->
            @if(!empty($breadcrumbs))
                <nav class="mb-6 flex items-center space-x-2 text-sm bg-gray-50 px-4 py-3 rounded-lg">
                    <button 
                        wire:click="navigateBack(null)"
                        class="flex items-center text-gray-600 hover:text-red-600 hover:underline transition-colors"
                    >
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        หน้าแรก
                    </button>
                    
                    @foreach($breadcrumbs as $crumb)
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        
                        @if(!$loop->last)
                            <button 
                                wire:click="navigateBack({{ $crumb['id'] }})"
                                class="text-gray-600 hover:text-red-600 hover:underline transition-colors"
                            >
                                {{ $crumb['name'] }}
                            </button>
                        @else
                            <span class="text-red-600 font-semibold">{{ $crumb['name'] }}</span>
                        @endif
                    @endforeach
                </nav>
            @endif
            
            <!-- Contents Table with Manager -->
            @include('livewire.backend.partials.contents-table', [
                'selectedYear' => $selectedYear,
                'contents' => $contents,
                'hasFolders' => $hasFolders,
                'hasFiles' => $hasFiles,
                'hasFoldersInParent' => $hasFoldersInParent,
                'hasFilesInParent' => $hasFilesInParent,
                'currentParentId' => $currentParentId,
            ])
        </div>
    @else
        <!-- Empty State -->
        <div class="flex items-center justify-center min-h-[60vh]">
            <div class="text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="mt-4 text-xl font-semibold text-gray-900">กรุณาเลือกปี</h3>
                <p class="mt-2 text-gray-600">กรุณาเลือกปีจากเมนูด้านซ้ายเพื่อเริ่มจัดการเนื้อหา</p>
            </div>
        </div>
    @endif
</div>