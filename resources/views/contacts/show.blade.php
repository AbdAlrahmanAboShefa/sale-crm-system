<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $contact->name }} - Sales CRM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <aside class="w-64 bg-[#1e293b] text-white flex-shrink-0 flex flex-col">
            <div class="p-5 border-b border-slate-700">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center"><i class="fas fa-chart-line text-white text-lg"></i></div>
                    <div><h1 class="text-lg font-bold">Sales CRM</h1><p class="text-xs text-slate-400">@hasrole('Admin') Administrator @endhasrole @hasrole('Manager') Manager @endhasrole @hasrole('Agent') Sales Agent @endhasrole</p></div>
                </div>
            </div>
            <nav class="mt-4 flex-1">
                @role('Admin')
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}"><i class="fas fa-home w-5 mr-3"></i>Dashboard</a>
                <a href="{{ route('admin.contacts.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('admin.contacts.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}"><i class="fas fa-users w-5 mr-3"></i>Contacts</a>
                <a href="{{ route('admin.deals.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('admin.deals.*') && !request()->routeIs('admin.deals.kanban') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}"><i class="fas fa-hand-holding-dollar w-5 mr-3"></i>Deals</a>
                <a href="{{ route('admin.deals.kanban') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('admin.deals.kanban') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}"><i class="fas fa-columns w-5 mr-3"></i>Kanban</a>
                <a href="{{ route('admin.activities.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('admin.activities.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}"><i class="fas fa-calendar-check w-5 mr-3"></i>Activities</a>
                @endrole
                @role('Manager')
                <a href="{{ route('manager.dashboard') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('manager.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}"><i class="fas fa-home w-5 mr-3"></i>Dashboard</a>
                <a href="{{ route('manager.contacts.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('manager.contacts.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}"><i class="fas fa-users w-5 mr-3"></i>Contacts</a>
                <a href="{{ route('manager.deals.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('manager.deals.*') && !request()->routeIs('manager.deals.kanban') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}"><i class="fas fa-hand-holding-dollar w-5 mr-3"></i>Deals</a>
                <a href="{{ route('manager.deals.kanban') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('manager.deals.kanban') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}"><i class="fas fa-columns w-5 mr-3"></i>Kanban</a>
                <a href="{{ route('manager.activities.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('manager.activities.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}"><i class="fas fa-calendar-check w-5 mr-3"></i>Activities</a>
                @endrole
                @role('Agent')
                <a href="{{ route('agent.dashboard') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('agent.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}"><i class="fas fa-home w-5 mr-3"></i>Dashboard</a>
                <a href="{{ route('agent.contacts.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('agent.contacts.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}"><i class="fas fa-users w-5 mr-3"></i>Contacts</a>
                <a href="{{ route('agent.deals.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('agent.deals.*') && !request()->routeIs('agent.deals.kanban') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}"><i class="fas fa-hand-holding-dollar w-5 mr-3"></i>Deals</a>
                <a href="{{ route('agent.deals.kanban') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('agent.deals.kanban') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}"><i class="fas fa-columns w-5 mr-3"></i>Kanban</a>
                <a href="{{ route('agent.activities.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('agent.activities.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}"><i class="fas fa-calendar-check w-5 mr-3"></i>Activities</a>
                @endrole
            </nav>
            <div class="p-4 border-t border-slate-700"><form action="{{ route('logout') }}" method="POST">@csrf<button type="submit" class="flex items-center w-full px-4 py-2 text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors"><i class="fas fa-sign-out-alt w-5 mr-3"></i>Logout</button></form></div>
        </aside>

        <div class="flex-1 flex flex-col">
            <header class="bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                <div><h2 class="text-xl font-semibold text-gray-800">Contact Details</h2></div>
                <div class="flex items-center gap-4">
                    <x-notification-bell />
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                        <div><p class="text-sm font-medium text-gray-800">{{ auth()->user()->name }}</p></div>
                    </div>
                </div>
            </header>

            <main class="p-6 bg-gray-50">
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
                                <a href="{{ route('agent.contacts.edit', $contact) }}" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm">
                                    <i class="fas fa-pencil mr-2"></i>Edit
                                </a>
                                <form action="{{ route('agent.contacts.destroy', $contact) }}" method="POST" x-data="{ showModal: false }" class="flex-1">
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
                                <a href="{{ route('agent.activities.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
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
                                <a href="{{ route('agent.activities.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                                    <i class="fas fa-plus mr-2"></i>Log Activity
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('agent.contacts.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Contacts
                    </a>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
