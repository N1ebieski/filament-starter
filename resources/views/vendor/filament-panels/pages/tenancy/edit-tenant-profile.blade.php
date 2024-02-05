<x-filament-panels::page>
    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

   @php
        $relationManagers = $this->getRelations();
   @endphp

   <x-filament-panels::resources.relation-managers
        :active-locale="null"
        :active-manager="0"
        :content-tab-label="'Tab'"
        :managers="$relationManagers"
        :owner-record="$tenant"
        :page-class="static::class"
   >
   </x-filament-panels::resources.relation-managers>    
</x-filament-panels::page>
