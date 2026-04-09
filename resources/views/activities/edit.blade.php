<x-app-layout title="{{ __('messages.activities.edit_activity') }}">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">{{ __('messages.activities.edit_activity') }}</h1>
            <a href="{{ route(Route::currentRouteNamed() . '.activities.index') }}" class="text-gray-600 hover:text-gray-800">
                {{ __('messages.common.back') }}
            </a>
        </div>

        <form action="{{ route(Route::currentRouteNamed() . '.activities.update', $activity) }}" method="POST" class="bg-white rounded-xl shadow-sm p-6">
            @csrf
            @method('PUT')
            @include('activities._form', ['contacts' => $contacts, 'deals' => $deals])

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t">
                <a href="{{ route(Route::currentRouteNamed() . '.activities.show', $activity) }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                    {{ __('messages.common.cancel') }}
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    {{ __('messages.activities.update_activity') ?? __('messages.common.save') }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
