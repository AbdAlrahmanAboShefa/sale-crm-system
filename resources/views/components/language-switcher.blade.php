<div x-data="{ open: false }" class="relative">
    <button 
        @click="open = !open" 
        @click.away="open = false"
        class="flex items-center gap-2 px-3 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 transition-colors text-slate-700"
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
        class="absolute mt-2 w-40 bg-white rounded-lg shadow-lg border border-slate-200 py-1 z-50 {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }}"
        style="left: 0;"
    >
        <a 
            href="{{ route('language.switch', 'en') }}" 
            class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 transition-colors {{ app()->getLocale() === 'en' ? 'bg-blue-50 text-blue-600 font-medium' : '' }}"
        >
            <span class="text-lg">🇺🇸</span>
            <span>English</span>
        </a>
        <a 
            href="{{ route('language.switch', 'ar') }}" 
            class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 transition-colors {{ app()->getLocale() === 'ar' ? 'bg-blue-50 text-blue-600 font-medium' : '' }}"
        >
            <span class="text-lg">🇸🇦</span>
            <span>العربية</span>
        </a>
    </div>
</div>
