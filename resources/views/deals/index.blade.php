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
                    <form method="GET" action="{{ route($routePrefix . '.deals.index') }}" class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-500"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.common.search') }}" class="dark-input w-72 !ps-10">
                    </form>
                    <form method="GET" action="{{ route($routePrefix . '.deals.index') }}">
                        <select name="stage" onchange="this.form.submit()" class="dark-select text-sm">
                            <option value="">{{ __('messages.common.all') }} {{ __('messages.deals.stage') }}</option>
                            @foreach(['New', 'Contacted', 'Qualified', 'Proposal', 'Negotiation', 'Won', 'Lost'] as $stage)
                            <option value="{{ $stage }}" {{ request('stage') == $stage ? 'selected' : '' }}>{{ __("messages.deals.stages." . strtolower($stage)) }}</option>
                            @endforeach
                        </select>
                        @if(auth()->user()->hasRole(['Admin', 'Manager']))
                        <select name="user_id" onchange="this.form.submit()" class="dark-select text-sm">
                            <option value="">{{ __('messages.common.all') }}</option>
                            @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                        @endif
                    </form>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route($routePrefix . '.deals.kanban') }}" class="dark-btn dark-btn-secondary">
                        <i class="fas fa-columns"></i>
                        {{ __('messages.deals.kanban_board') }}
                    </a>
                    <a href="{{ route($routePrefix . '.deals.create') }}" class="dark-btn dark-btn-primary">
                        <i class="fas fa-plus"></i>
                        {{ __('messages.deals.new_deal') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="dark-table">
                <thead>
                    <tr>
                        <th class="!border-t-transparent !bg-slate-800/50">{{ __('messages.deals.deal_title') }}</th>
                        <th class="!border-t-transparent !bg-slate-800/50">{{ __('messages.deals.contact') }}</th>
                        <th class="!border-t-transparent !bg-slate-800/50">{{ __('messages.deals.value') }}</th>
                        <th class="!border-t-transparent !bg-slate-800/50">{{ __('messages.deals.stage') }}</th>
                        <th class="!border-t-transparent !bg-slate-800/50">{{ __('messages.deals.probability') }}</th>
                        <th class="!border-t-transparent !bg-slate-800/50">{{ __('messages.deals.close_date') }}</th>
                        <th class="!border-t-transparent !bg-slate-800/50">{{ __('messages.deals.assigned_to') }}</th>
                        <th class="!border-t-transparent !bg-slate-800/50">{{ __('messages.common.days') }}</th>
                        <th class="!border-t-transparent !bg-slate-800/50">{{ __('messages.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deals as $deal)
                    <tr class="{{ $deal->stage === 'Won' ? 'bg-emerald-500/5' : ($deal->stage === 'Lost' ? 'bg-rose-500/5' : '') }}">
                        <td>
                            <a href="{{ route($routePrefix . '.deals.show', $deal) }}" class="text-sm font-semibold text-slate-200 hover:text-cyan-400 transition-colors">
                                {{ $deal->title }}
                            </a>
                        </td>
                        <td>
                            @if($deal->contact)
                            <a href="{{ route($routePrefix . '.contacts.show', $deal->contact) }}" class="text-sm text-cyan-400 hover:text-cyan-300 hover:underline">
                                {{ $deal->contact->name }}
                            </a>
                            @else
                            <span class="text-slate-600">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-sm font-bold text-emerald-400">${{ number_format($deal->value, 0) }}</span>
                        </td>
                        <td>
                            @php
                            $stageBadge = [
                                'New' => 'dark-badge-gray',
                                'Contacted' => 'dark-badge-cyan',
                                'Qualified' => 'dark-badge-violet',
                                'Proposal' => 'dark-badge-amber',
                                'Negotiation' => 'dark-badge-rose',
                                'Won' => 'dark-badge-emerald',
                                'Lost' => 'dark-badge-gray',
                            ];
                            @endphp
                            <span class="dark-badge {{ $stageBadge[$deal->stage] ?? 'dark-badge-gray' }}">
                                {{ __("messages.deals.stages." . strtolower($deal->stage)) }}
                            </span>
                        </td>
                        <td>
                            <span class="text-sm text-slate-400">{{ $deal->probability }}%</span>
                        </td>
                        <td>
                            <span class="text-sm text-slate-400">{{ $deal->expected_close_date?->format('M j, Y') ?? '-' }}</span>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="dark-avatar dark-avatar-sm">
                                    {{ strtoupper(substr($deal->user?->name ?? 'U', 0, 1)) }}
                                </div>
                                <span class="text-sm text-slate-400">{{ $deal->user?->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="{{ $deal->daysInStage() > 14 ? 'text-rose-400 font-semibold' : ($deal->daysInStage() > 7 ? 'text-amber-400' : 'text-slate-400') }}">
                                {{ $deal->daysInStage() }}d
                            </span>
                        </td>
                        <td>
                            <div class="flex items-center gap-1">
                                <a href="{{ route($routePrefix . '.deals.show', $deal) }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-cyan-400 hover:bg-cyan-500/10 transition-all" title="{{ __('messages.common.view') }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route($routePrefix . '.deals.edit', $deal) }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-emerald-400 hover:bg-emerald-500/10 transition-all" title="{{ __('messages.common.edit') }}">
                                    <i class="fas fa-pencil"></i>
                                </a>
                                <form action="{{ route($routePrefix . '.deals.destroy', $deal) }}" method="POST" x-data="{ showModal: false }" class="inline">
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
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-16">
                            <div class="dark-empty-state">
                                <div class="dark-empty-icon">
                                    <i class="fas fa-hand-holding-dollar"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-slate-300 mb-2">{{ __('messages.deals.no_deals') ?? __('messages.common.no_data') }}</h3>
                                <p class="text-slate-500 mb-6">{{ __('messages.deals.get_started') ?? __('messages.contacts.get_started') }}</p>
                                <a href="{{ route($routePrefix . '.deals.create') }}" class="dark-btn dark-btn-primary">
                                    <i class="fas fa-plus"></i>
                                    {{ __('messages.deals.new_deal') }}
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($deals->hasPages())
        <div class="px-6 py-4 border-t border-[#2d3748]">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500">
                    Showing {{ $deals->firstItem() ?? 0 }} to {{ $deals->lastItem() ?? 0 }} of {{ $deals->total() }} results
                </p>
                <div class="dark-pagination">
                    @if($deals->onFirstPage())
                        <span class="opacity-50"><i class="fas fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $deals->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
                    @endif
                    
                    @foreach($deals->getUrlRange(1, $deals->lastPage()) as $page => $url)
                        @if($page == $deals->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                    
                    @if($deals->hasMorePages())
                        <a href="{{ $deals->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
                    @else
                        <span class="opacity-50"><i class="fas fa-chevron-right"></i></span>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection
