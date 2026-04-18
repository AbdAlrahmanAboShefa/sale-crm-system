<div x-data="{ open: false }" class="relative">
    <button 
        @click="open = !open" 
        @click.away="open = false"
        class="flex items-center gap-2 px-3 py-2 rounded-lg bg-slate-700/50 hover:bg-slate-600/50 transition-colors text-slate-300 hover:text-white border border-slate-600/50"
    >
        <i class="fas fa-globe"></i>
        <span class="text-sm font-medium uppercase">{{ app()->getLocale() }}</span>
        <i class="fas fa-chevron-down text-xs"></i>
    </button>
    
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute mt-2 w-44 bg-slate-800 rounded-lg shadow-xl border border-slate-700 py-1 z-50 right-0"
        style="display: none;"
    >
        <a 
            href="{{ route('language.switch', 'en') }}" 
            class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-300 hover:bg-slate-700 hover:text-white transition-colors {{ app()->getLocale() === 'en' ? 'bg-cyan-500/20 text-cyan-400 font-medium' : '' }}"
        >
            <span class="text-lg">🇺🇸</span>
            <span>English</span>
        </a>
        <a 
            href="{{ route('language.switch', 'ar') }}" 
            class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-300 hover:bg-slate-700 hover:text-white transition-colors {{ app()->getLocale() === 'ar' ? 'bg-cyan-500/20 text-cyan-400 font-medium' : '' }}"
        >
            <span class="text-lg">🇸🇦</span>
            <span>العربية</span>
        </a>
    </div>
</div>
