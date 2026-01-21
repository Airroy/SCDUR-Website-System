<x-layouts.admin>
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">โปรไฟล์</h2>

        <div class="space-y-6">
            <!-- Update Profile Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @livewire('profile.update-profile-information')
                </div>
            </div>

            <!-- Update Password -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @livewire('profile.update-password')
                </div>
            </div>

            <!-- Delete User Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @livewire('profile.delete-user')
                </div>
            </div>
        </div>
    </div>
</div>
</x-layouts.admin>
