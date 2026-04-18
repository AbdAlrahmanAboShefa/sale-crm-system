<?php

namespace App\Services\Payment\Gateways;

use App\Contracts\PaymentGatewayInterface;
use App\Dto\PaymentGatewayConfig;
use App\Dto\SubscriptionResult;
use App\Dto\WebhookPayload;
use App\Models\PaymentMethod;
use App\Models\PaymentTransaction;
use App\Models\Tenant;
use Illuminate\Support\Facades\Log;

/**
 * Abstract base class for all payment gateways.
 * Provides common functionality and enforces the gateway contract.
 */
abstract class AbstractPaymentGateway implements PaymentGatewayInterface
{
    protected ?PaymentGatewayConfig $config = null;

    public function configure(PaymentGatewayConfig $config): void
    {
        $this->config = $config;
    }

    public function getConfig(): ?PaymentGatewayConfig
    {
        return $this->config;
    }

    /**
     * Log a gateway event for auditing.
     */
    protected function logEvent(string $level, string $message, array $context = []): void
    {
        Log::channel('payment')->$level(
            "[{$this->getName()}] {$message}",
            $context
        );
    }

    /**
     * Record a payment transaction in the database.
     */
    protected function recordTransaction(
        Tenant $tenant,
        string $type,
        string $status,
        int|float $amount,
        string $currency,
        string $transactionId,
        ?PaymentMethod $paymentMethod = null,
        ?array $metadata = null,
        ?string $paidAt = null
    ): PaymentTransaction {
        return PaymentTransaction::create([
            'tenant_id' => $tenant->id,
            'gateway' => $this->getName(),
            'transaction_id' => $transactionId,
            'type' => $type,
            'status' => $status,
            'amount' => $amount,
            'currency' => $currency,
            'payment_method_id' => $paymentMethod?->id,
            'metadata' => $metadata,
            'paid_at' => $paidAt,
        ]);
    }

    /**
     * Format amount for display (cents to dollars).
     */
    protected function formatAmount(int $amountCents, string $currency = 'USD'): string
    {
        $amount = $amountCents / 100;
        $symbol = $this->getCurrencySymbol($currency);
        return "{$symbol}" . number_format($amount, 2);
    }

    /**
     * Get currency symbol for display.
     */
    protected function getCurrencySymbol(string $currency): string
    {
        return match (strtoupper($currency)) {
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'SAR' => 'ر.س',
            'AED' => 'د.إ',
            default => $currency . ' ',
        };
    }

    // These methods must be implemented by concrete gateways:
    abstract public function createPaymentIntent(
        int $amount,
        string $currency,
        string $tenantId,
        string $description,
        array $metadata = []
    ): array;

    abstract public function confirmPaymentIntent(string $paymentIntentId): array;

    abstract public function createSubscription(
        string $tenantId,
        string $priceId,
        array $paymentMethodData,
        array $metadata = []
    ): SubscriptionResult;

    abstract public function cancelSubscription(string $subscriptionId, bool $atPeriodEnd = true): bool;

    abstract public function getSubscription(string $subscriptionId): ?array;

    abstract public function createBillingPortalUrl(string $customerId): ?string;

    abstract public function handleWebhook(WebhookPayload $payload): void;

    abstract public function validateWebhookSignature(WebhookPayload $payload): bool;

    abstract public function refundPayment(string $paymentId, ?int $amount = null): array;

    abstract public function getPaymentMethod(string $paymentMethodId): ?array;
}
