<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contacts - Sales CRM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <aside class="w-64 bg-[#1e293b] text-white flex-shrink-0 flex flex-col">
            <div class="p-5 border-b border-slate-700">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-line text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold">Sales CRM</h1>
                        <p class="text-xs text-slate-400">
                            @hasrole('Admin') Administrator @endhasrole
                            @hasrole('Manager') Manager @endhasrole
                            @hasrole('Agent') Sales Agent @endhasrole
                        </p>
                    </div>
                </div>
            </div>
            <nav class="mt-4 flex-1">
                @role('Admin')
                <div class="px-3 mb-2">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Management</span>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-home w-5 mr-3"></i>
                    Dashboard
                </a>
                <a href="{{ route('admin.contacts.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('admin.contacts.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-users w-5 mr-3"></i>
                    Contacts
                </a>
                <a href="{{ route('admin.deals.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('admin.deals.*') && !request()->routeIs('admin.deals.kanban') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-hand-holding-dollar w-5 mr-3"></i>
                    Deals
                </a>
                <a href="{{ route('admin.deals.kanban') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('admin.deals.kanban') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-columns w-5 mr-3"></i>
                    Kanban Board
                </a>
                <a href="{{ route('admin.activities.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('admin.activities.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-calendar-check w-5 mr-3"></i>
                    Activities
                </a>
                @endrole

                @role('Manager')
                <div class="px-3 mb-2">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Management</span>
                </div>
                <a href="{{ route('manager.dashboard') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('manager.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-home w-5 mr-3"></i>
                    Dashboard
                </a>
                <a href="{{ route('manager.contacts.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('manager.contacts.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-users w-5 mr-3"></i>
                    Contacts
                </a>
                <a href="{{ route('manager.deals.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('manager.deals.*') && !request()->routeIs('manager.deals.kanban') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-hand-holding-dollar w-5 mr-3"></i>
                    Deals
                </a>
                <a href="{{ route('manager.deals.kanban') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('manager.deals.kanban') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-columns w-5 mr-3"></i>
                    Kanban Board
                </a>
                <a href="{{ route('manager.activities.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('manager.activities.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-calendar-check w-5 mr-3"></i>
                    Activities
                </a>
                @endrole

                @role('Agent')
                <div class="px-3 mb-2">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Workspace</span>
                </div>
                <a href="{{ route('agent.dashboard') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('agent.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-home w-5 mr-3"></i>
                    Dashboard
                </a>
                <a href="{{ route('agent.contacts.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('agent.contacts.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-users w-5 mr-3"></i>
                    Contacts
                </a>
                <a href="{{ route('agent.deals.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('agent.deals.*') && !request()->routeIs('agent.deals.kanban') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-hand-holding-dollar w-5 mr-3"></i>
                    Deals
                </a>
                <a href="{{ route('agent.deals.kanban') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('agent.deals.kanban') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-columns w-5 mr-3"></i>
                    Kanban Board
                </a>
                <a href="{{ route('agent.activities.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('agent.activities.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-calendar-check w-5 mr-3"></i>
                    Activities
                </a>
                @endrole
            </nav>
            <div class="p-4 border-t border-slate-700">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-2 text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
                        <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col">
            <header class="bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Contacts</h2>
                    <p class="text-sm text-gray-500">{{ $contacts->total() }} total contacts</p>
                </div>
                <div class="flex items-center gap-4">
                    <x-notification-bell />
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                        </div>
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

                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <div class="flex-1 max-w-md">
                                <form method="GET" action="{{ route($routePrefix . '.contacts.index') }}" class="relative">
                                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, company..."
                                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </form>
                            </div>
                            <div class="flex items-center gap-3">
                                <form method="GET" action="{{ route($routePrefix . '.contacts.index') }}" class="flex items-center gap-2">
                                    <select name="status" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">All Status</option>
                                        @foreach(['Lead', 'Prospect', 'Client', 'Lost', 'Inactive'] as $status)
                                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                    <select name="source" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">All Sources</option>
                                        @foreach(['website', 'referral', 'social', 'cold'] as $source)
                                        <option value="{{ $source }}" {{ request('source') == $source ? 'selected' : '' }}>{{ ucfirst($source) }}</option>
                                        @endforeach
                                    </select>
                                </form>
                                <a href="{{ route($routePrefix . '.contacts.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 rounded-lg text-sm font-medium text-white hover:bg-blue-700 shadow-sm transition-colors">
                                    <i class="fas fa-plus mr-2"></i>
                                    New Contact
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">
                                    <th class="px-6 py-4">Contact</th>
                                    <th class="px-6 py-4">Email</th>
                                    <th class="px-6 py-4">Phone</th>
                                    <th class="px-6 py-4">Company</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4">Source</th>
                                    <th class="px-6 py-4">Created</th>
                                    <th class="px-6 py-4">Actions</th>
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
                                        @php
                                        $statusColors = [
                                            'Lead' => 'bg-blue-100 text-blue-700',
                                            'Prospect' => 'bg-yellow-100 text-yellow-700',
                                            'Client' => 'bg-emerald-100 text-emerald-700',
                                            'Lost' => 'bg-red-100 text-red-700',
                                            'Inactive' => 'bg-gray-100 text-gray-700',
                                        ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$contact->status] ?? 'bg-gray-100 text-gray-700' }}">
                                            {{ $contact->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst($contact->source) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $contact->created_at->format('M j, Y') }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route($routePrefix . '.contacts.show', $contact) }}" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route($routePrefix . '.contacts.edit', $contact) }}" class="p-2 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Edit">
                                                <i class="fas fa-pencil"></i>
                                            </a>
                                            <form action="{{ route($routePrefix . '.contacts.destroy', $contact) }}" method="POST" x-data="{ showModal: false }" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" @click="showModal = true" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <div x-show="showModal" x-transition class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                                                    <div class="flex items-center justify-center min-h-screen px-4">
                                                        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" @click="showModal = false"></div>
                                                        <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6 z-10">
                                                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete Contact</h3>
                                                            <p class="text-gray-600 mb-6">Are you sure you want to delete <strong>{{ $contact->name }}</strong>? This action cannot be undone.</p>
                                                            <div class="flex justify-end gap-3">
                                                                <button type="button" @click="showModal = false" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Cancel</button>
                                                                <button type="submit" class="px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded-lg">Delete</button>
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
                                            <h3 class="text-lg font-medium text-gray-900 mb-1">No contacts found</h3>
                                            <p class="text-gray-500 mb-4">Get started by creating your first contact.</p>
                                            <a href="{{ route($routePrefix . '.contacts.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 rounded-lg text-sm font-medium text-white hover:bg-blue-700">
                                                <i class="fas fa-plus mr-2"></i>
                                                New Contact
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
            </main>
        </div>
    </div>
</body>
</html>
