<x-app-layout title="Edit Deal">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Edit Deal</h1>
            <a href="{{ route(Route::currentRouteNamed() . '.deals.index') }}" class="text-gray-600 hover:text-gray-800">
                ← Back
            </a>
        </div>

        <form action="{{ route(Route::currentRouteNamed() . '.deals.update', $deal) }}" method="POST" class="bg-white rounded-xl shadow-sm p-6">
            @csrf
            @method('PUT')
            @include('deals._form', ['contacts' => $contacts, 'users' => $users])

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t">
                <a href="{{ route(Route::currentRouteNamed() . '.deals.show', $deal) }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Update Deal
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
