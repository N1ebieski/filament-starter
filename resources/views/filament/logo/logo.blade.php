@php
    $brandName = filament()->getBrandName();
@endphp

<div 
    class="flex items-center"
    x-data
    x-init="
        const sidebar = document.querySelector('.fi-sidebar');

        sidebar.classList.remove('hidden');        
    "
    x-on:click="$store.sidebar.close()"
    x-on:pwa:fetched.window="$store.sidebar.close()"
    x-on:livewire:navigating.window="
        const sidebar = document.querySelector('.fi-sidebar');

        sidebar.classList.add('hidden');
    "
>
    <img
        alt="{{ __('filament-panels::layout.logo.alt', ['name' => $brandName]) }}"
        src="{{ asset('images/logo.svg') }}"
        class="fi-logo mr-4 dark:logo"
        style="height: 2rem;"
    />
    <div class="lg:hidden xl:block text-xl font-bold leading-5 tracking-tight text-gray-950 dark:text-white">
        {{ $brandName }}
    </div>
</div>