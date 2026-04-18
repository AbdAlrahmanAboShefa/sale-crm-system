<div x-data="{ open: false }" class="relative">
    <button @click="open = !open" class="relative p-2 text-slate-400 hover:text-white transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-rose-500 rounded-full animate-pulse">
                {{ auth()->user()->unreadNotifications->count() > 99 ? '99+' : auth()->user()->unreadNotifications->count() }}
            </span>
        @endif
    </button>

    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-80 bg-slate-800 rounded-xl shadow-2xl border border-slate-700 z-50 overflow-hidden" style="display: none;">
        <div class="px-4 py-3 border-b border-slate-700 flex justify-between items-center bg-slate-800/80 backdrop-blur-sm">
            <h3 class="font-semibold text-slate-100">Notifications</h3>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <button onclick="markAllRead()" class="text-xs text-cyan-400 hover:text-cyan-300 transition-colors">Mark all read</button>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto">
            @forelse(auth()->user()->notifications()->take(10)->get() as $notification)
                <div class="px-4 py-3 border-b border-slate-700/50 {{ $notification->read_at ? 'bg-slate-800/50' : 'bg-slate-700/30' }} hover:bg-slate-700/50 transition-colors">
                    <p class="text-sm text-slate-200">{{ $notification->data['message'] ?? 'New notification' }}</p>
                    <p class="text-xs text-slate-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                </div>
            @empty
                <div class="px-4 py-8 text-center">
                    <div class="w-12 h-12 rounded-xl bg-slate-700/50 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-bell-slash text-slate-500"></i>
                    </div>
                    <p class="text-slate-500 text-sm">No notifications</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
function markAllRead() {
    fetch('{{ route('notifications.markAllRead') }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
    }).then(() => location.reload());
}
</script>
@endpush
