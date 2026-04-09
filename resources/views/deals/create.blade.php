@extends('layouts.app')
@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">{{ __('messages.deals.new_deal') }}</h1>
            <a href="{{ route($routePrefix . '.deals.index') }}" class="text-gray-600 hover:text-gray-800">
                ← {{ __('messages.common.back') }}
            </a>
        </div>

        <form action="{{ route($routePrefix . '.deals.store') }}" method="POST" class="bg-white rounded-xl shadow-sm p-6">
            @csrf
            @include('deals._form', ['contacts' => $contacts, 'users' => $users])

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t">
                <a href="{{ route($routePrefix . '.deals.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                    {{ __('messages.common.cancel') }}
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    {{ __('messages.deals.new_deal') }}
                </button>
            </div>
        </form>
    </div>
@endsection
