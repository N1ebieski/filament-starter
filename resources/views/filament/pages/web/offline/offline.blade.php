<x-filament-panels::page.simple>
    <x-slot name="subheading">
        <span>{{ trans('offline.back_to') }}</span>

        <x-filament::link
            x-on:click="window.history.back()"
            tag="button"
        >
            {{ trans('offline.previous_page') }}
        </x-filament::link>
    </x-slot>

    <div class="mx-auto">
        <x-filament::icon
            icon="hugeicons-cellular-network-offline"
            class="pulsating-icon"
        />
    </div>
</x-filament-panels::page.simple>
