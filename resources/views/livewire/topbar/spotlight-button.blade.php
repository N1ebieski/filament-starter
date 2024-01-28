<div>
    @if ($this->spotlightAction->isVisible())
    <span x-data x-on:click="$dispatch('toggle-spotlight')">    
        {{ $this->spotlightAction }}
    </span>
    @endif
</div>