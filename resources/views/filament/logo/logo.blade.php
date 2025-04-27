@php
    $brandName = filament()->getBrandName();
    $isSidebar = !isset($this) || !$this instanceof \Filament\Pages\SimplePage;
@endphp

<div
    class="flex items-center"
    @if($isSidebar)
    x-on:click="$store.sidebar.close()"
    @endif
>
    <img
        alt="{{ __('filament-panels::layout.logo.alt', ['name' => $brandName]) }}"
        src="{{ asset('images/logo.svg') }}"
        class="fi-logo mr-4 dark:logo"
        style="height: 2rem;"
    />
    <div
        @class([
            'text-xl font-bold leading-5 tracking-tight text-gray-950 dark:text-white',
            'lg:hidden xl:block' => $isSidebar,
        ])
    >
        {{ $brandName }}
    </div>
</div>
@if($isSidebar)
<div
    class="absolute end-4 top-5"
    x-data
    x-cloak
    x-show="$store.sidebar.isOpen"
>
    <x-filament::icon-button
        color="gray"
        icon="heroicon-o-x-mark"
        icon-alias="sidebar.close-button"
        icon-size="lg"
        :label="__('filament::components/modal.actions.close.label')"
        tabindex="-1"
        x-on:click.prevent="$store.sidebar.close()"
        class="fi-modal-close-btn"
    />
</div>
@endif
