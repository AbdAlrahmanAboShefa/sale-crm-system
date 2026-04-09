<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? __('messages.dashboard') }} - {{ __('messages.app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @if(app()->getLocale() === 'ar')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap5 RTL@latest/dist/css/bootstrap.rtl.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .rtl-input {
            text-align: right;
        }
    </style>
    @endif
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
                        <h1 class="text-lg font-bold">{{ __('messages.app.name') }}</h1>
                        <p class="text-xs text-slate-400">
                            @hasrole('Admin') {{ __('messages.roles.admin') }} @endhasrole
                            @hasrole('Manager') {{ __('messages.roles.manager') }} @endhasrole
                            @hasrole('Agent') {{ __('messages.roles.agent') }} @endhasrole
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
                    <i class="fas fa-home w-5 {{ app()->getLocale() === 'ar' ? 'ms-3 me-0' : 'me-3' }}"></i>
                    {{ __('messages.nav.dashboard') }}
                </a>
                <a href="{{ route('admin.contacts.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('admin.contacts.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-users w-5 {{ app()->getLocale() === 'ar' ? 'ms-3 me-0' : 'me-3' }}"></i>
                    {{ __('messages.contacts.title') }}
                </a>
                <a href="{{ route('admin.deals.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('admin.deals.*') && !request()->routeIs('admin.deals.kanban') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-hand-holding-dollar w-5 {{ app()->getLocale() === 'ar' ? 'ms-3 me-0' : 'me-3' }}"></i>
                    {{ __('messages.deals.title') }}
                </a>
                <a href="{{ route('admin.deals.kanban') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('admin.deals.kanban') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-columns w-5 {{ app()->getLocale() === 'ar' ? 'ms-3 me-0' : 'me-3' }}"></i>
                    {{ __('messages.deals.kanban_board') }}
                </a>
                <a href="{{ route('admin.activities.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('admin.activities.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-calendar-check w-5 {{ app()->getLocale() === 'ar' ? 'ms-3 me-0' : 'me-3' }}"></i>
                    {{ __('messages.activities.title') }}
                </a>
                @endrole

                @role('Manager')
                <div class="px-3 mb-2">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Management</span>
                </div>
                <a href="{{ route('manager.dashboard') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('manager.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-home w-5 {{ app()->getLocale() === 'ar' ? 'ms-3 me-0' : 'me-3' }}"></i>
                    {{ __('messages.nav.dashboard') }}
                </a>
                <a href="{{ route('manager.contacts.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('manager.contacts.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-users w-5 {{ app()->getLocale() === 'ar' ? 'ms-3 me-0' : 'me-3' }}"></i>
                    {{ __('messages.contacts.title') }}
                </a>
                <a href="{{ route('manager.deals.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('manager.deals.*') && !request()->routeIs('manager.deals.kanban') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-hand-holding-dollar w-5 {{ app()->getLocale() === 'ar' ? 'ms-3 me-0' : 'me-3' }}"></i>
                    {{ __('messages.deals.title') }}
                </a>
                <a href="{{ route('manager.deals.kanban') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('manager.deals.kanban') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-columns w-5 {{ app()->getLocale() === 'ar' ? 'ms-3 me-0' : 'me-3' }}"></i>
                    {{ __('messages.deals.kanban_board') }}
                </a>
                <a href="{{ route('manager.activities.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('manager.activities.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-calendar-check w-5 {{ app()->getLocale() === 'ar' ? 'ms-3 me-0' : 'me-3' }}"></i>
                    {{ __('messages.activities.title') }}
                </a>
                @endrole

                @role('Agent')
                <div class="px-3 mb-2">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Workspace</span>
                </div>
                <a href="{{ route('agent.dashboard') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('agent.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-home w-5 {{ app()->getLocale() === 'ar' ? 'ms-3 me-0' : 'me-3' }}"></i>
                    {{ __('messages.nav.dashboard') }}
                </a>
                <a href="{{ route('agent.contacts.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('agent.contacts.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-users w-5 {{ app()->getLocale() === 'ar' ? 'ms-3 me-0' : 'me-3' }}"></i>
                    {{ __('messages.contacts.title') }}
                </a>
                <a href="{{ route('agent.deals.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('agent.deals.*') && !request()->routeIs('agent.deals.kanban') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-hand-holding-dollar w-5 {{ app()->getLocale() === 'ar' ? 'ms-3 me-0' : 'me-3' }}"></i>
                    {{ __('messages.deals.title') }}
                </a>
                <a href="{{ route('agent.deals.kanban') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('agent.deals.kanban') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-columns w-5 {{ app()->getLocale() === 'ar' ? 'ms-3 me-0' : 'me-3' }}"></i>
                    {{ __('messages.deals.kanban_board') }}
                </a>
                <a href="{{ route('agent.activities.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('agent.activities.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-calendar-check w-5 {{ app()->getLocale() === 'ar' ? 'ms-3 me-0' : 'me-3' }}"></i>
                    {{ __('messages.activities.title') }}
                </a>
                @endrole
            </nav>
            <div class="p-4 border-t border-slate-700">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-2 text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors">
                        <i class="fas fa-sign-out-alt w-5 {{ app()->getLocale() === 'ar' ? 'ms-3 me-0' : 'me-3' }}"></i>
                        {{ __('messages.nav.logout') }}
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col">
            <header class="bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">{{ $title ?? __('messages.nav.dashboard') }}</h2>
                    <p class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <x-language-switcher />
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
                {{ $slot }}
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
