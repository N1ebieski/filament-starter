<div class="spotlight">
    @isset($jsPath)
        <script>{!! file_get_contents($jsPath) !!}</script>
    @endisset
    @isset($cssPath)
        <style>{!! file_get_contents($cssPath) !!}</style>
    @endisset

    <div 
        x-data="spotlight({
            componentId: '{{ $this->id() }}',
            placeholder: '{{ trans('livewire-ui-spotlight::spotlight.placeholder') }}',
            commands: @js($commands),
            showResultsWithoutInput: '{{ config('livewire-ui-spotlight.show_results_without_input') }}',
        })"
        x-init="_init()"
        x-show="isOpen"
        x-cloak
        @foreach(config('livewire-ui-spotlight.shortcuts') as $key)
            x-on:keydown.window.prevent.cmd.{{ $key }}="toggleOpen()"
            x-on:keydown.window.prevent.ctrl.{{ $key }}="toggleOpen()"
        @endforeach
        x-on:keydown.backspace="!input.length ? reset() : null" 
        x-on:keydown.window.escape="isOpen = false"
        x-on:toggle-spotlight.window="toggleOpen()"
        x-on:livewire:navigating.window="dispose()"
        class="fixed z-50 px-2 pt-16 flex items-start justify-center inset-0 sm:pt-24"
    >
        <div 
            x-show="isOpen" 
            x-on:click="isOpen = false" 
            x-transition:enter="ease-out duration-200" 
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" 
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100" 
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 transition-opacity"
        >
            <div class="absolute inset-0 bg-gray-900 opacity-50 backdrop"></div>
        </div>

        <div 
            x-show="isOpen" 
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" 
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-150" 
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative bg-gray-900 rounded-lg overflow-hidden shadow-xl transform transition-all max-w-lg w-full"
        >
            <div class="relative">
                <div class="absolute h-full right-5 flex items-center">
                    <svg 
                        class="animate-spin h-5 w-5 text-white d-none" 
                        xmlns="http://www.w3.org/2000/svg" 
                        fill="none"
                        viewBox="0 0 24 24" 
                        wire:loading.delay 
                        wire:loading.class.remove="d-none"
                    >
                        <circle 
                            class="opacity-25" 
                            cx="12" 
                            cy="12" 
                            r="10" 
                            stroke="currentColor"
                            stroke-width="4"
                        ></circle>
                        <path 
                            class="opacity-75" 
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                        ></path>
                    </svg>
                </div>
                <input 
                    x-on:keydown.tab.prevent="" 
                    x-on:keydown.prevent.stop.enter="go()" 
                    x-on:keydown.prevent.arrow-up="selectUp()"
                    x-on:keydown.prevent.arrow-down="selectDown()" 
                    x-ref="input" 
                    x-model="input"
                    type="text"
                    style="caret-color: #6b7280; border: 0 !important;"
                    class="appearance-none w-full bg-transparent px-6 py-4 text-gray-300 text-lg placeholder-gray-500 focus:border-0 focus:border-transparent focus:shadow-none outline-none focus:outline-none"
                    x-bind:placeholder="inputPlaceholder"
                >
            </div>
            <div 
                class="border-t border-gray-800" 
                x-show="filteredItems().length > 0 || selectedCommand !== null" 
                style="display:none;"
            >
                <ul 
                    x-ref="results" 
                    style="max-height: 365px;" 
                    class="overflow-y-auto"
                >

                    <li x-show="currentDependency">
                        <button 
                            x-on:click="reset()"
                            class="block w-full px-6 py-3 text-left"
                            x-ref="commands"
                            x-bind:class="{ 'bg-gray-700': selected === -1, 'hover:bg-gray-800': selected !== -1 }"
                        >
                            <span class="text-gray-300">
                                <x-heroicon-m-arrow-up class="w-5 h-5 inline" />
                                <span>...</span>
                            </span>
                        </button>
                    </li>

                    <template x-for="(item, i) in filteredItems()" x-bind:key>
                        <li class="item">
                            <button 
                                x-on:click="go(item[0].item.id)" 
                                class="block w-full px-6 py-3 text-left"
                                x-bind:class="{ 'bg-gray-700': selected === i, 'hover:bg-gray-800': selected !== i }"
                            >
                                <span 
                                    x-text="item[0].item.name"
                                    x-bind:class="{ 'text-gray-300': selected !== i, 'text-white': selected === i }"
                                ></span>
                                <span 
                                    x-text="item[0].item.description" 
                                    class="ml-1"
                                    x-bind:class="{ 'text-gray-500': selected !== i, 'text-gray-400': selected === i }"
                                ></span>
                            </button>
                        </li>
                    </template>
                </ul>
            </div>
            <div class="border-t border-gray-800">
                <div class="flex justify-between gap-1 mx-3 mb-4 mt-3">
                    <div class="flex flex-row flex-auto truncate gap-1">
                        <small>
                            <x-heroicon-m-arrow-small-up class="w-5 h-5 border border-white rounded text-gray-300" />
                        </small>
                        <small>
                            <x-heroicon-m-arrow-small-down class="w-5 h-5 border border-white rounded text-gray-300" />
                        </small>
                        <small class="text-gray-300">{{ trans('spotlight.navigation') }}</small>
                    </div>                
                    <div class="flex flex-row flex-auto truncate gap-1" x-show="currentDependency">
                        <small>
                            <x-heroicon-m-arrow-long-left class="w-10 h-5 border border-white rounded text-gray-300" />
                        </small>
                        <small class="text-gray-300">{{ trans('spotlight.back') }}</small>
                    </div>
                    <div class="flex flex-row flex-auto truncate gap-1">
                        <small class="border border-white rounded font-sans text-gray-300 px-1">
                            <span>{{ $this->shortcutsAsString }}</span>
                        </small>
                        <small class="text-gray-300">{{ trans('spotlight.enable') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
