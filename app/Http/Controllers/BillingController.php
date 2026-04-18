<?php

namespace App\Http\Controllers;

use App\Dto\WebhookPayload;
use App\Models\PaymentTransaction;
use App\Services\Payment\IdempotencyService;
use App\Services\Payment\PaymentManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class BillingController extends Controller
{
    public function __construct(
        protected PaymentManager $paymentManager,
        protected IdempotencyService $idempotencyService
    ) {}

    /**
     * Display the billing/upgrade page.
     */
    public function index(Request $request): View
    {
        $tenant = $request->user()->tenant;
        $subscription = null;
        $transactions = collect();
        $billingPortalUrl = null;

        if ($tenant->subscribed('default')) {
            $subscription = $tenant->subscription('default');
        }

        $transactions = PaymentTransaction::where('tenant_id', $tenant->id)
            ->with('paymentMethod')
            ->latest()
            ->take(10)
            ->get();

        if ($tenant->stripe_id) {
            try {
                $billingPortalUrl = $this->paymentManager
                    ->gateway('stripe')
                    ->createBillingPortalUrl($tenant->stripe_id);
            } catch (\Exception $e) {
                Log::warning('Failed to get billing portal URL: ' . $e->getMessage());
            }
        }

        return view('billing.upgrade', compact(
            'tenant',
            'subscription',
            'transactions',
            'billingPortalUrl'
        ));
    }

    /**
     * Create a subscription checkout.
     *
     * Expects a PaymentMethod id (pm_xxx) created client-side via stripe.createPaymentMethod().
     * Cashier handles attaching the payment method and charging the customer.
     */
    public function checkout(Request $request): JsonResponse
    {
        $request->validate([
            'plan'              => 'required|in:basic,pro,enterprise',
            'billing'           => 'nullable|in:monthly,yearly',
            'payment_method_id' => 'required|string|starts_with:pm_',  // must be a real Stripe PM token
            'idempotency_key'   => 'nullable|string|max:255',
        ]);

        $tenant  = $request->user()->tenant;
        $plan    = $request->input('plan');
        $billing = $request->input('billing', 'monthly');

        if ($tenant->plan === $plan && $tenant->subscribed('default')) {
            return response()->json([
                'error' => 'You already have an active subscription to this plan.',
            ], 409);
        }

        $priceId = $this->getPriceId($plan, $billing);

        if (!$priceId) {
            return response()->json([
                'error' => 'Invalid plan or billing cycle selected.',
            ], 400);
        }

        $idempotencyKey = $request->input('idempotency_key');

        return $this->idempotencyService->handle($request, $idempotencyKey, function () use ($tenant, $plan, $billing, $priceId, $request) {
            try {
                $result = $this->paymentManager
                    ->gateway('stripe')
                    ->createSubscription(
                        tenantId: (string) $tenant->id,
                        priceId: $priceId,
                        paymentMethodData: [
                            'payment_method_id' => $request->input('payment_method_id'),
                        ],
                        metadata: [
                            'plan'    => $plan,
                            'billing' => $billing,
                        ]
                    );

                $tenant->update([
                    'plan'          => $plan,
                    'trial_ends_at' => null,
                ]);

                return response()->json([
                    'success'      => true,
                    'message'      => 'Subscription activated successfully!',
                    'subscription' => [
                        'id'     => $result->id,
                        'status' => $result->status,
                    ],
                ]);
            } catch (\Exception $e) {
                Log::error('Checkout failed: ' . $e->getMessage());

                return response()->json([
                    'error'   => 'Payment failed. Please try again or contact support.',
                    'details' => config('app.debug') ? $e->getMessage() : null,
                ], 500);
            }
        });
    }

    /**
     * Create a one-time payment intent.
     */
    public function createPaymentIntent(Request $request): JsonResponse
    {
        $request->validate([
            'amount'          => 'required|integer|min:100',
            'currency'        => 'nullable|string|size:3',
            'description'     => 'nullable|string|max:255',
            'idempotency_key' => 'nullable|string|max:255',
        ]);

        $tenant         = $request->user()->tenant;
        $currency       = $request->input('currency', 'USD');
        $description    = $request->input('description', 'Payment for ' . $tenant->name);
        $idempotencyKey = $request->input('idempotency_key');

        return $this->idempotencyService->handle($request, $idempotencyKey, function () use ($tenant, $currency, $description, $request) {
            try {
                $result = $this->paymentManager
                    ->gateway('stripe')
                    ->createPaymentIntent(
                        amount: $request->integer('amount'),
                        currency: $currency,
                        tenantId: (string) $tenant->id,
                        description: $description,
                        metadata: ['plan' => $tenant->plan]
                    );

                return response()->json($result);
            } catch (\Exception $e) {
                return response()->json([
                    'error'   => 'Failed to create payment intent.',
                    'details' => config('app.debug') ? $e->getMessage() : null,
                ], 500);
            }
        });
    }

    /**
     * Confirm a payment intent.
     */
    public function confirmPayment(Request $request): JsonResponse
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
        ]);

        try {
            $result = $this->paymentManager
                ->gateway('stripe')
                ->confirmPaymentIntent($request->input('payment_intent_id'));

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Failed to confirm payment.',
                'details' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Cancel the current subscription.
     */
    public function cancelSubscription(Request $request): JsonResponse
    {
        $request->validate([
            'idempotency_key' => 'nullable|string|max:255',
        ]);

        $tenant       = $request->user()->tenant;
        $subscription = $tenant->subscription('default');

        if (!$subscription) {
            return response()->json(['error' => 'No active subscription found.'], 404);
        }

        $idempotencyKey = $request->input('idempotency_key');

        return $this->idempotencyService->handle($request, $idempotencyKey, function () use ($subscription, $tenant) {
            try {
                $this->paymentManager
                    ->gateway('stripe')
                    ->cancelSubscription(
                        subscriptionId: $subscription->stripe_id,
                        atPeriodEnd: true
                    );

                $tenant->update(['plan' => 'free']);

                return response()->json([
                    'success' => true,
                    'message' => 'Subscription will be cancelled at the end of the billing period.',
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'error'   => 'Failed to cancel subscription.',
                    'details' => config('app.debug') ? $e->getMessage() : null,
                ], 500);
            }
        });
    }

    /**
     * Handle Stripe webhook events.
     */
    public function webhook(Request $request): Response
    {
        $payload = new WebhookPayload(
            raw: $request->getContent(),
            eventType: $request->header('Stripe-Event-Type') ?? $request->input('type'),
            data: $request->all(),
            signature: $request->header('Stripe-Signature') ?? '',
            headers: $request->headers->all(),
        );

        $gateway = $this->paymentManager->gateway('stripe');

        if (!$gateway->validateWebhookSignature($payload)) {
            Log::warning('Stripe webhook rejected: invalid signature');
            abort(403, 'Invalid webhook signature.');
        }

        try {
            $gateway->handleWebhook($payload);
        } catch (\Exception $e) {
            Log::error('Webhook handling failed: ' . $e->getMessage());
            abort(400, 'Webhook handling failed.');
        }

        return response('Webhook handled', 200);
    }

    /**
     * Get transaction history.
     */
    public function transactions(Request $request): JsonResponse
    {
        $tenant = $request->user()->tenant;

        $transactions = PaymentTransaction::where('tenant_id', $tenant->id)
            ->with('paymentMethod')
            ->latest()
            ->paginate(20);

        return response()->json($transactions);
    }

    /**
     * Get plan pricing and price IDs.
     */
    public function getPlans(): JsonResponse
    {
        return response()->json($this->getPlansConfig());
    }

    protected function getPriceId(string $plan, string $billing): ?string
    {
        return $this->getPlansConfig()[$plan]['price_id'][$billing] ?? null;
    }

    protected function getPlansConfig(): array
    {
        return [
            // 'free' => [
            //     'name' => 'Free',
            //     'monthly_price' => 0,
            //     'yearly_price' => 0,
            //     'price_id' => ['monthly' => null, 'yearly' => null],
            //     'features' => ['3 users', '50 contacts', 'Basic features', 'Email support'],
            //     'limits'   => ['users' => 3, 'contacts' => 50],
            // ],
            'basic' => [
                'name' => 'Basic',
                'monthly_price' => 29,
                'yearly_price' => 290,
                'price_id' => [
                    'monthly' => env('STRIPE_PRICE_BASIC_MONTHLY', 'price_basic_monthly'),
                    'yearly'  => env('STRIPE_PRICE_BASIC_YEARLY',  'price_basic_yearly'),
                ],
                'features' => ['10 users', '500 contacts', 'All core features', 'Priority support'],
                'limits'   => ['users' => 10, 'contacts' => 500],
            ],
            'pro' => [
                'name' => 'Pro',
                'monthly_price' => 79,
                'yearly_price' => 790,
                'price_id' => [
                    'monthly' => env('STRIPE_PRICE_PRO_MONTHLY', 'price_pro_monthly'),
                    'yearly'  => env('STRIPE_PRICE_PRO_YEARLY',  'price_pro_yearly'),
                ],
                'features' => ['25 users', 'Unlimited contacts', 'All features', 'Priority support', 'Advanced analytics', 'Custom integrations'],
                'limits'   => ['users' => 25, 'contacts' => PHP_INT_MAX],
                'popular'  => true,
            ],
            'enterprise' => [
                'name' => 'Enterprise',
                'monthly_price' => 199,
                'yearly_price' => 1990,
                'price_id' => [
                    'monthly' => env('STRIPE_PRICE_ENTERPRISE_MONTHLY', 'price_enterprise_monthly'),
                    'yearly'  => env('STRIPE_PRICE_ENTERPRISE_YEARLY',  'price_enterprise_yearly'),
                ],
                'features' => ['Unlimited users', 'Unlimited contacts', 'All features', 'Dedicated support', 'Custom SLA', 'SSO & SAML', 'Audit logs'],
                'limits'   => ['users' => PHP_INT_MAX, 'contacts' => PHP_INT_MAX],
            ],
        ];
    }
}
