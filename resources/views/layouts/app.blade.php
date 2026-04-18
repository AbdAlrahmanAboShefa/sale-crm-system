<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? __('messages.app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=DM+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    @if(app()->getLocale() === 'ar')
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet"/>
    <style>
        body { font-family: 'Cairo', 'Segoe UI', sans-serif; }
    </style>
    @endif
    <link rel="stylesheet" href="{{ asset('css/dark-theme.css') }}">
</head>
<body class="dark-mode">
    <div class="gradient-mesh"></div>
    @auth
        @php
            $__tenant = auth()->user()->tenant;
            $__isSuperAdmin = auth()->user()->hasRole('Super Admin');
            $__showBanner = $__tenant
                && !$__isSuperAdmin
                && $__tenant->isOnTrial()
                && now()->diffInDays($__tenant->trial_ends_at) <= 7;
        @endphp
        @if($__showBanner)
            @php $__daysLeft = (int) now()->diffInDays($__tenant->trial_ends_at); @endphp
            <div class="relative z-50 bg-gradient-to-r from-amber-900/90 to-amber-800/90 border-b border-amber-700/50 px-6 py-3 backdrop-blur-sm">
                <div class="flex items-center justify-center gap-3 text-sm">
                    <span class="inline-flex items-center gap-2 text-amber-200">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span class="font-medium">Your trial expires in {{ $__daysLeft }} {{ $__daysLeft === 1 ? 'day' : 'days' }}.</span>
                    </span>
                    <a href="{{ route('billing.upgrade') }}" class="inline-flex items-center gap-2 px-4 py-1.5 bg-amber-500 hover:bg-amber-400 text-amber-950 rounded-lg text-sm font-semibold transition-all hover:scale-105">
                        <i class="fas fa-rocket"></i>
                        Upgrade Now
                    </a>
                </div>
            </div>
        @endif
    @endauth
    <div class="min-h-screen flex relative z-10">
        <aside class="dark-sidebar w-64 flex-shrink-0 flex flex-col backdrop-blur-xl">
            <div class="p-6 border-b border-[#2d3748]">
                <div class="flex items-center gap-4">
                    @hasrole('Super Admin')
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-lg shadow-amber-500/25">
                        <i class="fas fa-crown text-white text-xl"></i>
                    </div>
                    @else
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-500 to-violet-600 flex items-center justify-center shadow-lg shadow-cyan-500/20">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                    @endhasrole
                    <div>
                        <h1 class="text-lg font-bold text-slate-100 tracking-tight">{{ __('messages.app.name') }}</h1>
                        <div class="flex items-center gap-2 mt-0.5">
                            @hasrole('Super Admin')
                            <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                            @else
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            @endhasrole
                            <p class="text-xs text-slate-400 font-medium">
                                @hasrole('Super Admin') {{ __('messages.roles.super_admin') }} @endhasrole
                                @hasrole('Admin') {{ __('messages.roles.admin') }} @endhasrole
                                @hasrole('Manager') {{ __('messages.roles.manager') }} @endhasrole
                                @hasrole('Agent') {{ __('messages.roles.agent') }} @endhasrole
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <nav class="mt-4 px-3 flex-1 space-y-1">
                @role('Admin')
                <a href="{{ route('admin.dashboard') }}" class="dark-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home w-5 text-center"></i>
                    <span>{{ __('messages.nav.dashboard') }}</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="dark-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-user-shield w-5 text-center"></i>
                    <span>{{ __('messages.users.title') }}</span>
                </a>
                <a href="{{ route('admin.contacts.index') }}" class="dark-nav-item {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                    <i class="fas fa-address-book w-5 text-center"></i>
                    <span>{{ __('messages.contacts.title') }}</span>
                </a>
                <a href="{{ route('admin.deals.index') }}" class="dark-nav-item {{ request()->routeIs('admin.deals.*') && !request()->routeIs('admin.deals.kanban') ? 'active' : '' }}">
                    <i class="fas fa-hand-holding-dollar w-5 text-center"></i>
                    <span>{{ __('messages.deals.title') }}</span>
                </a>
                <a href="{{ route('admin.deals.kanban') }}" class="dark-nav-item {{ request()->routeIs('admin.deals.kanban') ? 'active' : '' }}">
                    <i class="fas fa-columns w-5 text-center"></i>
                    <span>{{ __('messages.deals.kanban_board') }}</span>
                </a>
                <a href="{{ route('admin.activities.index') }}" class="dark-nav-item {{ request()->routeIs('admin.activities.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check w-5 text-center"></i>
                    <span>{{ __('messages.activities.title') }}</span>
                </a>
                <a href="{{ route('admin.settings') }}" class="dark-nav-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                    <i class="fas fa-cog w-5 text-center"></i>
                    <span>Settings</span>
                </a>
                <a href="{{ route('billing.upgrade') }}" class="dark-nav-item text-amber-400 hover:text-amber-300 hover:bg-amber-500/10" style="margin-top: 8px; border: 1px dashed rgba(245, 158, 11, 0.3); border-radius: 12px;">
                    <i class="fas fa-crown w-5 text-center"></i>
                    <span>{{ __('messages.billing.upgrade_btn', ['default' => 'Upgrade Plan']) }}</span>
                </a>
                @endrole
                @role('Manager')
                <a href="{{ route('manager.dashboard') }}" class="dark-nav-item {{ request()->routeIs('manager.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home w-5 text-center"></i>
                    <span>{{ __('messages.nav.dashboard') }}</span>
                </a>
                <a href="{{ route('manager.contacts.index') }}" class="dark-nav-item {{ request()->routeIs('manager.contacts.*') ? 'active' : '' }}">
                    <i class="fas fa-address-book w-5 text-center"></i>
                    <span>{{ __('messages.contacts.title') }}</span>
                </a>
                <a href="{{ route('manager.deals.index') }}" class="dark-nav-item {{ request()->routeIs('manager.deals.*') && !request()->routeIs('manager.deals.kanban') ? 'active' : '' }}">
                    <i class="fas fa-hand-holding-dollar w-5 text-center"></i>
                    <span>{{ __('messages.deals.title') }}</span>
                </a>
                <a href="{{ route('manager.deals.kanban') }}" class="dark-nav-item {{ request()->routeIs('manager.deals.kanban') ? 'active' : '' }}">
                    <i class="fas fa-columns w-5 text-center"></i>
                    <span>{{ __('messages.deals.kanban_board') }}</span>
                </a>
                <a href="{{ route('manager.activities.index') }}" class="dark-nav-item {{ request()->routeIs('manager.activities.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check w-5 text-center"></i>
                    <span>{{ __('messages.activities.title') }}</span>
                </a>
                <a href="{{ route('billing.upgrade') }}" class="dark-nav-item text-amber-400 hover:text-amber-300 hover:bg-amber-500/10" style="margin-top: 8px; border: 1px dashed rgba(245, 158, 11, 0.3); border-radius: 12px;">
                    <i class="fas fa-crown w-5 text-center"></i>
                    <span>{{ __('messages.billing.upgrade_btn', ['default' => 'Upgrade Plan']) }}</span>
                </a>
                @endrole
                @role('Agent')
                <a href="{{ route('agent.dashboard') }}" class="dark-nav-item {{ request()->routeIs('agent.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home w-5 text-center"></i>
                    <span>{{ __('messages.nav.dashboard') }}</span>
                </a>
                <a href="{{ route('agent.contacts.index') }}" class="dark-nav-item {{ request()->routeIs('agent.contacts.*') ? 'active' : '' }}">
                    <i class="fas fa-address-book w-5 text-center"></i>
                    <span>{{ __('messages.contacts.title') }}</span>
                </a>
                <a href="{{ route('agent.deals.index') }}" class="dark-nav-item {{ request()->routeIs('agent.deals.*') && !request()->routeIs('agent.deals.kanban') ? 'active' : '' }}">
                    <i class="fas fa-hand-holding-dollar w-5 text-center"></i>
                    <span>{{ __('messages.deals.title') }}</span>
                </a>
                <a href="{{ route('agent.deals.kanban') }}" class="dark-nav-item {{ request()->routeIs('agent.deals.kanban') ? 'active' : '' }}">
                    <i class="fas fa-columns w-5 text-center"></i>
                    <span>{{ __('messages.deals.kanban_board') }}</span>
                </a>
                <a href="{{ route('agent.activities.index') }}" class="dark-nav-item {{ request()->routeIs('agent.activities.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check w-5 text-center"></i>
                    <span>{{ __('messages.activities.title') }}</span>
                </a>
                <a href="{{ route('billing.upgrade') }}" class="dark-nav-item text-amber-400 hover:text-amber-300 hover:bg-amber-500/10" style="margin-top: 8px; border: 1px dashed rgba(245, 158, 11, 0.3); border-radius: 12px;">
                    <i class="fas fa-crown w-5 text-center"></i>
                    <span>{{ __('messages.billing.upgrade_btn', ['default' => 'Upgrade Plan']) }}</span>
                </a>
                @endrole
                @role('Super Admin')
                <a href="{{ route('super_admin.dashboard') }}" class="dark-nav-item {{ request()->routeIs('super_admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-crown w-5 text-center"></i>
                    <span>{{ __('messages.nav.dashboard') }}</span>
                </a>
                <a href="{{ route('super_admin.tenants.index') }}" class="dark-nav-item {{ request()->routeIs('super_admin.tenants.*') ? 'active' : '' }}">
                    <i class="fas fa-server w-5 text-center"></i>
                    <span>{{ __('messages.tenants.title') }}</span>
                </a>
                @endrole
            </nav>
            <div class="p-4 border-t border-[#2d3748]">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dark-nav-item w-full text-left hover:text-rose-400 hover:bg-rose-500/10">
                        <i class="fas fa-sign-out-alt w-5 text-center"></i>
                        <span>{{ __('messages.nav.logout') }}</span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col">
            <header class="dark-header px-6 py-4 flex justify-between items-center sticky top-0 z-40">
                <div>
                    <h2 class="text-xl font-bold text-slate-100 tracking-tight">{{ $title ?? __('messages.nav.dashboard') }}</h2>
                    <p class="text-xs text-slate-500 mt-0.5 flex items-center gap-2">
                        <i class="fas fa-clock"></i>
                        {{ now()->format('l, F j, Y') }}
                    </p>
                </div>
                <div class="flex items-center gap-4">
                    <x-language-switcher />
                    <x-notification-bell />
                    <a href="{{ route('profile') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500 to-violet-600 flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-cyan-500/20 group-hover:shadow-cyan-500/40 transition-all">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div class="hidden md:block">
                            <p class="text-sm font-semibold text-slate-200 group-hover:text-white transition-colors">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
                        </div>
                    </a>
                </div>
            </header>

            <main class="p-6 relative">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
