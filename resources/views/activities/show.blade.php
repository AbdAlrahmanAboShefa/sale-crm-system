@extends('layouts.app')

@php
    $routePrefix = 'admin';
    if (auth()->user()->hasRole('Manager')) $routePrefix = 'manager';
    elseif (auth()->user()->hasRole('Agent')) $routePrefix = 'agent';
@endphp

@section('title'){{ __('messages.activities.activity_details') }}@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <span class="px-3 py-1 text-sm rounded-full 
                @if($activity->is_done) dark-badge dark-badge-emerald @else dark-badge dark-badge-amber @endif">
                {{ $activity->is_done ? __('messages.activities.completed') : __('messages.activities.pending') }}
            </span>
            <span class="dark-badge dark-badge-cyan">
                {{ $activity->type }}
            </span>
        </div>
        <a href="{{ route($routePrefix . '.activities.index') }}" class="dark-btn dark-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            {{ __('messages.common.back') }}
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="dark-card p-6">
                <h2 class="text-lg font-semibold text-slate-100 mb-4">{{ __('messages.activities.activity_details') }}</h2>
                
                <div class="prose prose-sm max-w-none text-slate-400">
                    <p>{{ $activity->note }}</p>
                </div>

                @if($activity->outcome)
                <div class="mt-4 pt-4 border-t border-[#2d3748]">
                    <span class="text-sm text-slate-500">{{ __('messages.activities.outcome') ?? 'Outcome' }}: </span>
                    @php $outcomeBadge = $activity->outcome === 'Positive' ? 'dark-badge-emerald' : ($activity->outcome === 'Negative' ? 'dark-badge-rose' : 'dark-badge-gray'); @endphp
                    <span class="dark-badge {{ $outcomeBadge }}">{{ $activity->outcome }}</span>
                </div>
                @endif

                <div class="mt-4 pt-4 border-t border-[#2d3748] flex gap-2 flex-wrap">
                    <a href="{{ route($routePrefix . '.activities.edit', $activity) }}" class="dark-btn dark-btn-primary">
                        <i class="fas fa-pencil"></i>
                        {{ __('messages.common.edit') }}
                    </a>
                    <form action="{{ route($routePrefix . '.activities.destroy', $activity) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="dark-btn bg-rose-600 hover:bg-rose-500 text-white" onclick="return confirm('{{ __('messages.messages.delete_confirm') }}')">
                            <i class="fas fa-trash"></i>
                            {{ __('messages.common.delete') }}
                        </button>
                    </form>
                    @if(!$activity->is_done)
                    <button type="button" onclick="markDone({{ $activity->id }})" class="dark-btn dark-btn-secondary">
                        <i class="fas fa-check"></i>
                        {{ __('messages.activities.mark_done') }}
                    </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="dark-card p-6">
                <h2 class="text-lg font-semibold text-slate-100 mb-4">{{ __('messages.activities.details') ?? 'Details' }}</h2>
                
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm text-slate-500">{{ __('messages.activities.due_date') }}</dt>
                        <dd class="text-slate-200">
                            @if($activity->isOverdue() && !$activity->is_done)
                                <span class="text-rose-400 font-medium">
                                    {{ $activity->due_date->format('M d, Y h:i A') }} ({{ __('messages.activities.overdue') }})
                                </span>
                            @else
                                {{ $activity->due_date->format('M d, Y h:i A') }}
                            @endif
                        </dd>
                    </div>
                    @if($activity->duration_minutes)
                    <div>
                        <dt class="text-sm text-slate-500">{{ __('messages.activities.duration') ?? 'Duration' }}</dt>
                        <dd class="text-slate-200">{{ $activity->duration_minutes }} {{ __('messages.activities.minutes') ?? 'minutes' }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            @if($activity->contact)
            <div class="dark-card p-6">
                <h2 class="text-lg font-semibold text-slate-100 mb-4">{{ __('messages.contacts.title') }}</h2>
                <div class="flex items-center gap-3">
                    <div class="dark-avatar">
                        {{ strtoupper(substr($activity->contact->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-medium text-slate-200">{{ $activity->contact->name }}</p>
                        <p class="text-xs text-slate-500">{{ $activity->contact->email }}</p>
                    </div>
                </div>
            </div>
            @endif

            @if($activity->deal)
            <div class="dark-card p-6">
                <h2 class="text-lg font-semibold text-slate-100 mb-4">{{ __('messages.deals.deal') }}</h2>
                <a href="{{ route($routePrefix . '.deals.show', $activity->deal) }}" class="block">
                    <p class="font-medium text-cyan-400 hover:text-cyan-300">{{ $activity->deal->title }}</p>
                    <span class="dark-badge dark-badge-gray text-xs mt-1 inline-block">{{ $activity->deal->stage }}</span>
                </a>
            </div>
            @endif

            <div class="dark-card p-6">
                <h2 class="text-lg font-semibold text-slate-100 mb-4">{{ __('messages.deals.assigned_to') }}</h2>
                <div class="flex items-center gap-3">
                    <div class="dark-avatar">
                        {{ strtoupper(substr($activity->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-medium text-slate-200">{{ $activity->user->name }}</p>
                        <p class="text-xs text-slate-500">{{ $activity->user->email }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function markDone(activityId) {
        fetch('/' + '{{ $routePrefix }}' + '/activities/' + activityId + '/done', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
</script>
@endpush
@endsection
