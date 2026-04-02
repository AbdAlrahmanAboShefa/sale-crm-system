<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Deals - Sales CRM</title>
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
                <div><h2 class="text-xl font-semibold text-gray-800">Deals</h2><p class="text-sm text-gray-500">{{ $deals->total() }} total deals</p></div>
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
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg flex items-center"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</div>
                @endif

                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <div class="flex items-center gap-2 flex-wrap">
                                <form method="GET" action="{{ route($routePrefix . '.deals.index') }}" class="relative">
                                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search deals..." class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </form>
                                <form method="GET" action="{{ route($routePrefix . '.deals.index') }}">
                                    <select name="stage" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                                        <option value="">All Stages</option>
                                        @foreach(['New', 'Contacted', 'Qualified', 'Proposal', 'Negotiation', 'Won', 'Lost'] as $stage)
                                        <option value="{{ $stage }}" {{ request('stage') == $stage ? 'selected' : '' }}>{{ $stage }}</option>
                                        @endforeach
                                    </select>
                                    @if(auth()->user()->hasRole(['Admin', 'Manager']))
                                    <select name="user_id" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                                        <option value="">All Agents</option>
                                        @foreach($users as $u)
                                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                        @endforeach
                                    </select>
                                    @endif
                                </form>
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="{{ route($routePrefix . '.deals.kanban') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                                    <i class="fas fa-columns mr-2"></i>Kanban View
                                </a>
                                <a href="{{ route($routePrefix . '.deals.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 rounded-lg text-sm font-medium text-white hover:bg-blue-700 shadow-sm transition-colors">
                                    <i class="fas fa-plus mr-2"></i>New Deal
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">
                                    <th class="px-6 py-4">Title</th>
                                    <th class="px-6 py-4">Contact</th>
                                    <th class="px-6 py-4">Value</th>
                                    <th class="px-6 py-4">Stage</th>
                                    <th class="px-6 py-4">Prob.</th>
                                    <th class="px-6 py-4">Expected Close</th>
                                    <th class="px-6 py-4">Owner</th>
                                    <th class="px-6 py-4">Days</th>
                                    <th class="px-6 py-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($deals as $deal)
                                <tr class="{{ $deal->stage === 'Won' ? 'bg-emerald-50' : ($deal->stage === 'Lost' ? 'bg-red-50' : '') }} hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <a href="{{ route($routePrefix . '.deals.show', $deal) }}" class="text-sm font-medium text-gray-900 hover:text-blue-600">{{ $deal->title }}</a>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        @if($deal->contact)
                                        <a href="{{ route($routePrefix . '.contacts.show', $deal->contact) }}" class="text-blue-600 hover:underline">{{ $deal->contact->name }}</a>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-emerald-600">${{ number_format($deal->value, 0) }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                        $stageColors = ['New' => 'bg-gray-100 text-gray-700', 'Contacted' => 'bg-blue-100 text-blue-700', 'Qualified' => 'bg-cyan-100 text-cyan-700', 'Proposal' => 'bg-yellow-100 text-yellow-700', 'Negotiation' => 'bg-orange-100 text-orange-700', 'Won' => 'bg-emerald-100 text-emerald-700', 'Lost' => 'bg-red-100 text-red-700'];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $stageColors[$deal->stage] ?? 'bg-gray-100 text-gray-700' }}">
                                            {{ $deal->stage }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $deal->probability }}%</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $deal->expected_close_date?->format('M j, Y') ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                                {{ strtoupper(substr($deal->user?->name ?? 'U', 0, 1)) }}
                                            </div>
                                            <span class="text-sm text-gray-600">{{ $deal->user?->name ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="{{ $deal->daysInStage() > 14 ? 'text-red-600 font-semibold' : ($deal->daysInStage() > 7 ? 'text-orange-600' : 'text-gray-600') }}">
                                            {{ $deal->daysInStage() }}d
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route($routePrefix . '.deals.show', $deal) }}" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route($routePrefix . '.deals.edit', $deal) }}" class="p-2 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Edit"><i class="fas fa-pencil"></i></a>
                                            <form action="{{ route($routePrefix . '.deals.destroy', $deal) }}" method="POST" x-data="{ showModal: false }" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="button" @click="showModal = true" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete"><i class="fas fa-trash"></i></button>
                                                <div x-show="showModal" x-transition class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                                                    <div class="flex items-center justify-center min-h-screen px-4">
                                                        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" @click="showModal = false"></div>
                                                        <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6 z-10">
                                                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete Deal</h3>
                                                            <p class="text-gray-600 mb-6">Are you sure you want to delete <strong>{{ $deal->title }}</strong>?</p>
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
                                    <td colspan="9" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-hand-holding-dollar text-gray-300 text-5xl mb-4"></i>
                                            <h3 class="text-lg font-medium text-gray-900 mb-1">No deals found</h3>
                                            <p class="text-gray-500 mb-4">Get started by creating your first deal.</p>
                                            <a href="{{ route($routePrefix . '.deals.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 rounded-lg text-sm font-medium text-white hover:bg-blue-700"><i class="fas fa-plus mr-2"></i>New Deal</a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($deals->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $deals->withQueryString()->links() }}
                    </div>
                    @endif
                </div>
            </main>
        </div>
    </div>
</body>
</html>
