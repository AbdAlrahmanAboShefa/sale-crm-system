<x-app-layout title="Deals Kanban">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Deals Pipeline</h1>
            <p class="text-sm text-gray-500 mt-1">Total Forecast: <span class="font-semibold text-green-600">{{ $forecast['currency'] }} {{ number_format($forecast['total'], 0) }}</span></p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route(Route::currentRouteNamed() . '.deals.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                List View
            </a>
            <a href="{{ route(Route::currentRouteNamed() . '.deals.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                + New Deal
            </a>
        </div>
    </div>

    <div class="flex gap-4 overflow-x-auto pb-4" x-data="kanbanApp()">
        @foreach(['New', 'Contacted', 'Qualified', 'Proposal', 'Negotiation', 'Won', 'Lost'] as $stage)
        <div class="flex-shrink-0 w-72">
            <div class="bg-gray-100 rounded-lg p-3">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold text-gray-700">{{ $stage }}</h3>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded-full text-gray-600">
                        {{ $kanban[$stage]['count'] ?? 0 }}
                    </span>
                </div>
                
                <div class="space-y-3 min-h-[200px]" data-stage="{{ $stage }}">
                    @forelse($kanban[$stage]['deals'] ?? [] as $deal)
                    <div class="bg-white rounded-lg p-3 shadow-sm hover:shadow-md transition-shadow cursor-move deal-card"
                         data-deal-id="{{ $deal->id }}"
                         data-stage="{{ $stage }}">
                        <div class="flex items-start justify-between mb-2">
                            <a href="{{ route(Route::currentRouteNamed() . '.deals.show', $deal) }}" class="text-sm font-medium text-gray-800 hover:text-blue-600">
                                {{ $deal->title }}
                            </a>
                        </div>
                        
                        <div class="text-xs text-gray-500 mb-2">
                            {{ $deal->contact?->name ?? 'No contact' }}
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-800">
                                {{ $deal->currency }} {{ number_format($deal->value, 0) }}
                            </span>
                            <span class="text-xs text-gray-400">
                                {{ $deal->daysInStage() }}d
                            </span>
                        </div>
                        
                        @if($isAdminOrManager)
                        <div class="mt-2 pt-2 border-t text-xs text-gray-500">
                            {{ $deal->user?->name }}
                        </div>
                        @endif
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-400 text-sm">
                        No deals
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
        function kanbanApp() {
            return {
                init() {
                    document.querySelectorAll('[data-stage]').forEach(column => {
                        new Sortable(column, {
                            group: 'deals',
                            animation: 150,
                            ghostClass: 'opacity-50',
                            onEnd: (evt) => {
                                const dealId = evt.item.dataset.dealId;
                                const newStage = evt.to.dataset.stage;
                                
                                if (evt.from !== evt.to || newStage !== evt.item.dataset.stage) {
                                    this.updateDealStage(dealId, newStage);
                                }
                            }
                        });
                    });
                },
                updateDealStage(dealId, stage) {
                    fetch(`/deals/${dealId}/stage`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ stage })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        location.reload();
                    });
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
