<?php

namespace App\Dto;

readonly class WebhookPayload
{
    public function __construct(
        public string $raw,
        public string $eventType,
        public array $data,
        public string $signature = '',
        public array $headers = [],
    ) {}
}
