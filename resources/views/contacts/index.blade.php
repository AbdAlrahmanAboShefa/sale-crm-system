@extends('layouts.app')
@section('content')
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg flex items-center">
        <i class="fas fa-check-circle {{ app()->getLocale() === 'ar' ? 'ms-2 me-0' : 'me-2' }}"></i>
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex-1 max-w-md">
                    <form method="GET" action="{{ route($routePrefix . '.contacts.index') }}" class="relative">
                        <i class="fas fa-search absolute {{ app()->getLocale() === 'ar' ? 'right-3' : 'left-3' }} top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.common.search_placeholder') }}"
                            class="w-full {{ app()->getLocale() === 'ar' ? 'pr-10 pl-4' : 'pl-10 pr-4' }} py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </form>
                </div>
                <div class="flex items-center gap-3">
                    <form method="GET" action="{{ route($routePrefix . '.contacts.index') }}" class="flex items-center gap-2">
                        <select name="status" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">{{ __('messages.common.all') }} {{ __('messages.common.status') }}</option>
                            @foreach(['Lead', 'Prospect', 'Client', 'Lost', 'Inactive'] as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                        <select name="source" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">{{ __('messages.common.all') }} {{ __('messages.contacts.source') }}</option>
                            @foreach(['website', 'referral', 'social', 'cold'] as $source)
                            <option value="{{ $source }}" {{ request('source') == $source ? 'selected' : '' }}>{{ ucfirst(__("messages.contacts.sources." . $source)) }}</option>
                            @endforeach
                        </select>
                    </form>
                    <a href="{{ route($routePrefix . '.contacts.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 rounded-lg text-sm font-medium text-white hover:bg-blue-700 shadow-sm transition-colors">
                        <i class="fas fa-plus {{ app()->getLocale() === 'ar' ? 'ms-2 me-0' : 'me-2' }}"></i>
                        {{ __('messages.contacts.new_contact') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">
                        <th class="px-6 py-4">{{ __('messages.contacts.name') }}</th>
                        <th class="px-6 py-4">{{ __('messages.contacts.email') }}</th>
                        <th class="px-6 py-4">{{ __('messages.contacts.phone') }}</th>
                        <th class="px-6 py-4">{{ __('messages.contacts.company') }}</th>
                        <th class="px-6 py-4">{{ __('messages.common.status') }}</th>
                        <th class="px-6 py-4">{{ __('messages.contacts.source') }}</th>
                        <th class="px-6 py-4">{{ __('messages.common.created_at') }}</th>
                        <th class="px-6 py-4">{{ __('messages.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($contacts as $contact)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                    {{ strtoupper(substr($contact->name, 0, 1)) }}
                                </div>
                                <span class="font-medium text-gray-900">{{ $contact->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $contact->email }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $contact->phone ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $contact->company ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $contact->status === 'Lead' ? 'bg-blue-100 text-blue-700' : ($contact->status === 'Prospect' ? 'bg-yellow-100 text-yellow-700' : ($contact->status === 'Client' ? 'bg-emerald-100 text-emerald-700' : ($contact->status === 'Lost' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700'))) }}">
                                {{ $contact->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ __("messages.contacts.sources." . $contact->source) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $contact->created_at->format('M j, Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route($routePrefix . '.contacts.show', $contact) }}" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="{{ __('messages.common.view') }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route($routePrefix . '.contacts.edit', $contact) }}" class="p-2 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="{{ __('messages.common.edit') }}">
                                    <i class="fas fa-pencil"></i>
                                </a>
                                <form action="{{ route($routePrefix . '.contacts.destroy', $contact) }}" method="POST" x-data="{ showModal: false }" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" @click="showModal = true" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="{{ __('messages.common.delete') }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <div x-show="showModal" x-transition class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                                        <div class="flex items-center justify-center min-h-screen px-4">
                                            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" @click="showModal = false"></div>
                                            <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6 z-10">
                                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('messages.contacts.delete_contact') ?? __('messages.common.confirm_delete') }}</h3>
                                                <p class="text-gray-600 mb-6">{{ __('messages.common.delete_warning') }}</p>
                                                <div class="flex justify-end gap-3">
                                                    <button type="button" @click="showModal = false" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">{{ __('messages.common.cancel') }}</button>
                                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded-lg">{{ __('messages.common.delete') }}</button>
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
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-users text-gray-300 text-5xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">{{ __('messages.contacts.no_contacts') }}</h3>
                                <p class="text-gray-500 mb-4">{{ __('messages.contacts.get_started') }}</p>
                                <a href="{{ route($routePrefix . '.contacts.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 rounded-lg text-sm font-medium text-white hover:bg-blue-700">
                                    <i class="fas fa-plus {{ app()->getLocale() === 'ar' ? 'ms-2 me-0' : 'me-2' }}"></i>
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
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $contacts->withQueryString()->links() }}
        </div>
        @endif
    </div>
@endsection
