<div 
    x-init="
        const sidebar = document.querySelector('.fi-sidebar');
        
        if (sidebar) sidebar.classList.remove('hidden');        
    "
    x-on:pwa:fetched.window="$store.sidebar.close()"
    x-on:livewire:navigating.window="
        const sidebar = document.querySelector('.fi-sidebar');

        if (sidebar) sidebar.classList.add('hidden');
    "
></div>