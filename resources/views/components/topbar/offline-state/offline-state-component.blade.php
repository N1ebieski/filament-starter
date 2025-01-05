<div class="flex items-center">
    <div>
        <div
            x-data="{ show: !navigator.onLine }"
            x-init="
                window.addEventListener('online', () => show = false);
                window.addEventListener('offline', () => show = true);
            "
            x-tooltip="{
                content: '{{ trans('offline.offline_mode') }}',
                theme: $store.theme,
            }"
        >
            <x-filament::icon
                icon="hugeicons-cellular-network-offline"
                class="pulsating-icon"
                x-show="show"
                x-cloak
            />
        </div>
    </div>
</div>
