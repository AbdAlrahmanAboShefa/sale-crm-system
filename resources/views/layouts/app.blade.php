<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sales CRM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        @auth
            <aside class="w-64 bg-gray-900 text-white flex-shrink-0">
                <div class="p-4 border-b border-gray-700">
                    <h1 class="text-xl font-bold">Sales CRM</h1>
                </div>
                <nav class="mt-4 space-y-1">
                    @role('Admin')
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 hover:bg-gray-800 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800' : '' }}">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.contacts.index') }}" class="flex items-center px-4 py-2 hover:bg-gray-800 {{ request()->routeIs('admin.contacts.*') ? 'bg-gray-800' : '' }}">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Contacts
                    </a>
                    <a href="{{ route('admin.deals.index') }}" class="flex items-center px-4 py-2 hover:bg-gray-800 {{ request()->routeIs('admin.deals.*') ? 'bg-gray-800' : '' }}">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Deals
                    </a>
                    <a href="{{ route('admin.deals.kanban') }}" class="flex items-center px-4 py-2 hover:bg-gray-800 pl-8 text-sm text-gray-300 {{ request()->routeIs('admin.deals.kanban') ? 'bg-gray-800 text-white' : '' }}">
                        Kanban Board
                    </a>
                    <a href="{{ route('admin.activities.index') }}" class="flex items-center px-4 py-2 hover:bg-gray-800 {{ request()->routeIs('admin.activities.*') ? 'bg-gray-800' : '' }}">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Activities
                    </a>
                    @endrole

                    @role('Manager')
                    <a href="{{ route('manager.dashboard') }}" class="flex items-center px-4 py-2 hover:bg-gray-800 {{ request()->routeIs('manager.dashboard') ? 'bg-gray-800' : '' }}">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('manager.contacts.index') }}" class="flex items-center px-4 py-2 hover:bg-gray-800 {{ request()->routeIs('manager.contacts.*') ? 'bg-gray-800' : '' }}">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Contacts
                    </a>
                    <a href="{{ route('manager.deals.index') }}" class="flex items-center px-4 py-2 hover:bg-gray-800 {{ request()->routeIs('manager.deals.*') ? 'bg-gray-800' : '' }}">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Deals
                    </a>
                    <a href="{{ route('manager.deals.kanban') }}" class="flex items-center px-4 py-2 hover:bg-gray-800 pl-8 text-sm text-gray-300 {{ request()->routeIs('manager.deals.kanban') ? 'bg-gray-800 text-white' : '' }}">
                        Kanban Board
                    </a>
                    <a href="{{ route('manager.activities.index') }}" class="flex items-center px-4 py-2 hover:bg-gray-800 {{ request()->routeIs('manager.activities.*') ? 'bg-gray-800' : '' }}">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Activities
                    </a>
                    @endrole

                    @role('Agent')
                    <a href="{{ route('agent.dashboard') }}" class="flex items-center px-4 py-2 hover:bg-gray-800 {{ request()->routeIs('agent.dashboard') ? 'bg-gray-800' : '' }}">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('agent.contacts.index') }}" class="flex items-center px-4 py-2 hover:bg-gray-800 {{ request()->routeIs('agent.contacts.*') ? 'bg-gray-800' : '' }}">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Contacts
                    </a>
                    <a href="{{ route('agent.deals.index') }}" class="flex items-center px-4 py-2 hover:bg-gray-800 {{ request()->routeIs('agent.deals.*') ? 'bg-gray-800' : '' }}">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Deals
                    </a>
                    <a href="{{ route('agent.deals.kanban') }}" class="flex items-center px-4 py-2 hover:bg-gray-800 pl-8 text-sm text-gray-300 {{ request()->routeIs('agent.deals.kanban') ? 'bg-gray-800 text-white' : '' }}">
                        Kanban Board
                    </a>
                    <a href="{{ route('agent.activities.index') }}" class="flex items-center px-4 py-2 hover:bg-gray-800 {{ request()->routeIs('agent.activities.*') ? 'bg-gray-800' : '' }}">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Activities
                    </a>
                    @endrole
                </nav>
            </aside>
        @endauth

        <div class="flex-1 flex flex-col">
            @auth
            <header class="bg-white shadow px-6 py-3 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-700">
                    @hasrole('Admin') Admin @endhasrole
                    @hasrole('Manager') Manager @endhasrole
                    @hasrole('Agent') Agent @endhasrole
                </h2>
                <div class="flex items-center gap-4">
                    <x-notification-bell />
                    <span class="text-gray-600">{{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                            Logout
                        </button>
                    </form>
                </div>
            </header>
            @endauth

            <main class="p-6">
                {{ $slot ?? $content ?? '' }}
            </main>
        </div>
    </div>
</body>
</html>
