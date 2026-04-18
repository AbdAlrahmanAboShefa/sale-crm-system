@extends('layouts.app')
@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-slate-100">{{ isset($deal) ? __('messages.deals.edit_deal') : __('messages.deals.new_deal') }}</h1>
            <a href="{{ route($routePrefix . '.deals.show', $deal ?? 0) }}" class="dark-btn dark-btn-secondary">
                <i class="fas fa-arrow-left"></i>
                {{ __('messages.common.back') }}
            </a>
        </div>

        <form action="{{ route($routePrefix . '.deals.update', $deal) }}" method="POST" class="dark-card p-6">
            @csrf
            @method('PUT')
            @include('deals._form', ['contacts' => $contacts, 'users' => $users])

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-[#2d3748]">
                <a href="{{ route($routePrefix . '.deals.show', $deal) }}" class="dark-btn dark-btn-secondary">
                    {{ __('messages.common.cancel') }}
                </a>
                <button type="submit" class="dark-btn dark-btn-primary">
                    <i class="fas fa-save"></i>
                    {{ __('messages.deals.update_deal') ?? __('messages.common.save') }}
                </button>
            </div>
        </form>
    </div>
@endsection
