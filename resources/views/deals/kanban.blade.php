@extends('layouts.app')
@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-100 tracking-tight">Deals Pipeline</h1>
            <p class="text-sm text-slate-500 mt-1 flex items-center gap-2">
                <i class="fas fa-chart-pie text-cyan-400"></i>
                Total Forecast: <span class="font-semibold text-emerald-400">{{ $forecast['currency'] }} {{ number_format($forecast['total'], 0) }}</span>
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route($routePrefix . '.deals.index') }}" class="dark-btn dark-btn-secondary">
                <i class="fas fa-list"></i>
                List View
            </a>
            <a href="{{ route($routePrefix . '.deals.create') }}" class="dark-btn dark-btn-primary">
                <i class="fas fa-plus"></i>
                New Deal
            </a>
        </div>
    </div>

    <div class="flex gap-5 pb-6 overflow-x-auto" id="kanban-board">
        @foreach(['New', 'Contacted', 'Qualified', 'Proposal', 'Negotiation', 'Won', 'Lost'] as $stage)
        @php
            $stageColors = [
                'New' => 'from-slate-500 to-slate-600',
                'Contacted' => 'from-blue-500 to-blue-600',
                'Qualified' => 'from-cyan-500 to-cyan-600',
                'Proposal' => 'from-yellow-500 to-yellow-600',
                'Negotiation' => 'from-orange-500 to-orange-600',
                'Won' => 'from-emerald-500 to-emerald-600',
                'Lost' => 'from-red-500 to-red-600',
            ];
        @endphp
        <div class="flex-shrink-0 w-80" data-stage-column="{{ $stage }}">
            <div class="dark-kanban-column">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-gradient-to-r {{ $stageColors[$stage] }}"></div>
                        <h3 class="font-semibold text-slate-200">{{ $stage }}</h3>
                    </div>
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-slate-700/50 text-slate-400 text-sm font-semibold kanban-count" data-stage="{{ $stage }}">
                        {{ $kanban[$stage]['count'] ?? 0 }}
                    </span>
                </div>
                
                <div class="space-y-3 min-h-[400px] kanban-dropzone" data-stage="{{ $stage }}">
                    @forelse($kanban[$stage]['deals'] ?? [] as $deal)
                    <div class="dark-kanban-card group" data-deal-id="{{ $deal->id }}" data-stage="{{ $stage }}">
                        <div class="flex items-start justify-between mb-3">
                            <a href="{{ route($routePrefix.'.deals.show', $deal) }}" class="text-sm font-semibold text-slate-200 hover:text-cyan-400 transition-colors">
                                {{ $deal->title }}
                            </a>
                        </div>
                        
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fas fa-user text-slate-500 text-xs"></i>
                            <span class="text-xs text-slate-400">{{ $deal->contact?->name ?? 'No contact' }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between pt-3 border-t border-slate-700/50">
                            <span class="text-lg font-bold text-emerald-400">
                                {{ $deal->currency }} {{ number_format($deal->value, 0) }}
                            </span>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-slate-500 flex items-center gap-1">
                                    <i class="fas fa-clock"></i>
                                    {{ $deal->daysInStage() }}d
                                </span>
                                @if($isAdminOrManager)
                                <div class="w-6 h-6 rounded-md bg-gradient-to-br from-cyan-500 to-violet-600 flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr($deal->user?->name ?? 'U', 0, 1)) }}
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="absolute inset-0 rounded-xl border-2 border-transparent group-hover:border-cyan-500/30 transition-all pointer-events-none"></div>
                    </div>
                    @empty
                    <div class="text-center py-12 empty-state">
                        <div class="w-16 h-16 rounded-2xl bg-slate-800/50 flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-inbox text-slate-600 text-xl"></i>
                        </div>
                        <p class="text-sm text-slate-500">No deals</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initKanban();
        });

        function initKanban() {
            const dropzones = document.querySelectorAll('.kanban-dropzone');
            
            dropzones.forEach(function(dropzone) {
                new Sortable(dropzone, {
                    group: 'kanban-deals',
                    animation: 200,
                    ghostClass: 'opacity-50',
                    dragClass: 'sortable-drag',
                    handle: '.dark-kanban-card',
                    filter: '.empty-state',
                    onStart: function(evt) {
                        document.body.classList.add('sorting');
                    },
                    onEnd: function(evt) {
                        document.body.classList.remove('sorting');
                        
                        const dealId = evt.item.dataset.dealId;
                        const newStage = evt.to.dataset.stage;
                        const oldStage = evt.from.dataset.stage;
                        
                        if (newStage !== oldStage) {
                            updateDealStage(dealId, newStage);
                        }
                    }
                });
            });
        }

        function updateDealStage(dealId, stage) {
            fetch('/{{ $routePrefix }}/deals/' + dealId + '/stage', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ stage: stage })
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to update deal stage');
                    location.reload();
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
                location.reload();
            });
        }
    </script>
    @endpush
@endsection
