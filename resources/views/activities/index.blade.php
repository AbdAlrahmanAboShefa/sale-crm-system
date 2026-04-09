@extends('layouts.app')
@section('content')
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg flex items-center"><i class="fas fa-check-circle {{ app()->getLocale() === 'ar' ? 'ms-2 me-0' : 'me-2' }}"></i>{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-2">
                    <i class="fas fa-filter text-gray-400"></i>
                    <form method="GET" class="flex items-center gap-2 flex-wrap">
                        <select name="type" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                            <option value="">{{ __('messages.common.all') }} {{ __('messages.activities.types') }}</option>
                            @foreach(['Call', 'Meeting', 'Email', 'Task', 'Demo'] as $type)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                        <select name="is_done" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                            <option value="">{{ __('messages.common.all') }} {{ __('messages.common.status') }}</option>
                            <option value="0" {{ request('is_done') === '0' ? 'selected' : '' }}>{{ __('messages.activities.pending') }}</option>
                            <option value="1" {{ request('is_done') == '1' ? 'selected' : '' }}>{{ __('messages.activities.completed') }}</option>
                        </select>
                        <input type="date" name="due_date" value="{{ request('due_date') }}" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                    </form>
                </div>
                <a href="{{ route($routePrefix . '.activities.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 rounded-lg text-sm font-medium text-white hover:bg-blue-700 shadow-sm transition-colors">
                    <i class="fas fa-plus {{ app()->getLocale() === 'ar' ? 'ms-2 me-0' : 'me-2' }}"></i>{{ __('messages.activities.log_activity') }}
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">
                        <th class="px-6 py-4">{{ __('messages.activities.type') }}</th>
                        <th class="px-6 py-4">{{ __('messages.activities.notes') }}</th>
                        <th class="px-6 py-4">{{ __('messages.contacts.title') }}</th>
                        <th class="px-6 py-4">{{ __('messages.deals.deal') }}</th>
                        <th class="px-6 py-4">{{ __('messages.activities.due_date') }}</th>
                        <th class="px-6 py-4">{{ __('messages.common.status') }}</th>
                        <th class="px-6 py-4">{{ __('messages.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($activities as $activity)
                    <tr class="{{ $activity->isOverdue() ? 'border-l-4 border-red-500 bg-red-50' : '' }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center
                                    @if($activity->type === 'Call') bg-blue-100 text-blue-600
                                    @elseif($activity->type === 'Meeting') bg-emerald-100 text-emerald-600
                                    @elseif($activity->type === 'Email') bg-purple-100 text-purple-600
                                    @elseif($activity->type === 'Demo') bg-orange-100 text-orange-600
                                    @else bg-gray-100 text-gray-600 @endif">
                                    @if($activity->type === 'Call')<i class="fas fa-phone text-xs"></i>
                                    @elseif($activity->type === 'Meeting')<i class="fas fa-calendar text-xs"></i>
                                    @elseif($activity->type === 'Email')<i class="fas fa-envelope text-xs"></i>
                                    @elseif($activity->type === 'Demo')<i class="fas fa-presentation text-xs"></i>
                                    @else<i class="fas fa-check text-xs"></i>@endif
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $activity->type }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-600 max-w-xs truncate">{{ $activity->note ?? __('messages.activities.no_notes') }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $activity->contact?->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $activity->deal?->title ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($activity->due_date)
                            <span class="text-sm {{ $activity->isOverdue() ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                                {{ $activity->due_date->format('M j, Y g:i A') }}
                            </span>
                            @else
                            <span class="text-sm text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($activity->is_done)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">{{ __('messages.activities.completed') }}</span>
                            @elseif($activity->isOverdue())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">{{ __('messages.activities.overdue') }}</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">{{ __('messages.activities.pending') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                @if(!$activity->is_done)
                                <button onclick="markDone({{ $activity->id }})" class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="{{ __('messages.activities.mark_done') }}">
                                    <i class="fas fa-check"></i>
                                </button>
                                @endif
                                <a href="{{ route($routePrefix . '.activities.edit', $activity) }}" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="{{ __('messages.common.edit') }}">
                                    <i class="fas fa-pencil"></i>
                                </a>
                                <form action="{{ route($routePrefix . '.activities.destroy', $activity) }}" method="POST" x-data="{ showModal: false }" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="button" @click="showModal = true" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="{{ __('messages.common.delete') }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <div x-show="showModal" x-transition class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                                        <div class="flex items-center justify-center min-h-screen px-4">
                                            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" @click="showModal = false"></div>
                                            <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6 z-10">
                                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('messages.activities.delete_activity') ?? 'Delete Activity' }}</h3>
                                                <p class="text-gray-600 mb-6">{{ __('messages.messages.delete_confirm') }}</p>
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
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-calendar-times text-gray-300 text-5xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">{{ __('messages.activities.no_activities') }}</h3>
                                <p class="text-gray-500 mb-4">{{ __('messages.activities.log_first') }}</p>
                                <a href="{{ route($routePrefix . '.activities.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 rounded-lg text-sm font-medium text-white hover:bg-blue-700">
                                    <i class="fas fa-plus {{ app()->getLocale() === 'ar' ? 'ms-2 me-0' : 'me-2' }}"></i>{{ __('messages.activities.log_activity') }}
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($activities->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $activities->withQueryString()->links() }}
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
