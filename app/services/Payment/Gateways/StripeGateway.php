<?php

namespace App\Services\Payment\Gateways;

use App\Dto\PaymentGatewayConfig;
use App\Dto\SubscriptionResult;
use App\Dto\WebhookPayload;
use App\Models\PaymentMethod;
use App\Models\Tenant;
use Exception;
use Illuminate\Support\Facades\Cache;
use Stripe\Customer;
use Stripe\Exception\SignatureVerificationException;
use Stripe\PaymentIntent;
use Stripe\Refund;
use Stripe\Stripe;
use Stripe\Subscription;
use Stripe\Webhook;

class StripeGateway extends AbstractPaymentGateway
{
    public function getName(): string
    {
        return 'stripe';
    }

    /**
     * Configure Stripe SDK with API keys.
     */
    public function configure(PaymentGatewayConfig $config): void
    {
        parent::configure($config);
        Stripe::setApiKey($config->secretKey);
        Stripe::setApiVersion('2024-06-20');
    }

    public function createPaymentIntent(
        int $amount,
        string $currency,
        string $tenantId,
        string $description,
        array $metadata = []
    ): array {
        try {
            $intent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => $currency,
                'description' => $description,
                'metadata' => array_merge([
                    'tenant_id' => $tenantId,
                    'gateway' => $this->getName(),
                ], $metadata),
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            $this->logEvent('info', 'Payment intent created', [
                'intent_id' => $intent->id,
                'tenant_id' => $tenantId,
                'amount' => $amount,
                'currency' => $currency,
            ]);

            return [
                'id' => $intent->id,
                'client_secret' => $intent->client_secret,
                'status' => $intent->status,
            ];
        } catch (Exception $e) {
            $this->logEvent('error', 'Failed to create payment intent', [
                'tenant_id' => $tenantId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function confirmPaymentIntent(string $paymentIntentId): array
    {
        try {
            $intent = PaymentIntent::retrieve($paymentIntentId);

            return [
                'id' => $intent->id,
                'status' => $intent->status,
                'amount' => $intent->amount,
                'currency' => $intent->currency,
                'payment_method' => $intent->payment_method,
                'created' => $intent->created,
            ];
        } catch (Exception $e) {
            $this->logEvent('error', 'Failed to retrieve payment intent', [
                'intent_id' => $paymentIntentId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function createSubscription(
        string $tenantId,
        string $priceId,
        array $paymentMethodData,
        array $metadata = []
    ): SubscriptionResult {
        $tenant = Tenant::findOrFail($tenantId);

        // Create or retrieve Stripe customer
        if (!$tenant->stripe_id) {
            $tenant->createAsStripeCustomer([
                'name' => $tenant->name,
                'email' => $tenant->users()->first()?->email,
                'metadata' => [
                    'tenant_id' => $tenant->id,
                    'subdomain' => $tenant->subdomain,
                ],
            ]);
        }

        // Create subscription via Cashier
        $subscription = $tenant->newSubscription('default', $priceId)
            ->create($paymentMethodData['payment_method_id'] ?? 'pm_card_visa', [
                'metadata' => array_merge([
                    'tenant_id' => $tenantId,
                    'plan' => $metadata['plan'] ?? 'default',
                ], $metadata),
            ]);

        $this->logEvent('info', 'Subscription created', [
            'tenant_id' => $tenantId,
            'subscription_id' => $subscription->stripe_id,
            'price_id' => $priceId,
        ]);

        // FIX: current_period_start/end are Unix timestamps (integers), not objects.
        // Using date() to convert them instead of ->toDateTime()->format() which throws an error.
        $stripeSubscription = $subscription->asStripeSubscription();
        $periodStart = $stripeSubscription->current_period_start
            ? date('Y-m-d H:i:s', $stripeSubscription->current_period_start)
            : null;
        $periodEnd = $stripeSubscription->current_period_end
            ? date('Y-m-d H:i:s', $stripeSubscription->current_period_end)
            : null;

        return new SubscriptionResult(
            id: $subscription->stripe_id,
            status: $subscription->stripe_status,
            planId: $priceId,
            currentPeriodStart: $periodStart,
            currentPeriodEnd: $periodEnd,
            cancelAtPeriodEnd: $subscription->ends_at ? '1' : '0',
            metadata: $metadata,
        );
    }

    public function cancelSubscription(string $subscriptionId, bool $atPeriodEnd = true): bool
    {
        try {
            $stripeSubscription = Subscription::retrieve($subscriptionId);

            if ($atPeriodEnd) {
                $stripeSubscription->cancelAtPeriodEnd = true;
                $stripeSubscription->save();
            } else {
                $stripeSubscription->cancelNow();
            }

            $this->logEvent('info', 'Subscription cancelled', [
                'subscription_id' => $subscriptionId,
                'at_period_end' => $atPeriodEnd,
            ]);

            return true;
        } catch (Exception $e) {
            $this->logEvent('error', 'Failed to cancel subscription', [
                'subscription_id' => $subscriptionId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function getSubscription(string $subscriptionId): ?array
    {
        try {
            $subscription = Subscription::retrieve($subscriptionId);

            return [
                'id' => $subscription->id,
                'status' => $subscription->status,
                'customer' => $subscription->customer,
                'plan' => $subscription->plan->id ?? null,
                'current_period_start' => $subscription->current_period_start,
                'current_period_end' => $subscription->current_period_end,
                'cancel_at_period_end' => $subscription->cancel_at_period_end,
                'created' => $subscription->created,
            ];
        } catch (Exception $e) {
            return null;
        }
    }

    public function createBillingPortalUrl(string $customerId): ?string
    {
        try {
            $session = \Stripe\BillingPortal\Session::create([
                'customer' => $customerId,
                'return_url' => route('billing.upgrade'),
            ]);

            return $session->url;
        } catch (Exception $e) {
            $this->logEvent('error', 'Failed to create billing portal URL', [
                'customer_id' => $customerId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    public function handleWebhook(WebhookPayload $payload): void
    {
        $event = Webhook::constructEvent(
            $payload->raw,
            $payload->signature,
            $this->config->webhookSecret
        );

        $this->logEvent('info', 'Webhook event received', [
            'event_type' => $event->type,
            'event_id' => $event->id,
        ]);

        match ($event->type) {
            'invoice.payment_succeeded' => $this->handleInvoicePaymentSucceeded($event),
            'invoice.payment_failed' => $this->handleInvoicePaymentFailed($event),
            'customer.subscription.created' => $this->handleSubscriptionCreated($event),
            'customer.subscription.updated' => $this->handleSubscriptionUpdated($event),
            'customer.subscription.deleted' => $this->handleSubscriptionDeleted($event),
            'payment_intent.succeeded' => $this->handlePaymentIntentSucceeded($event),
            'payment_intent.payment_failed' => $this->handlePaymentIntentFailed($event),
            'charge.refunded' => $this->handleChargeRefunded($event),
            default => $this->logEvent('info', 'Unhandled webhook event', ['event_type' => $event->type]),
        };
    }

    public function validateWebhookSignature(WebhookPayload $payload): bool
    {
        try {
            Webhook::constructEvent(
                $payload->raw,
                $payload->signature,
                $this->config->webhookSecret
            );
            return true;
        } catch (SignatureVerificationException $e) {
            $this->logEvent('error', 'Webhook signature verification failed', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function refundPayment(string $paymentId, ?int $amount = null): array
    {
        try {
            $refundData = ['payment_intent' => $paymentId];
            if ($amount !== null) {
                $refundData['amount'] = $amount;
            }

            $refund = Refund::create($refundData);

            $this->logEvent('info', 'Refund processed', [
                'payment_id' => $paymentId,
                'refund_id' => $refund->id,
                'amount' => $amount ?? 'full',
            ]);

            return [
                'id' => $refund->id,
                'status' => $refund->status,
                'amount' => $refund->amount,
            ];
        } catch (Exception $e) {
            $this->logEvent('error', 'Failed to refund payment', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function getPaymentMethod(string $paymentMethodId): ?array
    {
        try {
            $pm = \Stripe\PaymentMethod::retrieve($paymentMethodId);

            return [
                'id' => $pm->id,
                'type' => $pm->type,
                'card' => $pm->card ? [
                    'brand' => $pm->card->brand,
                    'last4' => $pm->card->last4,
                    'exp_month' => $pm->card->exp_month,
                    'exp_year' => $pm->card->exp_year,
                ] : null,
                'billing_details' => $pm->billing_details,
                'created' => $pm->created,
            ];
        } catch (Exception $e) {
            return null;
        }
    }

    // ---- Webhook Event Handlers ----

    protected function handleInvoicePaymentSucceeded($event): void
    {
        $invoice = $event->data->object;
        $tenant = Tenant::where('stripe_id', $invoice->customer)->first();

        if ($tenant) {
            $this->recordTransaction(
                tenant: $tenant,
                type: 'payment',
                status: 'completed',
                amount: $invoice->amount_paid / 100,
                currency: $invoice->currency,
                transactionId: $invoice->payment_intent ?? $invoice->id,
                paidAt: now()->format('Y-m-d H:i:s'),
                metadata: [
                    'invoice_id' => $invoice->id,
                    'subscription_id' => $invoice->subscription ?? null,
                ]
            );

            $this->logEvent('info', 'Invoice payment succeeded', [
                'tenant_id' => $tenant->id,
                'amount' => $invoice->amount_paid,
            ]);
        }
    }

    protected function handleInvoicePaymentFailed($event): void
    {
        $invoice = $event->data->object;
        $tenant = Tenant::where('stripe_id', $invoice->customer)->first();

        if ($tenant) {
            $this->recordTransaction(
                tenant: $tenant,
                type: 'payment',
                status: 'failed',
                amount: $invoice->amount_due / 100,
                currency: $invoice->currency,
                transactionId: $invoice->id,
                metadata: [
                    'invoice_id' => $invoice->id,
                    'failure_reason' => $invoice->last_payment_error ?? null,
                ]
            );

            $this->logEvent('warning', 'Invoice payment failed', [
                'tenant_id' => $tenant->id,
                'amount' => $invoice->amount_due,
            ]);
        }
    }

    protected function handleSubscriptionCreated($event): void
    {
        $this->logEvent('info', 'Subscription created via webhook', [
            'subscription_id' => $event->data->object->id,
        ]);
    }

    protected function handleSubscriptionUpdated($event): void
    {
        $this->logEvent('info', 'Subscription updated via webhook', [
            'subscription_id' => $event->data->object->id,
        ]);
    }

    protected function handleSubscriptionDeleted($event): void
    {
        $subscription = $event->data->object;
        $tenant = Tenant::where('stripe_id', $subscription->customer)->first();

        if ($tenant) {
            $tenant->update(['plan' => 'free']);
            $this->logEvent('info', 'Subscription deleted, tenant downgraded to free', [
                'tenant_id' => $tenant->id,
            ]);
        }
    }

    protected function handlePaymentIntentSucceeded($event): void
    {
        $intent = $event->data->object;
        $tenantId = $intent->metadata->tenant_id ?? null;

        if ($tenantId) {
            $tenant = Tenant::find($tenantId);
            if ($tenant) {
                $this->recordTransaction(
                    tenant: $tenant,
                    type: 'payment',
                    status: 'completed',
                    amount: $intent->amount / 100,
                    currency: $intent->currency,
                    transactionId: $intent->id,
                    paidAt: now()->format('Y-m-d H:i:s'),
                    metadata: [
                        'description' => $intent->description,
                    ]
                );
            }
        }
    }

    protected function handlePaymentIntentFailed($event): void
    {
        $intent = $event->data->object;
        $tenantId = $intent->metadata->tenant_id ?? null;

        if ($tenantId) {
            $tenant = Tenant::find($tenantId);
            if ($tenant) {
                $this->recordTransaction(
                    tenant: $tenant,
                    type: 'payment',
                    status: 'failed',
                    amount: $intent->amount / 100,
                    currency: $intent->currency,
                    transactionId: $intent->id,
                    metadata: [
                        'error' => $intent->last_payment_error?->message ?? 'Unknown error',
                    ]
                );
            }
        }
    }

    protected function handleChargeRefunded($event): void
    {
        $charge = $event->data->object;
        $this->logEvent('info', 'Charge refunded', [
            'charge_id' => $charge->id,
            'amount' => $charge->amount_refunded,
        ]);
    }
}
