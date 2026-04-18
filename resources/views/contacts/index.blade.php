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
                <div class="flex-1 max-w-md">
                    <form method="GET" action="{{ route($routePrefix . '.contacts.index') }}" class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-500"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.common.search_placeholder') }}" class="dark-input w-full !ps-10">
                    </form>
                </div>
                <div class="flex items-center gap-3">
                    <form method="GET" action="{{ route($routePrefix . '.contacts.index') }}" class="flex items-center gap-2">
                        <select name="status" onchange="this.form.submit()" class="dark-select text-sm">
                            <option value="">{{ __('messages.common.all') }} {{ __('messages.common.status') }}</option>
                            @foreach(['Lead', 'Prospect', 'Client', 'Lost', 'Inactive'] as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                        <select name="source" onchange="this.form.submit()" class="dark-select text-sm">
                            <option value="">{{ __('messages.common.all') }} {{ __('messages.contacts.source') }}</option>
                            @foreach(['website', 'referral', 'social', 'cold'] as $source)
                            <option value="{{ $source }}" {{ request('source') == $source ? 'selected' : '' }}>{{ ucfirst(__("messages.contacts.sources." . $source)) }}</option>
                            @endforeach
                        </select>
                    </form>
                    <a href="{{ route($routePrefix . '.contacts.create') }}" class="dark-btn dark-btn-primary">
                        <i class="fas fa-plus"></i>
                        {{ __('messages.contacts.new_contact') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="dark-table">
                <thead>
                    <tr>
                        <th class="!border-t-transparent !bg-slate-800/50">{{ __('messages.contacts.name') }}</th>
                        <th class="!border-t-transparent !bg-slate-800/50">{{ __('messages.contacts.email') }}</th>
                        <th class="!border-t-transparent !bg-slate-800/50">{{ __('messages.contacts.phone') }}</th>
                        <th class="!border-t-transparent !bg-slate-800/50">{{ __('messages.contacts.company') }}</th>
                        <th class="!border-t-transparent !bg-slate-800/50">{{ __('messages.common.status') }}</th>
                        <th class="!border-t-transparent !bg-slate-800/50">{{ __('messages.contacts.source') }}</th>
                        <th class="!border-t-transparent !bg-slate-800/50">{{ __('messages.common.created_at') }}</th>
                        <th class="!border-t-transparent !bg-slate-800/50">{{ __('messages.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="dark-avatar">
                                    {{ strtoupper(substr($contact->name, 0, 1)) }}
                                </div>
                                <span class="font-semibold text-slate-200">{{ $contact->name }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="text-sm text-slate-400">{{ $contact->email }}</span>
                        </td>
                        <td>
                            <span class="text-sm text-slate-400">{{ $contact->phone ?? '-' }}</span>
                        </td>
                        <td>
                            <span class="text-sm text-slate-400">{{ $contact->company ?? '-' }}</span>
                        </td>
                        <td>
                            @php
                            $statusBadge = [
                                'Lead' => 'dark-badge-cyan',
                                'Prospect' => 'dark-badge-amber',
                                'Client' => 'dark-badge-emerald',
                                'Lost' => 'dark-badge-rose',
                                'Inactive' => 'dark-badge-gray',
                            ];
                            @endphp
                            <span class="dark-badge {{ $statusBadge[$contact->status] ?? 'dark-badge-gray' }}">
                                {{ $contact->status }}
                            </span>
                        </td>
                        <td>
                            <span class="text-sm text-slate-400">{{ __("messages.contacts.sources." . $contact->source) }}</span>
                        </td>
                        <td>
                            <span class="text-sm text-slate-500">{{ $contact->created_at->format('M j, Y') }}</span>
                        </td>
                        <td>
                            <div class="flex items-center gap-1">
                                <a href="{{ route($routePrefix . '.contacts.show', $contact) }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-cyan-400 hover:bg-cyan-500/10 transition-all" title="{{ __('messages.common.view') }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route($routePrefix . '.contacts.edit', $contact) }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-emerald-400 hover:bg-emerald-500/10 transition-all" title="{{ __('messages.common.edit') }}">
                                    <i class="fas fa-pencil"></i>
                                </a>
                                <form action="{{ route($routePrefix . '.contacts.destroy', $contact) }}" method="POST" x-data="{ showModal: false }" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" @click="showModal = true" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition-all" title="{{ __('messages.common.delete') }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <div x-show="showModal" x-transition class="dark-modal-backdrop flex items-center justify-center p-4" style="display: none;">
                                        <div class="dark-modal max-w-md w-full p-6" @click.stop>
                                            <div class="text-center">
                                                <div class="w-16 h-16 rounded-2xl bg-rose-500/20 flex items-center justify-center mx-auto mb-4">
                                                    <i class="fas fa-exclamation-triangle text-rose-400 text-2xl"></i>
                                                </div>
                                                <h3 class="text-xl font-bold text-slate-100 mb-2">{{ __('messages.contacts.delete_contact') ?? __('messages.common.confirm_delete') }}</h3>
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
                        <td colspan="8" class="py-16">
                            <div class="dark-empty-state">
                                <div class="dark-empty-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-slate-300 mb-2">{{ __('messages.contacts.no_contacts') }}</h3>
                                <p class="text-slate-500 mb-6">{{ __('messages.contacts.get_started') }}</p>
                                <a href="{{ route($routePrefix . '.contacts.create') }}" class="dark-btn dark-btn-primary">
                                    <i class="fas fa-plus"></i>
                                    {{ __('messages.contacts.new_contact') }}
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($contacts->hasPages())
        <div class="px-6 py-4 border-t border-[#2d3748]">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500">
                    Showing {{ $contacts->firstItem() ?? 0 }} to {{ $contacts->lastItem() ?? 0 }} of {{ $contacts->total() }} results
                </p>
                <div class="dark-pagination">
                    @if($contacts->onFirstPage())
                        <span class="opacity-50"><i class="fas fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $contacts->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
                    @endif
                    
                    @foreach($contacts->getUrlRange(1, $contacts->lastPage()) as $page => $url)
                        @if($page == $contacts->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                    
                    @if($contacts->hasMorePages())
                        <a href="{{ $contacts->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
                    @else
                        <span class="opacity-50"><i class="fas fa-chevron-right"></i></span>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection
