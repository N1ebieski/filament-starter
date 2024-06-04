<div>
    @if ($spotlightAction->isVisible())
    <span x-data x-on:click="$dispatch('toggle-spotlight')">    
        {{ $spotlightAction }}
    </span>
    @endif
</div>