<x-app-layout title="Activity Details">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 text-sm rounded-full 
                    @if($activity->is_done) bg-green-100 text-green-700 @else bg-yellow-100 text-yellow-700 @endif">
                    {{ $activity->is_done ? 'Completed' : 'Pending' }}
                </span>
                <span class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded-full">
                    {{ $activity->type }}
                </span>
            </div>
            <a href="{{ route(Route::currentRouteNamed() . '.activities.index') }}" class="text-gray-600 hover:text-gray-800">
                ← Back
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Activity Details</h2>
                    
                    <div class="prose prose-sm max-w-none text-gray-600">
                        <p>{{ $activity->note }}</p>
                    </div>

                    @if($activity->outcome)
                    <div class="mt-4 pt-4 border-t">
                        <span class="text-sm text-gray-500">Outcome: </span>
                        <span class="px-2 py-1 text-sm rounded 
                            @if($activity->outcome === 'Positive') bg-green-100 text-green-700
                            @elseif($activity->outcome === 'Negative') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ $activity->outcome }}
                        </span>
                    </div>
                    @endif

                    <div class="mt-4 pt-4 border-t flex gap-2">
                        <a href="{{ route(Route::currentRouteNamed() . '.activities.edit', $activity) }}" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Edit
                        </a>
                        <form action="{{ route(Route::currentRouteNamed() . '.activities.destroy', $activity) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700">
                                Delete
                            </button>
                        </form>
                        @if(!$activity->is_done)
                        <button type="button" onclick="markDone({{ $activity->id }})" class="px-3 py-1.5 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Mark Done
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Details</h2>
                    
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm text-gray-500">Due Date</dt>
                            <dd class="text-gray-800">
                                @if($activity->isOverdue() && !$activity->is_done)
                                    <span class="text-red-600 font-medium">
                                        {{ $activity->due_date->format('M d, Y h:i A') }} (Overdue)
                                    </span>
                                @else
                                    {{ $activity->due_date->format('M d, Y h:i A') }}
                                @endif
                            </dd>
                        </div>
                        @if($activity->duration_minutes)
                        <div>
                            <dt class="text-sm text-gray-500">Duration</dt>
                            <dd class="text-gray-800">{{ $activity->duration_minutes }} minutes</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                @if($activity->contact)
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Contact</h2>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-semibold">
                            {{ strtoupper(substr($activity->contact->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $activity->contact->name }}</p>
                            <p class="text-xs text-gray-500">{{ $activity->contact->email }}</p>
                        </div>
                    </div>
                </div>
                @endif

                @if($activity->deal)
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Related Deal</h2>
                    <a href="{{ route(Route::currentRouteNamed() . '.deals.show', $activity->deal) }}" class="block">
                        <p class="font-medium text-blue-600 hover:underline">{{ $activity->deal->title }}</p>
                        <span class="text-xs px-2 py-1 bg-gray-100 rounded mt-1 inline-block">{{ $activity->deal->stage }}</span>
                    </a>
                </div>
                @endif

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Assigned To</h2>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 font-semibold text-sm">
                            {{ strtoupper(substr($activity->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $activity->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $activity->user->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function markDone(activityId) {
            fetch(`/activities/${activityId}/done`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    </script>
    @endpush
</x-app-layout>
