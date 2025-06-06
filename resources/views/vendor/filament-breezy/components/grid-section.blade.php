@props(['title','description'])
<x-filament::grid
    :default="false"
    :md="false"
    @class(["pt-6 gap-4 filament-breezy-grid-section md:grid-cols-[1fr_2fr]"])
    {{ $attributes }}
>

    <x-filament::grid.column>
        <h3 @class(['text-lg font-medium filament-breezy-grid-title'])>{{$title}}</h3>

        <p @class(['mt-1 text-sm text-gray-500 filament-breezy-grid-description'])>
            {{$description}}
        </p>
    </x-filament::grid.column>

    <x-filament::grid.column>
        {{ $slot }}
    </x-filament::grid.column>

</x-filament::grid>
