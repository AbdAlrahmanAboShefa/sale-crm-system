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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="dark-card p-6">
                <div class="flex flex-col items-center mb-6">
                    <div class="dark-avatar w-20 h-20 text-3xl mb-4">
                        {{ strtoupper(substr($contact->name, 0, 1)) }}
                    </div>
                    <h3 class="text-xl font-bold text-slate-100">{{ $contact->name }}</h3>
                    @php
                    $statusBadge = match($contact->status) {
                        'Lead' => 'dark-badge-cyan',
                        'Prospect' => 'dark-badge-amber',
                        'Client' => 'dark-badge-emerald',
                        'Lost' => 'dark-badge-rose',
                        default => 'dark-badge-gray',
                    };
                @endphp
                    <span class="dark-badge {{ $statusBadge }} mt-2">{{ $contact->status }}</span>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center gap-3 p-3 bg-slate-800/50 rounded-lg">
                        <i class="fas fa-envelope text-slate-500 w-5"></i>
                        <div>
                            <p class="text-xs text-slate-500">{{ __('messages.contacts.email') }}</p>
                            <a href="mailto:{{ $contact->email }}" class="text-sm text-cyan-400 hover:underline">{{ $contact->email }}</a>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-slate-800/50 rounded-lg">
                        <i class="fas fa-phone text-slate-500 w-5"></i>
                        <div>
                            <p class="text-xs text-slate-500">{{ __('messages.contacts.phone') }}</p>
                            <p class="text-sm text-slate-200">{{ $contact->phone ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-slate-800/50 rounded-lg">
                        <i class="fas fa-building text-slate-500 w-5"></i>
                        <div>
                            <p class="text-xs text-slate-500">{{ __('messages.contacts.company') }}</p>
                            <p class="text-sm text-slate-200">{{ $contact->company ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-slate-800/50 rounded-lg">
                        <i class="fas fa-globe text-slate-500 w-5"></i>
                        <div>
                            <p class="text-xs text-slate-500">{{ __('messages.common.website') }}</p>
                            @if($contact->website)
                            <a href="{{ $contact->website }}" target="_blank" rel="noopener noreferrer" class="text-sm text-cyan-400 hover:underline">{{ Str::limit($contact->website, 25) }}</a>
                            @else
                            <p class="text-sm text-slate-500">-</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-slate-800/50 rounded-lg">
                        <i class="fas fa-tag text-slate-500 w-5"></i>
                        <div>
                            <p class="text-xs text-slate-500">{{ __('messages.contacts.source') }}</p>
                            <p class="text-sm text-slate-200 capitalize">{{ __("messages.contacts.sources." . $contact->source) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-slate-800/50 rounded-lg">
                        <i class="fas fa-user text-slate-500 w-5"></i>
                        <div>
                            <p class="text-xs text-slate-500">{{ __('messages.common.owner') }}</p>
                            <p class="text-sm text-slate-200">{{ $contact->user->name ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                @if($contact->tags && count($contact->tags) > 0)
                <div class="mt-6 pt-6 border-t border-[#2d3748]">
                    <h4 class="text-sm font-medium text-slate-300 mb-3">{{ __('messages.common.tags') }}</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($contact->tags as $tag)
                        <span class="dark-badge dark-badge-gray text-xs">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="mt-6 pt-6 border-t border-[#2d3748] flex gap-2">
                    <a href="{{ route($routePrefix . '.contacts.edit', $contact) }}" class="flex-1 dark-btn dark-btn-primary">
                        <i class="fas fa-pencil"></i>{{ __('messages.common.edit') }}
                    </a>
                    <form action="{{ route($routePrefix . '.contacts.destroy', $contact) }}" method="POST" x-data="{ showModal: false }" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="button" @click="showModal = true" class="dark-btn w-full border border-rose-500/50 text-rose-400 hover:bg-rose-500/10">
                            <i class="fas fa-trash"></i>{{ __('messages.common.delete') }}
                        </button>
                        <div x-show="showModal" x-transition class="dark-modal-backdrop flex items-center justify-center p-4" style="display: none;">
                            <div class="dark-modal max-w-md w-full p-6" @click.stop>
                                <div class="text-center">
                                    <div class="w-16 h-16 rounded-2xl bg-rose-500/20 flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-exclamation-triangle text-rose-400 text-2xl"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-100 mb-2">{{ __('messages.common.confirm_delete') }}</h3>
                                    <p class="text-slate-400 mb-6">{{ __('messages.common.delete_warning') }}</p>
                                    <div class="flex justify-center gap-3">
                                        <button type="button" @click="showModal = false" class="dark-btn dark-btn-secondary">{{ __('messages.common.cancel') }}</button>
                                        <button type="submit" class="dark-btn bg-rose-600 hover:bg-rose-500 text-white">{{ __('messages.common.delete') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="dark-card p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-slate-100">{{ __('messages.activities.activity_timeline') }}</h3>
                    <a href="{{ route($routePrefix . '.activities.create') }}" class="dark-btn dark-btn-primary">
                        <i class="fas fa-plus"></i>{{ __('messages.activities.log_activity') }}
                    </a>
                </div>

                @if($contact->activities->count() > 0)
                <div class="space-y-4">
                    @foreach($contact->activities as $activity)
                    <div class="dark-activity-item">
                        <div class="dark-activity-icon
                            @if($activity->type === 'Call') bg-blue-500/15 text-blue-400
                            @elseif($activity->type === 'Meeting') bg-emerald-500/15 text-emerald-400
                            @elseif($activity->type === 'Email') bg-violet-500/15 text-violet-400
                            @elseif($activity->type === 'Demo') bg-orange-500/15 text-orange-400
                            @else bg-slate-500/15 text-slate-400 @endif">
                            <i class="fas fa-{{ $activity->type === 'Call' ? 'phone' : ($activity->type === 'Meeting' ? 'calendar' : ($activity->type === 'Email' ? 'envelope' : ($activity->type === 'Demo' ? 'desktop' : 'clipboard-list'))) }}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-medium text-slate-200">{{ __("messages.activities.types." . strtolower($activity->type)) }}</span>
                                @if($activity->is_done)
                                <span class="dark-badge dark-badge-emerald text-xs">{{ __('messages.activities.completed') }}</span>
                                @elseif($activity->isOverdue())
                                <span class="dark-badge dark-badge-rose text-xs">{{ __('messages.activities.overdue') }}</span>
                                @else
                                <span class="dark-badge dark-badge-amber text-xs">{{ __('messages.activities.pending') }}</span>
                                @endif
                            </div>
                            <p class="text-sm text-slate-400 mb-2">{{ $activity->note ?? __('messages.activities.no_notes') }}</p>
                            <div class="flex items-center gap-4 text-xs text-slate-500">
                                @if($activity->deal)
                                <span><i class="fas fa-hand-holding-dollar me-1"></i>{{ $activity->deal->title }}</span>
                                @endif
                                <span><i class="fas fa-clock me-1"></i>{{ $activity->due_date ? $activity->due_date->format('M j, Y g:i A') : '-' }}</span>
                                <span><i class="fas fa-user me-1"></i>{{ $activity->user->name ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="dark-empty-state">
                    <div class="dark-empty-icon">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                    <h4 class="text-lg font-medium text-slate-300 mb-1">{{ __('messages.activities.no_activities') }}</h4>
                    <p class="text-slate-500 mb-4">{{ __('messages.activities.log_first') }}</p>
                    <a href="{{ route($routePrefix . '.activities.create') }}" class="dark-btn dark-btn-primary">
                        <i class="fas fa-plus"></i>{{ __('messages.activities.log_activity') }}
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route($routePrefix . '.contacts.index') }}" class="inline-flex items-center text-slate-400 hover:text-slate-200">
            <i class="fas fa-arrow-left me-2"></i>{{ __('messages.common.back_to') }} {{ __('messages.contacts.title') }}
        </a>
    </div>
@endsection
