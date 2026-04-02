<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Contact - Sales CRM</title>
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
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-home w-5 mr-3"></i>Dashboard
                </a>
                <a href="{{ route('admin.contacts.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('admin.contacts.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-users w-5 mr-3"></i>Contacts
                </a>
                <a href="{{ route('admin.deals.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('admin.deals.*') && !request()->routeIs('admin.deals.kanban') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-hand-holding-dollar w-5 mr-3"></i>Deals
                </a>
                <a href="{{ route('admin.deals.kanban') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('admin.deals.kanban') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-columns w-5 mr-3"></i>Kanban
                </a>
                <a href="{{ route('admin.activities.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('admin.activities.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-calendar-check w-5 mr-3"></i>Activities
                </a>
                @endrole
                @role('Manager')
                <a href="{{ route('manager.dashboard') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('manager.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-home w-5 mr-3"></i>Dashboard
                </a>
                <a href="{{ route('manager.contacts.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('manager.contacts.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-users w-5 mr-3"></i>Contacts
                </a>
                <a href="{{ route('manager.deals.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('manager.deals.*') && !request()->routeIs('manager.deals.kanban') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-hand-holding-dollar w-5 mr-3"></i>Deals
                </a>
                <a href="{{ route('manager.deals.kanban') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('manager.deals.kanban') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-columns w-5 mr-3"></i>Kanban
                </a>
                <a href="{{ route('manager.activities.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('manager.activities.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-calendar-check w-5 mr-3"></i>Activities
                </a>
                @endrole
                @role('Agent')
                <a href="{{ route('agent.dashboard') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('agent.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-home w-5 mr-3"></i>Dashboard
                </a>
                <a href="{{ route('agent.contacts.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('agent.contacts.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-users w-5 mr-3"></i>Contacts
                </a>
                <a href="{{ route('agent.deals.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('agent.deals.*') && !request()->routeIs('agent.deals.kanban') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-hand-holding-dollar w-5 mr-3"></i>Deals
                </a>
                <a href="{{ route('agent.deals.kanban') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('agent.deals.kanban') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-columns w-5 mr-3"></i>Kanban
                </a>
                <a href="{{ route('agent.activities.index') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors {{ request()->routeIs('agent.activities.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fas fa-calendar-check w-5 mr-3"></i>Activities
                </a>
                @endrole
            </nav>
            <div class="p-4 border-t border-slate-700">
                <form action="{{ route('logout') }}" method="POST">@csrf<button type="submit" class="flex items-center w-full px-4 py-2 text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition-colors"><i class="fas fa-sign-out-alt w-5 mr-3"></i>Logout</button></form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col">
            <header class="bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                <div><h2 class="text-xl font-semibold text-gray-800">New Contact</h2></div>
                <div class="flex items-center gap-4">
                    <x-notification-bell />
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                        <div><p class="text-sm font-medium text-gray-800">{{ auth()->user()->name }}</p></div>
                    </div>
                </div>
            </header>

            <main class="p-6 bg-gray-50">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <form action="{{ route('agent.contacts.store') }}" method="POST">
                        @csrf
                        @if($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                            <ul class="list-disc list-inside text-sm">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                                @error('name')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                                @error('email')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="company" class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                                <input type="text" name="company" id="company" value="{{ old('company') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="website" class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                                <input type="url" name="website" id="website" value="{{ old('website') }}" placeholder="https://" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="source" class="block text-sm font-medium text-gray-700 mb-1">Source <span class="text-red-500">*</span></label>
                                <select name="source" id="source" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Source</option>
                                    @foreach(['website', 'referral', 'social', 'cold'] as $source)
                                    <option value="{{ $source }}" {{ old('source') == $source ? 'selected' : '' }}>{{ ucfirst($source) }}</option>
                                    @endforeach
                                </select>
                                @error('source')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                                <select name="status" id="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @foreach(['Lead', 'Prospect', 'Client', 'Lost', 'Inactive'] as $status)
                                    <option value="{{ $status }}" {{ old('status', 'Lead') == $status ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                                @error('status')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
                            <input type="text" name="tags_input" id="tags_input" placeholder="Enter tags separated by commas" value="{{ old('tags_input', '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Separate tags with commas</p>
                            <input type="hidden" name="tags" id="tags_hidden" value="{{ old('tags', '[]') }}">
                        </div>

                        <div class="flex justify-end gap-3 mt-8">
                            <a href="{{ route('agent.contacts.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">Cancel</a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">Create Contact</button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
