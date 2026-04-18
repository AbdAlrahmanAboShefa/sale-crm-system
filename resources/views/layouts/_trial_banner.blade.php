    @auth
        @if(
            auth()->user()->tenant &&
            !auth()->user()->hasRole('Super Admin') &&
            auth()->user()->tenant->isOnTrial() &&
            now()->diffInDays(auth()->user()->tenant->trial_ends_at) <= 7
        )
        @php $daysLeft = (int) now()->diffInDays(auth()->user()->tenant->trial_ends_at); @endphp
        <div class="bg-yellow-50 border-b border-yellow-200 px-6 py-2 flex items-center justify-between text-sm">
            <span class="text-yellow-800">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Your trial expires in {{ $daysLeft }} {{ $daysLeft === 1 ? 'day' : 'days' }}.
            </span>
            <a href="{{ route('billing.upgrade') }}" class="text-yellow-900 font-semibold underline hover:no-underline">
                Upgrade Now
            </a>
        </div>
        @endif
    @endauth