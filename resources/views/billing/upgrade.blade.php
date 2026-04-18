<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.billing.title', ['default' => 'Upgrade Your Plan']) }} | SalesFlow CRM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --font-display: 'Bricolage Grotesque', sans-serif;
            --font-body: 'Plus Jakarta Sans', sans-serif;
            --font-arabic: 'Tajawal', sans-serif;
        }

        body {
            font-family: var(--font-body);
            background: #0a0a0f;
            color: #e8e8ed;
        }

        [dir="rtl"] body {
            font-family: var(--font-arabic);
        }

        .font-display { font-family: var(--font-display); }

        /* Animated background */
        .bg-mesh {
            position: fixed;
            inset: 0;
            z-index: -1;
            background:
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(120, 119, 198, 0.15), transparent),
                radial-gradient(ellipse 60% 40% at 80% 100%, rgba(236, 72, 153, 0.08), transparent),
                radial-gradient(ellipse 50% 30% at 0% 50%, rgba(59, 130, 246, 0.06), transparent);
            animation: meshShift 20s ease-in-out infinite alternate;
        }

        @keyframes meshShift {
            0% { opacity: 0.8; }
            50% { opacity: 1; }
            100% { opacity: 0.8; }
        }

        /* Glass morphism cards */
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .glass-card:hover {
            border-color: rgba(255, 255, 255, 0.15);
            box-shadow: 0 12px 48px rgba(0, 0, 0, 0.4);
        }

        /* Plan cards */
        .plan-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .plan-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, transparent 0%, rgba(255,255,255,0.02) 50%, transparent 100%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .plan-card:hover::before {
            opacity: 1;
        }

        .plan-card:hover {
            transform: translateY(-8px) scale(1.02);
        }

        .plan-card.popular {
            border-color: rgba(168, 85, 247, 0.5);
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.08) 0%, rgba(236, 72, 153, 0.05) 100%);
        }

        .plan-card.popular::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, #a855f7, #ec4899, #a855f7);
            background-size: 200% 100%;
            animation: gradientFlow 3s ease infinite;
        }

        @keyframes gradientFlow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* CTA buttons */
        .btn-primary {
            background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #ec4899 0%, #a855f7 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .btn-primary:hover::before {
            opacity: 1;
        }

        .btn-primary span {
            position: relative;
            z-index: 1;
        }

        .btn-primary:hover {
            box-shadow: 0 8px 32px rgba(168, 85, 247, 0.4);
            transform: translateY(-2px);
        }

        /* Payment method tabs */
        .payment-tab {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .payment-tab.active {
            background: rgba(168, 85, 247, 0.15);
            border-color: rgba(168, 85, 247, 0.5);
        }

        .payment-tab:hover:not(.active) {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.2);
        }

        /* Input styling */
        .input-field {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .input-field:focus {
            border-color: rgba(168, 85, 247, 0.5);
            box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.15);
            outline: none;
        }

        /* Stripe element container */
        #stripe-element {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 12px 16px;
            border-radius: 8px;
            min-height: 48px;
        }

        #stripe-element.StripeElement--focus {
            border-color: rgba(168, 85, 247, 0.5);
            box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.15);
        }

        /* Toggle switch */
        .toggle-switch {
            position: relative;
            width: 52px;
            height: 28px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 14px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .toggle-switch.active {
            background: linear-gradient(135deg, #a855f7, #ec4899);
        }

        .toggle-switch::after {
            content: '';
            position: absolute;
            top: 3px;
            inset-inline-start: 3px;
            width: 22px;
            height: 22px;
            background: white;
            border-radius: 50%;
            transition: transform 0.3s ease;
        }

        [dir="rtl"] .toggle-switch::after {
            transform: translateX(0);
        }

        .toggle-switch.active::after {
            transform: translateX(24px);
        }

        /* Transaction rows */
        .transaction-row:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        /* Loading spinner */
        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Success/error toast */
        .toast {
            animation: slideIn 0.4s ease, fadeOut 0.4s ease 3.6s;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }

        /* Badge styles */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success { background: rgba(34, 197, 94, 0.15); color: #4ade80; }
        .badge-warning { background: rgba(251, 191, 36, 0.15); color: #fbbf24; }
        .badge-danger { background: rgba(239, 68, 68, 0.15); color: #f87171; }
        .badge-info { background: rgba(59, 130, 246, 0.15); color: #60a5fa; }

        /* Responsive grid for plans */
        @media (min-width: 1024px) {
            .plans-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (min-width: 768px) and (max-width: 1023px) {
            .plans-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="bg-mesh"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        {{-- Toast container --}}
        <div id="toast-container" class="fixed top-6 inset-inline-end-6 z-50 flex flex-col gap-3"></div>

        {{-- Header --}}
        <div class="text-center mb-16">
            <div class="inline-flex items-center gap-2 mb-4">
                <i class="fas fa-crown text-amber-400 text-2xl"></i>
                <h1 class="font-display text-4xl sm:text-5xl font-bold bg-gradient-to-r from-purple-400 via-pink-400 to-purple-400 bg-clip-text text-transparent">
                    {{ __('messages.billing.title', ['default' => 'Upgrade Your Plan']) }}
                </h1>
            </div>
            <p class="text-lg text-gray-400 max-w-2xl mx-auto">
                {{ __('messages.billing.subtitle', ['default' => 'Choose the perfect plan for your team. Scale as you grow.']) }}
            </p>

            @if(session('error'))
            <div class="mt-6 inline-flex items-center gap-3 px-6 py-4 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400">
                <i class="fas fa-exclamation-triangle"></i>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            @if(session('success'))
            <div class="mt-6 inline-flex items-center gap-3 px-6 py-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif
        </div>

        {{-- Current Plan Status --}}
        <div class="glass-card rounded-2xl p-6 mb-12">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center">
                        <i class="fas fa-box text-purple-400"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">{{ __('messages.billing.current_plan', ['default' => 'Current Plan']) }}</p>
                        <p class="text-xl font-semibold capitalize">{{ $tenant->plan }}</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-4">
                    @if($tenant->isOnTrial())
                    <span class="badge badge-warning">
                        <i class="fas fa-clock"></i>
                        {{ __('messages.billing.trial_ends', ['default' => 'Trial ends']) }}: {{ $tenant->trial_ends_at->format('M d, Y') }}
                    </span>
                    @endif

                    @if($billingPortalUrl)
                    <a href="{{ $billingPortalUrl }}" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg glass-card hover:border-purple-500/50 transition-colors text-sm">
                        <i class="fas fa-receipt"></i>
                        {{ __('messages.billing.manage_billing', ['default' => 'Manage Billing']) }}
                    </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Billing Cycle Toggle --}}
        <div class="flex items-center justify-center gap-4 mb-12">
            <span class="text-gray-400" id="monthly-label">{{ __('messages.billing.monthly', ['default' => 'Monthly']) }}</span>
            <div class="toggle-switch" id="billing-toggle" role="switch" aria-checked="false" tabindex="0"></div>
            <span class="text-gray-400" id="yearly-label">{{ __('messages.billing.yearly', ['default' => 'Yearly']) }}</span>
            <span class="badge badge-success ms-2">
                {{ __('messages.billing.save_20', ['default' => 'Save 20%']) }}
            </span>
        </div>

        {{-- Plans Grid --}}
        @php
            $plans = [
                'free' => [
                    'name' => 'Free',
                    'monthly_price' => 0,
                    'yearly_price' => 0,
                    'features' => ['3 users', '50 contacts', 'Basic features', 'Email support'],
                    'limits' => ['users' => 3, 'contacts' => 50],
                    'icon' => 'fa-box',
                    'color' => 'gray',
                ],
                'basic' => [
                    'name' => 'Basic',
                    'monthly_price' => 29,
                    'yearly_price' => 290,
                    'features' => ['10 users', '500 contacts', 'All core features', 'Priority support'],
                    'limits' => ['users' => 10, 'contacts' => 500],
                    'icon' => 'fa-rocket',
                    'color' => 'blue',
                ],
                'pro' => [
                    'name' => 'Pro',
                    'monthly_price' => 79,
                    'yearly_price' => 790,
                    'features' => ['25 users', 'Unlimited contacts', 'All features', 'Priority support', 'Advanced analytics', 'Custom integrations'],
                    'limits' => ['users' => 25, 'contacts' => PHP_INT_MAX],
                    'icon' => 'fa-gem',
                    'color' => 'purple',
                    'popular' => true,
                ],
                'enterprise' => [
                    'name' => 'Enterprise',
                    'monthly_price' => 199,
                    'yearly_price' => 1990,
                    'features' => ['Unlimited users', 'Unlimited contacts', 'All features', 'Dedicated support', 'Custom SLA', 'SSO & SAML', 'Audit logs'],
                    'limits' => ['users' => PHP_INT_MAX, 'contacts' => PHP_INT_MAX],
                    'icon' => 'fa-building',
                    'color' => 'amber',
                ],
            ];
            $currentPlan = $tenant->plan ?? 'free';
        @endphp

        <div class="plans-grid grid gap-6 mb-16">
            @foreach($plans as $planKey => $plan)
            <div class="plan-card glass-card rounded-2xl p-6 {{ ($plan['popular'] ?? false) ? 'popular' : '' }}"
                 data-plan="{{ $planKey }}"
                 data-monthly="{{ $plan['monthly_price'] }}"
                 data-yearly="{{ $plan['yearly_price'] }}">

                @if(($plan['popular'] ?? false))
                <div class="absolute top-0 inset-inline-end-0 bg-gradient-to-r from-purple-500 to-pink-500 text-white text-xs font-bold px-4 py-1 rounded-bl-xl rounded-tr-xl">
                    <i class="fas fa-star me-1"></i> MOST POPULAR
                </div>
                @endif

                @if($currentPlan === $planKey)
                <div class="absolute top-0 inset-inline-start-0 bg-emerald-500 text-white text-xs font-bold px-4 py-1 rounded-br-xl rounded-tl-xl">
                    <i class="fas fa-check me-1"></i> CURRENT
                </div>
                @endif

                <div class="{{ ($currentPlan === $planKey || ($plan['popular'] ?? false)) ? 'pt-12' : '' }}">
                    {{-- Plan icon & name --}}
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center">
                            <i class="fas {{ $plan['icon'] }} text-purple-400"></i>
                        </div>
                        <h3 class="font-display text-xl font-bold">{{ $plan['name'] }}</h3>
                    </div>

                    {{-- Price --}}
                    <div class="mb-6">
                        <div class="flex items-baseline gap-1">
                            <span class="text-4xl font-bold price-display">${{ number_format($plan['monthly_price']) }}</span>
                            <span class="text-gray-400">/{{ __('messages.billing.month_short', ['default' => 'mo']) }}</span>
                        </div>
                        <p class="text-sm text-gray-500 yearly-info hidden">
                            {{ __('messages.billing.yearly_note', ['default' => 'Billed annually']) }}
                        </p>
                    </div>

                    {{-- Features --}}
                    <ul class="space-y-3 mb-8">
                        @foreach($plan['features'] as $feature)
                        <li class="flex items-start gap-2 text-sm text-gray-300">
                            <i class="fas fa-check text-emerald-400 mt-1 flex-shrink-0"></i>
                            <span>{{ $feature }}</span>
                        </li>
                        @endforeach
                    </ul>

                    {{-- CTA Button --}}
                    @if($currentPlan === $planKey)
                    <button disabled class="w-full py-3 px-4 rounded-xl font-semibold bg-white/5 text-gray-500 cursor-not-allowed">
                        {{ __('messages.billing.current_plan_btn', ['default' => 'Current Plan']) }}
                    </button>
                    @else
                    <button onclick="selectPlan('{{ $planKey }}', {{ $plan['monthly_price'] }}, {{ $plan['yearly_price'] }})"
                            class="btn-primary w-full py-3 px-4 rounded-xl font-semibold text-white {{ ($plan['popular'] ?? false) ? '' : 'bg-white/10 hover:bg-white/20' }}">
                        <span>{{ $plan['monthly_price'] > 0 ? __('messages.billing.upgrade_btn', ['default' => 'Upgrade Now']) : __('messages.billing.downgrade_btn', ['default' => 'Downgrade']) }}</span>
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Payment Modal --}}
        <div id="payment-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" style="background: rgba(0,0,0,0.8); backdrop-filter: blur(8px);">
            <div class="glass-card rounded-2xl w-full max-w-lg p-8 max-h-[90vh] overflow-y-auto" role="dialog" aria-modal="true">
                {{-- Modal header --}}
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="font-display text-2xl font-bold" id="modal-title">Complete Payment</h3>
                        <p class="text-gray-400 text-sm mt-1">Selected plan: <span class="text-purple-400 font-semibold capitalize" id="selected-plan-name"></span></p>
                    </div>
                    <button onclick="closeModal()" class="w-10 h-10 rounded-lg glass-card flex items-center justify-center hover:bg-white/10 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                {{-- Payment method tabs --}}
                <div class="flex gap-3 mb-6">
                    <div class="payment-tab active flex-1 glass-card rounded-xl p-4 text-center" data-gateway="stripe" onclick="switchGateway('stripe')">
                        <i class="fab fa-stripe text-2xl text-blue-400 mb-2"></i>
                        <p class="text-sm font-medium">Credit Card</p>
                    </div>
                    <div class="payment-tab flex-1 glass-card rounded-xl p-4 text-center opacity-50 cursor-not-allowed" data-gateway="paypal">
                        <i class="fab fa-paypal text-2xl text-blue-300 mb-2"></i>
                        <p class="text-sm font-medium">PayPal</p>
                        <span class="text-xs text-gray-500 mt-1">Coming soon</span>
                    </div>
                </div>

                {{-- Stripe payment form --}}
                <div id="stripe-payment">
                    {{-- Amount summary --}}
                    <div class="glass-card rounded-xl p-4 mb-6 flex items-center justify-between">
                        <span class="text-gray-400">{{ __('messages.billing.amount', ['default' => 'Amount']) }}</span>
                        <span class="text-2xl font-bold" id="payment-amount">$0</span>
                    </div>

                    {{-- Billing cycle buttons --}}
                    <div class="mb-4">
                        <label class="text-sm text-gray-400 mb-2 block">{{ __('messages.billing.billing_cycle', ['default' => 'Billing Cycle']) }}</label>
                        <div class="flex gap-2">
                            <button type="button" class="billing-cycle-btn flex-1 py-2 px-4 rounded-lg glass-card text-sm active" data-cycle="monthly">Monthly</button>
                            <button type="button" class="billing-cycle-btn flex-1 py-2 px-4 rounded-lg glass-card text-sm" data-cycle="yearly">Yearly</button>
                        </div>
                    </div>

                    {{-- Card element --}}
                    <div class="mb-6">
                        <label class="text-sm text-gray-400 mb-2 block">{{ __('messages.billing.card_details', ['default' => 'Card Details']) }}</label>
                        <div id="stripe-element"></div>
                        <p id="card-errors" class="text-red-400 text-sm mt-2 hidden" role="alert"></p>
                    </div>

                    {{-- Submit button --}}
                    <button id="submit-payment" class="btn-primary w-full py-4 rounded-xl font-semibold text-white text-lg">
                        <span class="flex items-center justify-center gap-2">
                            <i class="fas fa-lock"></i>
                            <span id="submit-text">Pay</span>
                        </span>
                    </button>

                    <p class="text-center text-xs text-gray-500 mt-4">
                        <i class="fas fa-shield-alt me-1"></i>
                        {{ __('messages.billing.secure_payment', ['default' => 'Secure payment powered by Stripe']) }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Transaction History --}}
        @if(isset($transactions) && $transactions->count() > 0)
        <div class="glass-card rounded-2xl p-6">
            <h3 class="font-display text-xl font-bold mb-6 flex items-center gap-3">
                <i class="fas fa-receipt text-purple-400"></i>
                {{ __('messages.billing.transaction_history', ['default' => 'Transaction History']) }}
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-sm text-gray-400 border-b border-white/10">
                            <th class="pb-3 font-medium">{{ __('messages.billing.date', ['default' => 'Date']) }}</th>
                            <th class="pb-3 font-medium">{{ __('messages.billing.description', ['default' => 'Description']) }}</th>
                            <th class="pb-3 font-medium">{{ __('messages.billing.amount', ['default' => 'Amount']) }}</th>
                            <th class="pb-3 font-medium">{{ __('messages.billing.status', ['default' => 'Status']) }}</th>
                            <th class="pb-3 font-medium">{{ __('messages.billing.gateway', ['default' => 'Gateway']) }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                        <tr class="transaction-row border-b border-white/5 transition-colors">
                            <td class="py-4 text-sm">
                                {{ $transaction->paid_at?->format('M d, Y') ?? $transaction->created_at->format('M d, Y') }}
                            </td>
                            <td class="py-4 text-sm capitalize">
                                {{ $transaction->type }}
                            </td>
                            <td class="py-4 font-semibold">
                                {{ $transaction->formatted_amount }}
                            </td>
                            <td class="py-4">
                                <span class="badge {{ $transaction->status_badge_class }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td class="py-4">
                                <span class="inline-flex items-center gap-2 text-sm">
                                    <i class="fab fa-{{ $transaction->gateway }} text-lg"></i>
                                    {{ ucfirst($transaction->gateway) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Footer --}}
        <div class="text-center mt-16 pt-8 border-t border-white/10">
            <p class="text-gray-500 text-sm">
                {{ __('messages.billing.need_help', ['default' => 'Need help choosing a plan?']) }}
                <a href="mailto:support@salesflow.app" class="text-purple-400 hover:text-purple-300 underline">
                    {{ __('messages.billing.contact_support', ['default' => 'Contact Support']) }}
                </a>
            </p>
        </div>
    </div>

    <script>
        // ─── State ────────────────────────────────────────────────────────────────
        let isYearly = false;
        let selectedPlan = null;   // { plan, monthlyPrice, yearlyPrice }
        let stripe = null;
        let cardElement = null;
        let currentIdempotencyKey = null;

        function generateIdempotencyKey() {
            if (!currentIdempotencyKey) {
                currentIdempotencyKey = crypto.randomUUID();
            }
            return currentIdempotencyKey;
        }

        // ─── Stripe init ──────────────────────────────────────────────────────────
        const stripeKey = '{{ config('services.payment.stripe.public_key') }}';
        if (stripeKey) {
            stripe = Stripe(stripeKey);
        }

        // ─── Billing cycle toggle ─────────────────────────────────────────────────
        const toggle = document.getElementById('billing-toggle');
        toggle.addEventListener('click', () => {
            isYearly = !isYearly;
            toggle.classList.toggle('active', isYearly);
            toggle.setAttribute('aria-checked', isYearly);
            updatePrices();
        });
        toggle.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggle.click(); }
        });

        function updatePrices() {
            document.querySelectorAll('.plan-card').forEach(card => {
                const monthly = parseFloat(card.dataset.monthly);
                const yearly  = parseFloat(card.dataset.yearly);
                const priceDisplay = card.querySelector('.price-display');
                const yearlyInfo   = card.querySelector('.yearly-info');

                if (isYearly && yearly > 0) {
                    priceDisplay.textContent = '$' + Math.round(yearly / 12);
                    yearlyInfo.classList.remove('hidden');
                } else {
                    priceDisplay.textContent = '$' + monthly;
                    yearlyInfo.classList.add('hidden');
                }
            });

            // Keep modal billing-cycle buttons in sync
            document.querySelectorAll('.billing-cycle-btn').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.cycle === (isYearly ? 'yearly' : 'monthly'));
            });

            // Update modal amount if open
            if (selectedPlan) updateModalAmount();
        }

        function updateModalAmount() {
            const price = isYearly ? selectedPlan.yearlyPrice : selectedPlan.monthlyPrice;
            document.getElementById('payment-amount').textContent = '$' + price;
            document.getElementById('submit-text').textContent = price > 0 ? `Pay $${price}` : 'Continue';
        }

        // ─── Plan selection ───────────────────────────────────────────────────────
        // FIX: accept both monthly and yearly price so we can show the right amount
        function selectPlan(plan, monthlyPrice, yearlyPrice) {
            selectedPlan = { plan, monthlyPrice, yearlyPrice };

            const planNames = { free: 'Free', basic: 'Basic', pro: 'Pro', enterprise: 'Enterprise' };
            document.getElementById('selected-plan-name').textContent = planNames[plan];

            updateModalAmount();

            const modal = document.getElementById('payment-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            if (stripe && !cardElement) initStripeElement();
        }

        function closeModal() {
            document.getElementById('payment-modal').classList.add('hidden');
            document.getElementById('payment-modal').classList.remove('flex');
        }

        document.getElementById('payment-modal').addEventListener('click', (e) => {
            if (e.target === e.currentTarget) closeModal();
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeModal();
        });

        // ─── Stripe Elements ──────────────────────────────────────────────────────
        function initStripeElement() {
            if (!stripe) return;

            const elements = stripe.elements();
            cardElement = elements.create('card', {
                style: {
                    base: {
                        color: '#e8e8ed',
                        fontFamily: "'Plus Jakarta Sans', sans-serif",
                        fontSmoothing: 'antialiased',
                        fontSize: '16px',
                        '::placeholder': { color: '#6b7280' }
                    },
                    invalid: { color: '#f87171' }
                }
            });
            cardElement.mount('#stripe-element');

            cardElement.on('change', (event) => {
                const errorEl = document.getElementById('card-errors');
                if (event.error) {
                    errorEl.textContent = event.error.message;
                    errorEl.classList.remove('hidden');
                } else {
                    errorEl.classList.add('hidden');
                }
            });
        }

        function switchGateway(gateway) {
            if (gateway === 'paypal') return;
            document.querySelectorAll('.payment-tab').forEach(tab => {
                tab.classList.toggle('active', tab.dataset.gateway === gateway);
            });
        }

        // ─── Billing cycle buttons inside modal ───────────────────────────────────
        document.querySelectorAll('.billing-cycle-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.billing-cycle-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                isYearly = btn.dataset.cycle === 'yearly';
                updatePrices();
            });
        });

        // ─── Payment submission ───────────────────────────────────────────────────
        //
        // FIXED FLOW (was broken before):
        //
        // OLD (wrong):
        //   1. createPaymentIntent (one-time) → confirmCardPayment → checkout (subscription)
        //   This caused 400 Bad Request because the PaymentIntent was not attached to
        //   a subscription and confirmCardPayment needs a properly set-up PI.
        //
        // NEW (correct):
        //   1. stripe.createPaymentMethod() — tokenize card client-side, get pm_xxx id
        //   2. POST /billing/checkout — send pm_xxx + plan to server
        //   3. Cashier creates the subscription and handles the payment internally
        //   No confirmCardPayment needed for a standard subscription flow.
        //
        document.getElementById('submit-payment').addEventListener('click', async () => {
            const btn        = document.getElementById('submit-payment');
            const submitText = document.getElementById('submit-text');

            if (!selectedPlan) {
                showToast('Please select a plan first.', 'warning');
                return;
            }

            const price = isYearly ? selectedPlan.yearlyPrice : selectedPlan.monthlyPrice;

            if (price === 0) {
                showToast('Please select a paid plan to continue.', 'warning');
                return;
            }

            if (!stripe || !cardElement) {
                showToast('Payment gateway not configured.', 'error');
                return;
            }

            const idempotencyKey = generateIdempotencyKey();

            // Loading state
            btn.disabled = true;
            btn.classList.add('opacity-75');
            submitText.innerHTML = '<div class="spinner inline-block me-2"></div> Processing...';

            try {
                // Step 1: Tokenize the card → get a PaymentMethod id (pm_xxx)
                const { paymentMethod, error: pmError } = await stripe.createPaymentMethod({
                    type: 'card',
                    card: cardElement,
                    billing_details: {
                        name: '{{ auth()->user()->name ?? "Customer" }}'
                    }
                });

                if (pmError) {
                    throw new Error(pmError.message);
                }

                // Step 2: Send pm_xxx + plan to server — Cashier handles subscription + payment
                const response = await fetch('{{ route('billing.checkout') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        plan: selectedPlan.plan,
                        billing: isYearly ? 'yearly' : 'monthly',
                        payment_method_id: paymentMethod.id,
                        idempotency_key: idempotencyKey,
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.error || 'Subscription failed. Please try again.');
                }

                // Step 3: Handle 3D Secure if required
                // Cashier may return requires_action + client_secret for SCA
                if (data.requires_action && data.payment_intent_client_secret) {
                    const { error: confirmError } = await stripe.confirmCardPayment(
                        data.payment_intent_client_secret
                    );
                    if (confirmError) {
                        throw new Error(confirmError.message);
                    }
                }

                showToast('Subscription activated successfully! 🎉', 'success');
                closeModal();
                currentIdempotencyKey = null;
                setTimeout(() => window.location.reload(), 2000);

            } catch (error) {
                showToast(error.message, 'error');
            } finally {
                btn.disabled = false;
                btn.classList.remove('opacity-75');
                submitText.textContent = `Pay $${price}`;
            }
        });

        // ─── Toast ────────────────────────────────────────────────────────────────
        function showToast(message, type = 'info') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');

            const colors = {
                success: 'bg-emerald-500/20 border-emerald-500/30 text-emerald-400',
                error:   'bg-red-500/20 border-red-500/30 text-red-400',
                warning: 'bg-amber-500/20 border-amber-500/30 text-amber-400',
                info:    'bg-blue-500/20 border-blue-500/30 text-blue-400'
            };
            const icons = {
                success: 'fa-check-circle',
                error:   'fa-times-circle',
                warning: 'fa-exclamation-triangle',
                info:    'fa-info-circle'
            };

            toast.className = `toast glass-card rounded-xl px-5 py-4 flex items-center gap-3 border ${colors[type]}`;
            toast.innerHTML = `
                <i class="fas ${icons[type]} text-lg"></i>
                <span class="text-sm font-medium">${message}</span>
            `;

            container.appendChild(toast);
            setTimeout(() => toast.remove(), 4000);
        }
    </script>
</body>
</html>
