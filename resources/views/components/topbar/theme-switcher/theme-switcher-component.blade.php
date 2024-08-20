<div
    x-data="{ theme: null }"
    x-init="
        theme = localStorage.getItem('theme') || @js(filament()->getDefaultThemeMode()->value)
    "
    x-on:theme-changed.window="theme = $event.detail"
>
    @if (filament()->hasDarkMode() && (! filament()->hasDarkModeForced()))
    <x-filament::dropdown>
        <x-slot name="trigger">
            @foreach([
                'light' => 'heroicon-m-sun',
                'dark' => 'heroicon-m-moon',
                'system' => 'heroicon-m-computer-desktop'
            ] as $theme => $icon)
                @php
                    $label = __("filament-panels::layout.actions.theme_switcher.{$theme}.label");
                @endphp

                <x-filament::icon-button
                    :icon="$icon"
                    color="gray"
                    :tooltip="$label"  
                    x-show="theme === '{{ $theme }}'"
                    x-cloak
                />
            @endforeach
        </x-slot>
        
        <x-filament::dropdown.list>
            <x-filament-panels::theme-switcher />
        </x-filament::dropdown.list>
    </x-filament::dropdown>
    @endif
</div>