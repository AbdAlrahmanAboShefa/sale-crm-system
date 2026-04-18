@extends('layouts.app')
@section('content')
    @php
        $clientName = $deal->contact?->name ?? $deal->user->name;
        $clientEmail = $deal->contact?->email ?? $deal->user->email;
    @endphp

    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-slate-100">{{ $deal->title }}</h1>
            <div class="flex gap-2">
                <a href="{{ route($routePrefix . '.deals.index') }}" class="dark-btn dark-btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('messages.common.back') }}
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="dark-card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-slate-100">{{ __('messages.deals.deal_details') }}</h2>
                        @php $stageBadge = $deal->stage === 'Won' ? 'dark-badge-emerald' : ($deal->stage === 'Lost' ? 'dark-badge-rose' : ($deal->stage === 'New' ? 'dark-badge-gray' : ($deal->stage === 'Contacted' ? 'dark-badge-cyan' : 'dark-badge-violet'))); @endphp
                        <span class="dark-badge {{ $stageBadge }}">
                            {{ $deal->stage }}
                        </span>
                    </div>

                    <dl class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-800/50 rounded-lg p-4">
                            <dt class="text-sm text-slate-500">{{ __('messages.deals.value') }}</dt>
                            <dd class="text-xl font-bold text-emerald-400">{{ $deal->currency }} {{ number_format($deal->value, 2) }}</dd>
                        </div>
                        <div class="bg-slate-800/50 rounded-lg p-4">
                            <dt class="text-sm text-slate-500">{{ __('messages.deals.probability') ?? 'Probability' }}</dt>
                            <dd class="text-xl font-bold text-slate-100">{{ $deal->probability }}%</dd>
                        </div>
                        <div class="bg-slate-800/50 rounded-lg p-4">
                            <dt class="text-sm text-slate-500">{{ __('messages.deals.close_date') }}</dt>
                            <dd class="text-slate-200">{{ $deal->expected_close_date?->format('M d, Y') ?? __('messages.common.no_data') }}</dd>
                        </div>
                        <div class="bg-slate-800/50 rounded-lg p-4">
                            <dt class="text-sm text-slate-500">{{ __('messages.deals.days_in_stage') ?? 'Days in Stage' }}</dt>
                            <dd class="text-slate-200">{{ $deal->daysInStage() }} {{ __('messages.deals.days') ?? 'days' }}</dd>
                        </div>
                        @if($deal->lost_reason)
                        <div class="col-span-2 bg-slate-800/50 rounded-lg p-4">
                            <dt class="text-sm text-slate-500">{{ __('messages.deals.lost_reason') ?? 'Lost Reason' }}</dt>
                            <dd class="text-slate-200">{{ $deal->lost_reason }}</dd>
                        </div>
                        @endif
                    </dl>

                    <div class="mt-4 pt-4 border-t border-[#2d3748] flex gap-2">
                        <a href="{{ route($routePrefix . '.deals.edit', $deal) }}" class="dark-btn dark-btn-primary">
                            <i class="fas fa-pencil"></i>
                            {{ __('messages.common.edit') }}
                        </a>
                        <form action="{{ route($routePrefix . '.deals.destroy', $deal) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dark-btn bg-rose-600 hover:bg-rose-500 text-white" onclick="return confirm('{{ __('messages.messages.delete_confirm') }}')">
                                <i class="fas fa-trash"></i>
                                {{ __('messages.common.delete') }}
                            </button>
                        </form>
                    </div>
                </div>
 <x-ai-email-generator 
                            :client-name="$deal->contact->name" 
                            :client-email="$deal->contact->email" 
                        />
                @if($deal->contact)
                <div class="dark-card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-slate-100">{{ __('messages.deals.contact') }}</h2>
                      
                    </div>
                     
                    <div class="flex items-center gap-4">
                        <div class="dark-avatar">
                            {{ strtoupper(substr($deal->contact->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-slate-200">{{ $deal->contact->name }}</p>
                            <p class="text-sm text-slate-500">{{ $deal->contact->company ?? __('messages.contacts.no_company') ?? '-' }}</p>
                            <p class="text-sm text-slate-500">{{ $deal->contact->email }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="space-y-6">
                <div class="dark-card p-6">
                    <h2 class="text-lg font-semibold text-slate-100 mb-4">{{ __('messages.deals.assigned_to') }}</h2>
                    <div class="flex items-center gap-3">
                        <div class="dark-avatar">
                            {{ strtoupper(substr($deal->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-slate-200">{{ $deal->user->name }}</p>
                            <p class="text-xs text-slate-500">{{ $deal->user->email }}</p>
                        </div>
                    </div>
                </div>

                <div class="dark-card p-6">
                    <h2 class="text-lg font-semibold text-slate-100 mb-4">{{ __('messages.deals.pipeline') }}</h2>
                    <p class="text-sm text-slate-500 mb-3">{{ __('messages.deals.expected_value') ?? 'Expected Value' }}: <span class="font-semibold text-emerald-400">{{ $deal->currency }} {{ number_format($deal->value * $deal->probability / 100, 2) }}</span></p>
                    
                    <div class="space-y-2">
                        @foreach(['New', 'Contacted', 'Qualified', 'Proposal', 'Negotiation', 'Won', 'Lost'] as $stage)
                            <div class="flex items-center justify-between p-2 rounded {{ $deal->stage === $stage ? 'bg-cyan-500/10 border border-cyan-500/30' : '' }}">
                                <span class="text-sm {{ $deal->stage === $stage ? 'text-cyan-400 font-medium' : 'text-slate-400' }}">{{ $stage }}</span>
                                @if($deal->stage === $stage)
                                    <span class="dark-badge dark-badge-cyan text-xs">{{ __('messages.common.current') ?? 'Current' }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
