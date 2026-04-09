@extends('layouts.app')
@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">{{ $deal->title }}</h1>
            <div class="flex gap-2">
                <a href="{{ route($routePrefix . '.deals.index') }}" class="text-gray-600 hover:text-gray-800">
                    {{ __('messages.common.back') }}
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">{{ __('messages.deals.deal_details') }}</h2>
                        <span class="px-3 py-1 text-sm rounded-full 
                            @if($deal->stage === 'Won') bg-green-100 text-green-700
                            @elseif($deal->stage === 'Lost') bg-red-100 text-red-700
                            @else bg-blue-100 text-blue-700 @endif">
                            {{ $deal->stage }}
                        </span>
                    </div>

                    <dl class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm text-gray-500">{{ __('messages.deals.value') }}</dt>
                            <dd class="text-lg font-semibold text-gray-800">{{ $deal->currency }} {{ number_format($deal->value, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">{{ __('messages.deals.probability') ?? 'Probability' }}</dt>
                            <dd class="text-lg font-semibold text-gray-800">{{ $deal->probability }}%</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">{{ __('messages.deals.close_date') }}</dt>
                            <dd class="text-gray-800">{{ $deal->expected_close_date?->format('M d, Y') ?? __('messages.common.no_data') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">{{ __('messages.deals.days_in_stage') ?? 'Days in Stage' }}</dt>
                            <dd class="text-gray-800">{{ $deal->daysInStage() }} {{ __('messages.deals.days') ?? 'days' }}</dd>
                        </div>
                        @if($deal->lost_reason)
                        <div class="col-span-2">
                            <dt class="text-sm text-gray-500">{{ __('messages.deals.lost_reason') ?? 'Lost Reason' }}</dt>
                            <dd class="text-gray-800">{{ $deal->lost_reason }}</dd>
                        </div>
                        @endif
                    </dl>

                    <div class="mt-4 pt-4 border-t flex gap-2">
                        <a href="{{ route($routePrefix . '.deals.edit', $deal) }}" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            {{ __('messages.common.edit') }}
                        </a>
                        <form action="{{ route($routePrefix . '.deals.destroy', $deal) }}" method="POST" onsubmit="return confirm(&quot;{{ __('messages.messages.delete_confirm') }}&quot;);">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700">
                                {{ __('messages.common.delete') }}
                            </button>
                        </form>
                    </div>
                </div>

                @if($deal->contact)
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('messages.deals.contact') }}</h2>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-semibold">
                            {{ strtoupper(substr($deal->contact->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $deal->contact->name }}</p>
                            <p class="text-sm text-gray-500">{{ $deal->contact->company ?? __('messages.contacts.no_company') ?? '-' }}</p>
                            <p class="text-sm text-gray-500">{{ $deal->contact->email }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('messages.deals.assigned_to') }}</h2>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 font-semibold text-sm">
                            {{ strtoupper(substr($deal->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $deal->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $deal->user->email }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('messages.deals.pipeline') }}</h2>
                    <p class="text-sm text-gray-500 mb-3">{{ __('messages.deals.expected_value') ?? 'Expected Value' }}: <span class="font-semibold text-gray-800">{{ $deal->currency }} {{ number_format($deal->value * $deal->probability / 100, 2) }}</span></p>
                    
                    <div class="space-y-2">
                        @foreach(['New', 'Contacted', 'Qualified', 'Proposal', 'Negotiation', 'Won', 'Lost'] as $stage)
                            <div class="flex items-center justify-between p-2 rounded {{ $deal->stage === $stage ? 'bg-blue-50 border border-blue-200' : '' }}">
                                <span class="text-sm {{ $deal->stage === $stage ? 'text-blue-700 font-medium' : 'text-gray-600' }}">{{ $stage }}</span>
                                @if($deal->stage === $stage)
                                    <span class="text-xs text-blue-600">{{ __('messages.common.current') ?? 'Current' }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
