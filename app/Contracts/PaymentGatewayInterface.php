<?php

namespace App\Contracts;

use App\Dto\PaymentIntentResult;
use App\Dto\PaymentGatewayConfig;
use App\Dto\SubscriptionResult;
use App\Dto\WebhookPayload;

interface PaymentGatewayInterface
{
    /**
     * Get the gateway identifier (e.g., 'stripe', 'paypal')
     */
    public function getName(): string;

    /**
     * Configure the gateway with API keys and settings
     */
    public function configure(PaymentGatewayConfig $config): void;

    /**
     * Create a payment intent for one-time payments
     *
     * @return array{id: string, client_secret: string, status: string}
     */
    public function createPaymentIntent(
        int $amount,
        string $currency,
        string $tenantId,
        string $description,
        array $metadata = []
    ): array;

    /**
     * Confirm a payment intent
     */
    public function confirmPaymentIntent(string $paymentIntentId): array;

    /**
     * Create a subscription for the tenant
     */
    public function createSubscription(
        string $tenantId,
        string $priceId,
        array $paymentMethodData,
        array $metadata = []
    ): SubscriptionResult;

    /**
     * Cancel a subscription
     */
    public function cancelSubscription(string $subscriptionId, bool $atPeriodEnd = true): bool;

    /**
     * Retrieve subscription details
     */
    public function getSubscription(string $subscriptionId): ?array;

    /**
     * Generate a billing portal URL for managing subscriptions
     */
    public function createBillingPortalUrl(string $customerId): ?string;

    /**
     * Process webhook events from the payment gateway
     */
    public function handleWebhook(WebhookPayload $payload): void;

    /**
     * Validate webhook signature
     */
    public function validateWebhookSignature(WebhookPayload $payload): bool;

    /**
     * Refund a payment
     */
    public function refundPayment(string $paymentId, ?int $amount = null): array;

    /**
     * Retrieve payment method details
     */
    public function getPaymentMethod(string $paymentMethodId): ?array;
}
