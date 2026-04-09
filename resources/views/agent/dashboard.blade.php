@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">{{ __('messages.app.dashboard') }}</h1>
        <p class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">{{ __('messages.dashboard.my_deals') ?? 'My Deals' }}</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['totalDeals'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-hand-holding-dollar text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">{{ __('messages.deals.active_deals') }}</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['activeDeals'] }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-emerald-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">{{ __('messages.dashboard.my_pipeline_value') ?? 'My Pipeline Value' }}</p>
                    <p class="text-2xl font-bold text-gray-800">${{ number_format($stats['totalValue'], 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">{{ __('messages.deals.won_deals') }}</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['wonDeals'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-trophy text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('messages.dashboard.my_pipeline') ?? 'My Pipeline' }}</h3>
            <div class="space-y-4">
                @foreach(['New', 'Contacted', 'Qualified', 'Proposal', 'Negotiation', 'Won'] as $stage)
                @php
                    $stageData = $stageStats[$stage] ?? ['count' => 0, 'value' => 0];
                    $maxValue = max($stageStats['Proposal']['value'] ?? 1, $stageStats['Negotiation']['value'] ?? 1, $stageStats['Qualified']['value'] ?? 1);
                    $percentage = $maxValue > 0 ? ($stageData['value'] / $maxValue) * 100 : 0;
                    $colors = [
                        'New' => 'bg-blue-500',
                        'Contacted' => 'bg-cyan-500',
                        'Qualified' => 'bg-teal-500',
                        'Proposal' => 'bg-yellow-500',
                        'Negotiation' => 'bg-orange-500',
                        'Won' => 'bg-emerald-500',
                    ];
                @endphp
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">{{ $stage }}</span>
                        <span class="text-sm text-gray-500">{{ $stageData['count'] }} {{ __('messages.deals.title') }} - ${{ number_format($stageData['value'], 0) }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3">
                        <div class="{{ $colors[$stage] }} h-3 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('messages.dashboard.deals_by_stage') }}</h3>
            <div class="flex items-center justify-center">
                <div class="relative">
                    <canvas id="stageChart" width="200" height="200"></canvas>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2 mt-4">
                @foreach(['New', 'Contacted', 'Qualified', 'Proposal', 'Negotiation', 'Won', 'Lost'] as $stage)
                @php
                    $colors = [
                        'New' => 'bg-gray-500',
                        'Contacted' => 'bg-blue-500',
                        'Qualified' => 'bg-cyan-500',
                        'Proposal' => 'bg-yellow-500',
                        'Negotiation' => 'bg-orange-500',
                        'Won' => 'bg-emerald-500',
                        'Lost' => 'bg-red-500',
                    ];
                @endphp
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full {{ $colors[$stage] }}"></div>
                    <span class="text-xs text-gray-600">{{ $stage }}: {{ $stageStats[$stage]['count'] ?? 0 }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">{{ __('messages.dashboard.my_recent_activities') ?? 'My Recent Activities' }}</h3>
                <a href="{{ route('agent.activities.index') }}" class="text-sm text-blue-600 hover:text-blue-700">{{ __('messages.common.view_all') }}</a>
            </div>
            <div class="space-y-4">
                @forelse($recentActivities as $activity)
                <div class="flex items-start gap-3 pb-4 border-b border-gray-100 last:border-0 last:pb-0">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                        @if($activity->type === 'Call') bg-blue-100 text-blue-600
                        @elseif($activity->type === 'Meeting') bg-purple-100 text-purple-600
                        @elseif($activity->type === 'Email') bg-green-100 text-green-600
                        @elseif($activity->type === 'Demo') bg-orange-100 text-orange-600
                        @else bg-gray-100 text-gray-600 @endif">
                        <i class="fas fa-{{ $activity->type === 'Call' ? 'phone' : ($activity->type === 'Meeting' ? 'users' : ($activity->type === 'Email' ? 'envelope' : ($activity->type === 'Demo' ? 'desktop' : 'tasks'))) }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800">
                            @if($activity->contact)
                                {{ $activity->contact->name }}
                            @elseif($activity->deal)
                                {{ $activity->deal->title }}
                            @else
                                {{ $activity->type }}
                            @endif
                        </p>
                        <p class="text-xs text-gray-500 truncate">{{ $activity->note ?: __('messages.activities.no_notes') }}</p>
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $activity->created_at->diffForHumans() }}
                            @if($activity->is_done)
                                <span class="text-green-600 {{ app()->getLocale() === 'ar' ? 'me-2' : 'ml-2' }}"><i class="fas fa-check-circle"></i> {{ __('messages.activities.completed') }}</span>
                            @elseif($activity->isOverdue())
                                <span class="text-red-600 {{ app()->getLocale() === 'ar' ? 'me-2' : 'ml-2' }}"><i class="fas fa-exclamation-circle"></i> {{ __('messages.activities.overdue') }}</span>
                            @endif
                        </p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-inbox text-3xl mb-2"></i>
                    <p class="text-sm">{{ __('messages.activities.no_activities') }}</p>
                </div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">{{ __('messages.dashboard.monthly_performance') }}</h3>
            </div>
            <div class="h-64 flex items-end justify-around gap-2">
                @for($i = 1; $i <= 12; $i++)
                @php
                    $monthData = $monthlyDeals[$i] ?? null;
                    $count = $monthData->count ?? 0;
                    $maxCount = $monthlyDeals->max('count') ?: 1;
                    $height = ($count / $maxCount) * 100;
                @endphp
                <div class="flex flex-col items-center flex-1">
                    <div class="w-full bg-gradient-to-t from-blue-500 to-blue-300 rounded-t-md transition-all duration-300" style="height: {{ max($height, 5) }}%"></div>
                    <span class="text-xs text-gray-500 mt-2">{{ date('M', mktime(0, 0, 0, $i, 1)) }}</span>
                    <span class="text-xs font-medium text-gray-700">{{ $count }}</span>
                </div>
                @endfor
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('stageChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_keys($stageStats)) !!},
                datasets: [{
                    data: {!! json_encode(array_column($stageStats, 'count')) !!},
                    backgroundColor: [
                        '#6b7280',
                        '#3b82f6',
                        '#06b6d4',
                        '#eab308',
                        '#f97316',
                        '#10b981',
                        '#ef4444'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false }
                },
                cutout: '65%'
            }
        });
    }
});
</script>
@endpush
@endsection
