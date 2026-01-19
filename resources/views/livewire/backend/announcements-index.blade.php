<div>
    @if($selectedYear)
        <!-- Tab Navigation -->
        <x-backend.year-tabs :selectedYear="$selectedYear" :currentPage="$currentPage" />

        <!-- Page Header -->
        <div class="p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    {{ $category === 'announcement' ? 'ประกาศ' : 'คำสั่ง' }}ประจำปี {{ $selectedYear->year }}
                </h1>
                <p class="mt-1 text-gray-600">เพิ่ม แก้ไข และจัดการ{{ $category === 'announcement' ? 'ประกาศ' : 'คำสั่ง' }}</p>
            </div>

            <!-- Category Tabs -->
            <div class="mb-6">
                <div class="inline-flex rounded-lg border border-gray-200 p-1 bg-white">
                    <button wire:click="switchCategory('announcement')" 
                            class="px-4 py-2 rounded-md text-sm font-medium transition {{ $category === 'announcement' ? 'bg-red-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        ประกาศ
                    </button>
                    <button wire:click="switchCategory('order')" 
                            class="px-4 py-2 rounded-md text-sm font-medium transition {{ $category === 'order' ? 'bg-red-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        คำสั่ง
                    </button>
                </div>
            </div>

            <!-- Breadcrumbs -->
            @if(count($breadcrumbs) > 0)
            <nav class="mb-4 flex items-center space-x-2 text-sm">
                <button wire:click="navigateBack(null)" class="text-red-600 hover:text-red-800 font-medium">
                    {{ $category === 'announcement' ? 'ประกาศ' : 'คำสั่ง' }}
                </button>
                @foreach($breadcrumbs as $index => $crumb)
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    @if($index === count($breadcrumbs) - 1)
                        <span class="text-gray-900 font-medium">{{ $crumb['name'] }}</span>
                    @else
                        <button wire:click="navigateBack({{ $crumb['id'] }})" class="text-red-600 hover:text-red-800">
                            {{ $crumb['name'] }}
                        </button>
                    @endif
                @endforeach
            </nav>
            @endif

            @include('livewire.backend.partials.announcements-table', [
                'selectedYear' => $selectedYear, 
                'items' => $items, 
                'category' => $category,
                'currentParentId' => $currentParentId,
                'hasFiles' => $hasFiles,
                'hasFolders' => $hasFolders,
                'breadcrumbs' => $breadcrumbs
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
