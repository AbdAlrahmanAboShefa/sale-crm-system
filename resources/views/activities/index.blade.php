@extends('layouts.app')
@section('content')
    @if(session('success'))
    <div class="mb-6 dark-toast border-emerald-500/30">
        <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center">
            <i class="fas fa-check text-emerald-400"></i>
        </div>
        <span class="text-emerald-300 font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <div class="dark-card overflow-hidden">
        <div class="p-6 border-b border-[#2d3748]">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-3 flex-wrap">
                    <div class="flex items-center gap-2 text-slate-400">
                        <i class="fas fa-filter"></i>
                    </div>
                    <form method="GET" class="flex items-center gap-2 flex-wrap">
                        <select name="type" onchange="this.form.submit()" class="dark-select text-sm">
                            <option value="">{{ __('messages.common.all') }} {{ __('messages.activities.type') }}</option>
                            @foreach(['Call', 'Meeting', 'Email', 'Task', 'Demo'] as $type)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                        <select name="is_done" onchange="this.form.submit()" class="dark-select text-sm">
                            <option value="">{{ __('messages.common.all') }} {{ __('messages.common.status') }}</option>
                            <option value="0" {{ request('is_done') === '0' ? 'selected' : '' }}>{{ __('messages.activities.pending') }}</option>
                            <option value="1" {{ request('is_done') == '1' ? 'selected' : '' }}>{{ __('messages.activities.completed') }}</option>
                        </select>
                        <input type="date" name="due_date" value="{{ request('due_date') }}" onchange="this.form.submit()" class="dark-input text-sm">
                    </form>
                </div>
                <a href="{{ route($routePrefix . '.activities.create') }}" class="dark-btn dark-btn-primary">
                    <i class="fas fa-plus"></i>
                    {{ __('messages.activities.log_activity') }}
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="dark-table">
                <thead>
                    <tr>
                        <th class="!border-t-transparent !bg-slate-800/50">{{ __('messages.activities.type') }}</th>
                        <th class="!border-t-transparent !bg-slate-800/50">{{ __('messages.activities.notes') }}</th>
                        <th class="!border-t-transparent !bg-slate-800/50">{{ __('messages.contacts.title') }}</th>
                        <th class="!border-t-transparent !bg-slate-800/50">{{ __('messages.deals.deal') }}</th>
                        <th class="!border-t-transparent !bg-slate-800/50">{{ __('messages.activities.due_date') }}</th>
                        <th class="!border-t-transparent !bg-slate-800/50">{{ __('messages.common.status') }}</th>
                        <th class="!border-t-transparent !bg-slate-800/50">{{ __('messages.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                    <tr class="{{ $activity->isOverdue() ? 'border-l-4 border-rose-500 bg-rose-500/5' : '' }}">
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center
                                    @if($activity->type === 'Call') bg-blue-500/15 text-blue-400
                                    @elseif($activity->type === 'Meeting') bg-violet-500/15 text-violet-400
                                    @elseif($activity->type === 'Email') bg-emerald-500/15 text-emerald-400
                                    @elseif($activity->type === 'Demo') bg-orange-500/15 text-orange-400
                                    @else bg-slate-500/15 text-slate-400 @endif">
                                    <i class="fas fa-{{ $activity->type === 'Call' ? 'phone' : ($activity->type === 'Meeting' ? 'calendar' : ($activity->type === 'Email' ? 'envelope' : ($activity->type === 'Demo' ? 'desktop' : 'tasks'))) }}"></i>
                                </div>
                                <span class="text-sm font-semibold text-slate-200">{{ $activity->type }}</span>
                            </div>
                        </td>
                        <td>
                            <p class="text-sm text-slate-400 max-w-xs truncate">{{ $activity->note ?? __('messages.activities.no_notes') }}</p>
                        </td>
                        <td>
                            <span class="text-sm text-slate-300">{{ $activity->contact?->name ?? '-' }}</span>
                        </td>
                        <td>
                            <span class="text-sm text-slate-400">{{ $activity->deal?->title ?? '-' }}</span>
                        </td>
                        <td>
                            @if($activity->due_date)
                            <span class="text-sm {{ $activity->isOverdue() ? 'text-rose-400 font-semibold' : 'text-slate-400' }}">
                                {{ $activity->due_date->format('M j, Y g:i A') }}
                            </span>
                            @else
                            <span class="text-sm text-slate-600">-</span>
                            @endif
                        </td>
                        <td>
                            @if($activity->is_done)
                            <span class="dark-badge dark-badge-emerald">{{ __('messages.activities.completed') }}</span>
                            @elseif($activity->isOverdue())
                            <span class="dark-badge dark-badge-rose">{{ __('messages.activities.overdue') }}</span>
                            @else
                            <span class="dark-badge dark-badge-amber">{{ __('messages.activities.pending') }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center gap-1">
                                @if(!$activity->is_done)
                                <button onclick="markDone({{ $activity->id }})" class="w-8 h-8 rounded-lg flex items-center justify-center text-emerald-400 hover:bg-emerald-500/10 transition-all" title="{{ __('messages.activities.mark_done') }}">
                                    <i class="fas fa-check"></i>
                                </button>
                                @endif
                                <a href="{{ route($routePrefix . '.activities.edit', $activity) }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-cyan-400 hover:bg-cyan-500/10 transition-all" title="{{ __('messages.common.edit') }}">
                                    <i class="fas fa-pencil"></i>
                                </a>
                                <form action="{{ route($routePrefix . '.activities.destroy', $activity) }}" method="POST" x-data="{ showModal: false }" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="button" @click="showModal = true" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition-all" title="{{ __('messages.common.delete') }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <div x-show="showModal" x-transition class="dark-modal-backdrop flex items-center justify-center p-4" style="display: none;">
                                        <div class="dark-modal max-w-md w-full p-6" @click.stop>
                                            <div class="text-center">
                                                <div class="w-16 h-16 rounded-2xl bg-rose-500/20 flex items-center justify-center mx-auto mb-4">
                                                    <i class="fas fa-exclamation-triangle text-rose-400 text-2xl"></i>
                                                </div>
                                                <h3 class="text-xl font-bold text-slate-100 mb-2">{{ __('messages.activities.delete_activity') ?? 'Delete Activity' }}</h3>
                                                <p class="text-slate-400 mb-6">{{ __('messages.messages.delete_confirm') }}</p>
                                                <div class="flex justify-center gap-3">
                                                    <button type="button" @click="showModal = false" class="dark-btn dark-btn-secondary">{{ __('messages.common.cancel') }}</button>
                                                    <button type="submit" class="dark-btn bg-rose-600 hover:bg-rose-500 text-white">{{ __('messages.common.delete') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-16">
                            <div class="dark-empty-state">
                                <div class="dark-empty-icon">
                                    <i class="fas fa-calendar-times"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-slate-300 mb-2">{{ __('messages.activities.no_activities') }}</h3>
                                <p class="text-slate-500 mb-6">{{ __('messages.activities.log_first') }}</p>
                                <a href="{{ route($routePrefix . '.activities.create') }}" class="dark-btn dark-btn-primary">
                                    <i class="fas fa-plus"></i>
                                    {{ __('messages.activities.log_activity') }}
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($activities->hasPages())
        <div class="px-6 py-4 border-t border-[#2d3748]">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500">
                    Showing {{ $activities->firstItem() ?? 0 }} to {{ $activities->lastItem() ?? 0 }} of {{ $activities->total() }} results
                </p>
                <div class="dark-pagination">
                    @if($activities->onFirstPage())
                        <span class="opacity-50"><i class="fas fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $activities->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
                    @endif
                    
                    @foreach($activities->getUrlRange(1, $activities->lastPage()) as $page => $url)
                        @if($page == $activities->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                    
                    @if($activities->hasMorePages())
                        <a href="{{ $activities->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
                    @else
                        <span class="opacity-50"><i class="fas fa-chevron-right"></i></span>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    @push('scripts')
    <script>
    function markDone(id) {
        fetch(`{{ url('/') }}/{{ $routePrefix }}/activities/${id}/done`, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
        }).then(() => location.reload());
    }
    </script>
    @endpush
@endsection
