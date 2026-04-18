<?php

namespace App\Services\Payment;

use App\Contracts\PaymentGatewayInterface;
use App\Dto\PaymentGatewayConfig;
use App\Services\Payment\Gateways\StripeGateway;
use InvalidArgumentException;

/**
 * PaymentManager - Abstract layer for multi-gateway payment support.
 *
 * Manages multiple payment gateway instances and provides a unified interface
 * for payment operations across all gateways.
 *
 * Usage:
 *   $manager = app(PaymentManager::class);
 *   $gateway = $manager->gateway('stripe');
 *   $result = $gateway->createPaymentIntent(...);
 *
 * Or use the default gateway:
 *   $manager->createPaymentIntent(...); // Uses default gateway
 */
class PaymentManager
{
    /**
     * Registered gateway instances.
     *
     * @var array<string, PaymentGatewayInterface>
     */
    protected array $gateways = [];

    /**
     * The default gateway to use.
     */
    protected string $defaultGateway;

    /**
     * Gateway configuration from config/services.php.
     */
    protected array $config;

    /**
     * All supported gateway names (registered in resolve()).
     */
    protected array $supportedGateways = ['stripe'];

    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->defaultGateway = $config['default'] ?? 'stripe';
    }

    /**
     * Get a gateway instance by name.
     *
     * @throws InvalidArgumentException
     */
    public function gateway(string $name = null): PaymentGatewayInterface
    {
        $name = $name ?? $this->defaultGateway;

        if (!isset($this->gateways[$name])) {
            $this->gateways[$name] = $this->resolve($name);
        }

        return $this->gateways[$name];
    }

    /**
     * Resolve a gateway instance from the container or create it.
     */
    protected function resolve(string $name): PaymentGatewayInterface
    {
        return match ($name) {
            'stripe' => $this->createStripeGateway(),
            // Add new gateways here:
            // 'paypal' => $this->createPayPalGateway(),
            // 'mollie' => $this->createMollieGateway(),
            // 'razorpay' => $this->createRazorpayGateway(),
            default => throw new InvalidArgumentException("Unsupported payment gateway: {$name}"),
        };
    }

    /**
     * Create and configure a Stripe gateway instance.
     */
    protected function createStripeGateway(): StripeGateway
    {
        $gateway = new StripeGateway();
        $gateway->configure(new PaymentGatewayConfig(
            publicKey: $this->config['stripe']['public_key'] ?? '',
            secretKey: $this->config['stripe']['secret_key'] ?? '',
            webhookSecret: $this->config['stripe']['webhook_secret'] ?? '',
        ));

        return $gateway;
    }

    /**
     * Get all available gateway names.
     * FIX: Derived dynamically from config keys (excluding 'default') intersected
     * with supported gateways, so adding a new gateway in services.php + resolve()
     * is enough — no need to update this method manually.
     *
     * @return array<string>
     */
    public function getAvailableGateways(): array
    {
        $configuredGateways = array_filter(
            array_keys($this->config),
            fn($key) => $key !== 'default' && is_array($this->config[$key])
        );

        return array_values(
            array_intersect(array_values($configuredGateways), $this->supportedGateways)
        );
    }

    /**
     * Get the default gateway name.
     */
    public function getDefaultGateway(): string
    {
        return $this->defaultGateway;
    }

    /**
     * Check if a gateway is available.
     */
    public function hasGateway(string $name): bool
    {
        return in_array($name, $this->getAvailableGateways());
    }

    /**
     * Magic method to proxy calls to the default gateway.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->gateway()->$method(...$parameters);
    }
}
