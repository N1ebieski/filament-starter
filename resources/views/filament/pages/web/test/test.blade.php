<x-filament-panels::page>
    <div x-data="{ tab: 'tab1' }">
        <x-filament::tabs label="Content tabs">
            <x-filament::tabs.item 
                x-on:click="tab = 'tab1'" 
                :alpine-active="'tab === \'tab1\''"
            >
                Tab 1
            </x-filament::tabs.item>
        
            <x-filament::tabs.item
                x-on:click="tab = 'tab2'" 
                :alpine-active="'tab === \'tab2\''"        
            >
                Tab 2
            </x-filament::tabs.item>
        
            <x-filament::tabs.item
                x-on:click="tab = 'tab3'" 
                :alpine-active="'tab === \'tab3\''"        
            >
                Tab 3
            </x-filament::tabs.item>
        </x-filament::tabs>

        <div class="mt-2">
            <div 
                x-show="tab === 'tab1'" 
                x-cloak
                x-transition.opacity
                class="absolute"           
            >
                Pierwszy Tab
            </div>
            <div 
                x-show="tab === 'tab2'" 
                x-cloak
                x-transition.opacity
                class="absolute"
            >
                Drugi tab
            </div>
        </div>
    </div> 
</x-filament-panels::page>
