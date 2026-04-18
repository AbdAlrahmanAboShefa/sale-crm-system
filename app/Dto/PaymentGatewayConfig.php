<?php

namespace App\Dto;

readonly class PaymentGatewayConfig
{
    public function __construct(
        public string $publicKey,
        public string $secretKey,
        public string $webhookSecret = '',
        public array $options = [],
    ) {}
}
