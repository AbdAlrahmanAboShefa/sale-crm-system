@extends('layouts.app')
@section('content')
    <div class="dark-card p-6">
        <form action="{{ route($routePrefix . '.activities.store') }}" method="POST">
            @csrf
            @include('activities._form', ['contacts' => $contacts, 'deals' => $deals])

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route($routePrefix . '.activities.index') }}" class="dark-btn dark-btn-secondary">{{ __('messages.common.cancel') }}</a>
                <button type="submit" class="dark-btn dark-btn-primary">
                    <i class="fas fa-save"></i>
                    {{ __('messages.activities.save_activity') ?? 'Save Activity' }}
                </button>
            </div>
        </form>
    </div>
@endsection
