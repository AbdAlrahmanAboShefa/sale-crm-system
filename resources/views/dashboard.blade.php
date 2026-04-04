@extends('layouts.app')
@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}</h1>
                <p class="text-gray-500 mt-1">Here's what's happening with your sales today.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route($routePrefix . '.contacts.create') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm transition-colors">
                    <i class="fas fa-user-plus mr-2"></i>
                    New Contact
                </a>
                <a href="{{ route($routePrefix . '.deals.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 rounded-lg text-sm font-medium text-white hover:bg-blue-700 shadow-sm transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    New Deal
                </a>
                <a href="{{ route($routePrefix . '.activities.create') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm transition-colors">
                    <i class="fas fa-clipboard-list mr-2"></i>
                    Log Activity
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="h-1 bg-blue-500"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Pipeline Value</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">${{ number_format($kpis['pipelineValue'], 0) }}</p>
                            <p class="text-xs text-gray-400 mt-1">Active deals value</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="h-1 bg-emerald-500"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Won This Month</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">${{ number_format($kpis['wonThisMonth']['value'] ?? 0, 0) }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $kpis['wonThisMonth']['count'] ?? 0 }} deals closed</p>
                        </div>
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-trophy text-emerald-600 text-xl"></i>
                        </div>
                    </div>
                    @if(($kpis['wonThisMonth']['value'] ?? 0) > 0)
                    <div class="mt-4 flex items-center text-xs">
                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">
                            <i class="fas fa-arrow-up mr-1"></i>
                            <span class="font-medium">+12%</span>
                        </span>
                        <span class="ml-2 text-gray-500">vs last month</span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="h-1 bg-purple-500"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Conversion Rate</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $kpis['conversionRate'] ?? 0 }}%</p>
                            <p class="text-xs text-gray-400 mt-1">Leads to won deals</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-bullseye text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="h-1 bg-red-500"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Overdue Activities</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $kpis['overdueActivities'] ?? 0 }}</p>
                            <p class="text-xs text-gray-400 mt-1">Need attention</p>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-gray-900">Monthly Revenue</h2>
                    <select class="text-sm border-gray-300 rounded-lg text-gray-600 focus:ring-blue-500 focus:border-blue-500">
                        <option>Last 12 months</option>
                    </select>
                </div>
                <div class="h-72">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Pipeline Funnel</h2>
                <div class="h-72">
                    <canvas id="funnelChart"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Activities</h2>
                    <a href="{{ route($routePrefix . '.activities.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View all</a>
                </div>
                <div class="space-y-4">
                    @forelse($recentActivities as $activity)
                    <div class="flex items-start gap-4 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0
                            @if($activity['type'] === 'call') bg-blue-100 text-blue-600
                            @elseif($activity['type'] === 'email') bg-purple-100 text-purple-600
                            @elseif($activity['type'] === 'meeting') bg-emerald-100 text-emerald-600
                            @else bg-gray-100 text-gray-600
                            @endif">
                            @if($activity['type'] === 'call')
                            <i class="fas fa-phone text-sm"></i>
                            @elseif($activity['type'] === 'email')
                            <i class="fas fa-envelope text-sm"></i>
                            @elseif($activity['type'] === 'meeting')
                            <i class="fas fa-users text-sm"></i>
                            @else
                            <i class="fas fa-clipboard-list text-sm"></i>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $activity['title'] }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                @if($activity['deal_name'])
                                {{ $activity['deal_name'] }}
                                @else
                                {{ $activity['description'] }}
                                @endif
                            </p>
                        </div>
                        <span class="text-xs text-gray-400 whitespace-nowrap">{{ $activity['time_ago'] }}</span>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-inbox text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-500">No recent activities</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-gray-900">Top Deals</h2>
                    <a href="{{ route($routePrefix . '.deals.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View all</a>
                </div>
                <div class="overflow-hidden">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <th class="pb-3">Deal</th>
                                <th class="pb-3">Stage</th>
                                <th class="pb-3 text-right">Value</th>
                                <th class="pb-3 text-right">Prob.</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($topDeals as $deal)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3">
                                    <a href="{{ route($routePrefix . '.deals.show', $deal['id']) }}" class="text-sm font-medium text-gray-900 hover:text-blue-600">
                                        {{ $deal['name'] }}
                                    </a>
                                    <p class="text-xs text-gray-500">{{ $deal['contact_name'] }}</p>
                                </td>
                                <td class="py-3">
                                    @php
                                    $stageColors = [
                                        'New' => 'bg-gray-100 text-gray-700',
                                        'Contacted' => 'bg-blue-100 text-blue-700',
                                        'Qualified' => 'bg-cyan-100 text-cyan-700',
                                        'Proposal' => 'bg-purple-100 text-purple-700',
                                        'Negotiation' => 'bg-yellow-100 text-yellow-700',
                                        'Won' => 'bg-emerald-100 text-emerald-700',
                                        'Lost' => 'bg-red-100 text-red-700',
                                    ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $stageColors[$deal['stage']] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ $deal['stage'] }}
                                    </span>
                                </td>
                                <td class="py-3 text-right">
                                    <span class="text-sm font-semibold text-gray-900">${{ number_format($deal['value'], 0) }}</span>
                                </td>
                                <td class="py-3 text-right">
                                    <span class="text-sm text-gray-600">{{ $deal['probability'] }}%</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center">
                                    <i class="fas fa-hand-holding-dollar text-gray-300 text-4xl mb-3"></i>
                                    <p class="text-gray-500">No deals yet</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($isAdminOrManager && !empty($leaderboard))
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Top Agents Leaderboard</h2>
                <i class="fas fa-trophy text-yellow-500"></i>
            </div>
            <div class="overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <th class="pb-3 pr-4">Rank</th>
                            <th class="pb-3 pr-4">Agent</th>
                            <th class="pb-3 pr-4">Deals Won</th>
                            <th class="pb-3 text-right">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($leaderboard as $index => $agent)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 pr-4">
                                @if($index === 0)
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold">
                                    <i class="fas fa-medal"></i>
                                </span>
                                @elseif($index === 1)
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-200 text-gray-600 text-xs font-bold">
                                    <i class="fas fa-medal"></i>
                                </span>
                                @elseif($index === 2)
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-orange-100 text-orange-700 text-xs font-bold">
                                    <i class="fas fa-medal"></i>
                                </span>
                                @else
                                <span class="text-sm font-semibold text-gray-500">{{ $index + 1 }}</span>
                                @endif
                            </td>
                            <td class="py-3 pr-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                        {{ strtoupper(substr($agent->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $agent->name }}</span>
                                </div>
                            </td>
                            <td class="py-3 pr-4 text-sm text-gray-600">{{ $agent->won_deals }} deals</td>
                            <td class="py-3 text-right">
                                <span class="text-sm font-semibold text-emerald-600">${{ number_format($agent->pipeline_value, 0) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const revenueLabels = @json($monthlyRevenue['labels'] ?? []);
            const revenueData = @json($monthlyRevenue['values'] ?? []);

            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: revenueLabels,
                    datasets: [{
                        label: 'Revenue',
                        data: revenueData,
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1,
                        borderRadius: 6,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    return '$' + context.raw.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9',
                                drawBorder: false
                            },
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                },
                                color: '#64748b',
                                font: {
                                    family: 'Inter, sans-serif'
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    family: 'Inter, sans-serif'
                                }
                            }
                        }
                    }
                }
            });

            const funnelLabels = @json($pipelineFunnel['labels'] ?? []);
            const funnelData = @json($pipelineFunnel['values'] ?? []);

            const funnelCtx = document.getElementById('funnelChart').getContext('2d');
            new Chart(funnelCtx, {
                type: 'bar',
                data: {
                    labels: funnelLabels,
                    datasets: [{
                        label: 'Deals',
                        data: funnelData,
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(139, 92, 246, 0.8)',
                            'rgba(236, 72, 153, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                        ],
                        borderRadius: 6,
                        borderSkipped: false,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            padding: 12,
                            cornerRadius: 8,
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    family: 'Inter, sans-serif'
                                }
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    family: 'Inter, sans-serif'
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
@endsection
