@extends('layouts.app')
@section('content')
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex flex-col items-center mb-6">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-3xl font-bold mb-4">
                        {{ strtoupper(substr($contact->name, 0, 1)) }}
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">{{ $contact->name }}</h3>
                    @php
                    $statusColors = ['Lead' => 'bg-blue-100 text-blue-700', 'Prospect' => 'bg-yellow-100 text-yellow-700', 'Client' => 'bg-emerald-100 text-emerald-700', 'Lost' => 'bg-red-100 text-red-700', 'Inactive' => 'bg-gray-100 text-gray-700'];
                    @endphp
                    <span class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$contact->status] ?? 'bg-gray-100 text-gray-700' }}">
                        {{ $contact->status }}
                    </span>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-envelope text-gray-400 w-5"></i>
                        <div>
                            <p class="text-xs text-gray-500">Email</p>
                            <a href="mailto:{{ $contact->email }}" class="text-sm text-blue-600 hover:underline">{{ $contact->email }}</a>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-phone text-gray-400 w-5"></i>
                        <div>
                            <p class="text-xs text-gray-500">Phone</p>
                            <p class="text-sm text-gray-900">{{ $contact->phone ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-building text-gray-400 w-5"></i>
                        <div>
                            <p class="text-xs text-gray-500">Company</p>
                            <p class="text-sm text-gray-900">{{ $contact->company ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-globe text-gray-400 w-5"></i>
                        <div>
                            <p class="text-xs text-gray-500">Website</p>
                            @if($contact->website)
                            <a href="{{ $contact->website }}" target="_blank" class="text-sm text-blue-600 hover:underline">{{ Str::limit($contact->website, 25) }}</a>
                            @else
                            <p class="text-sm text-gray-400">-</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-tag text-gray-400 w-5"></i>
                        <div>
                            <p class="text-xs text-gray-500">Source</p>
                            <p class="text-sm text-gray-900 capitalize">{{ $contact->source }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-user text-gray-400 w-5"></i>
                        <div>
                            <p class="text-xs text-gray-500">Owner</p>
                            <p class="text-sm text-gray-900">{{ $contact->user->name ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                @if($contact->tags && count($contact->tags) > 0)
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Tags</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($contact->tags as $tag)
                        <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="mt-6 pt-6 border-t border-gray-100 flex gap-2">
                    <a href="{{ route($routePrefix . '.contacts.edit', $contact) }}" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm">
                        <i class="fas fa-pencil mr-2"></i>Edit
                    </a>
                    <form action="{{ route($routePrefix . '.contacts.destroy', $contact) }}" method="POST" x-data="{ showModal: false }" class="flex-1">
                        @csrf @method('DELETE')
                        <button type="button" @click="showModal = true" class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 font-medium text-sm">
                            <i class="fas fa-trash mr-2"></i>Delete
                        </button>
                        <div x-show="showModal" x-transition class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                            <div class="flex items-center justify-center min-h-screen px-4">
                                <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" @click="showModal = false"></div>
                                <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6 z-10">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete Contact</h3>
                                    <p class="text-gray-600 mb-6">Are you sure you want to delete this contact? This action cannot be undone.</p>
                                    <div class="flex justify-end gap-3">
                                        <button type="button" @click="showModal = false" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Cancel</button>
                                        <button type="submit" class="px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded-lg">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Activity Timeline</h3>
                    <a href="{{ route($routePrefix . '.activities.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                        <i class="fas fa-plus mr-2"></i>Log Activity
                    </a>
                </div>

                @if($contact->activities->count() > 0)
                <div class="space-y-4">
                    @foreach($contact->activities as $activity)
                    <div class="flex gap-4 p-4 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0
                            @if($activity->type === 'Call') bg-blue-100 text-blue-600
                            @elseif($activity->type === 'Meeting') bg-emerald-100 text-emerald-600
                            @elseif($activity->type === 'Email') bg-purple-100 text-purple-600
                            @elseif($activity->type === 'Demo') bg-orange-100 text-orange-600
                            @else bg-gray-100 text-gray-600 @endif">
                            @if($activity->type === 'Call')<i class="fas fa-phone text-sm"></i>
                            @elseif($activity->type === 'Meeting')<i class="fas fa-calendar text-sm"></i>
                            @elseif($activity->type === 'Email')<i class="fas fa-envelope text-sm"></i>
                            @elseif($activity->type === 'Demo')<i class="fas fa-presentation text-sm"></i>
                            @else<i class="fas fa-clipboard-list text-sm"></i>@endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-medium text-gray-900">{{ $activity->type }}</span>
                                @if($activity->is_done)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-xs">Done</span>
                                @elseif($activity->isOverdue())
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-red-100 text-red-700 text-xs">Overdue</span>
                                @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700 text-xs">Pending</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 mb-2">{{ $activity->note ?? 'No notes' }}</p>
                            <div class="flex items-center gap-4 text-xs text-gray-500">
                                @if($activity->deal)
                                <span><i class="fas fa-hand-holding-dollar mr-1"></i>{{ $activity->deal->title }}</span>
                                @endif
                                <span><i class="fas fa-clock mr-1"></i>{{ $activity->due_date ? $activity->due_date->format('M j, Y g:i A') : 'No due date' }}</span>
                                <span><i class="fas fa-user mr-1"></i>{{ $activity->user->name ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <i class="fas fa-calendar-times text-gray-300 text-5xl mb-4"></i>
                    <h4 class="text-lg font-medium text-gray-900 mb-1">No activities yet</h4>
                    <p class="text-gray-500 mb-4">Log your first activity with this contact.</p>
                    <a href="{{ route($routePrefix . '.activities.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                        <i class="fas fa-plus mr-2"></i>Log Activity
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route($routePrefix . '.contacts.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-2"></i>Back to Contacts
        </a>
    </div>
@endsection
