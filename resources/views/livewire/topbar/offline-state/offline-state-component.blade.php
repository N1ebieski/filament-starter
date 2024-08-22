<div class="flex items-center">
    <div wire:offline>
        <div 
            x-data
            x-tooltip="{
                content: '{{ trans('offline.offline_mode') }}',
                theme: $store.theme,
            }"        
        >
            <x-filament::icon
                icon="hugeicons-cellular-network-offline"
                class="pulsating-icon"
            />
        </div>
    </div>
</div>