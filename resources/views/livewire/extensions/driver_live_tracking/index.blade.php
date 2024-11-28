<div>
    {{-- Success is as dangerous as failure. --}}
    @if($showView)
        <div class="flex items-center w-full mb-3 text-2xl font-semibold">
            Driver Location Tracking
            <div class="mx-auto"></div>
            <div
                class="{{ setting('localeCode') == 'ar' ? 'mr-auto':'ml-auto' }}">
                <x-buttons.primary title="Back" wireClick="showExtensions" />
            </div>
        </div>

        <div class="flex items-center mb-4 justify-items-center">
            <div
                class="{{ setting('localeCode') == 'ar' ? 'mx-auto':'' }}">
            </div>
            <x-buttons.plain wireClick="showAllDriversOnMap">
                All
            </x-buttons.plain>
            <div class="w-2"></div>
            <x-buttons.plain wireClick="showOnlineDriversOnMap" bgColor="bg-green-500">
                Online Drivers
            </x-buttons.plain>
            <div class="w-2"></div>
            <x-buttons.plain wireClick="showOfflineDriversOnMap" bgColor="bg-red-500">
                Offline Drivers
            </x-buttons.plain>
            <div class="w-2"></div>
            <x-buttons.plain wireClick="$set('showCreate', true)">
                Custom
            </x-buttons.plain>
            <div
                class="{{ setting('localeCode') == 'ar' ? '':'mx-auto' }}">
            </div>
        </div>

        <div class="h-screen w-full">
            <div id="map" class="w-full h-full border rounded-sm border-primary-500" wire:ignore></div>
        </div>

        {{-- new form --}}
        <div x-data="{ open: @entangle('showCreate') }">
            <x-modal-lg >
                <p class="text-xl font-semibold">{{ __('Drivers') }}</p>
                <livewire:extensions.driver-live-tracking.drivers-table-extension />
            </x-modal-lg>
        </div>
    @endif

    {{-- scripts --}}
    @push('scripts')
        <script
            src="https://maps.googleapis.com/maps/api/js?key={{ setting('googleMapKey', '') }}&language={{ setting('localeCode', 'en') }}&libraries=&v=weekly">
        </script>
        
        <script defer src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
        <script defer src="https://www.gstatic.com/firebasejs/8.10.0/firebase-auth.js"></script>
        <script defer src="https://www.gstatic.com/firebasejs/8.10.0/firebase-firestore.js"></script>
        <script src="{{ asset('js/extensions/driver_tracking.js') }}"></script>
    @endpush

</div>
