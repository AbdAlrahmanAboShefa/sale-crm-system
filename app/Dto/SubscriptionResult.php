<?php

namespace App\Dto;

readonly class SubscriptionResult
{
    public function __construct(
        public string $id,
        public string $status,
        public string $planId,
        public ?string $currentPeriodStart = null,
        public ?string $currentPeriodEnd = null,
        public ?string $cancelAtPeriodEnd = null,
        public array $metadata = [],
    ) {}
}
