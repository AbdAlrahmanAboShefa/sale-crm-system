<?php

namespace App\Services\Payment;

use App\Models\PaymentIdempotencyKey;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class IdempotencyService
{
    /**
     * Cache lock timeout in seconds.
     */
    protected int $lockTimeout = 30;

    /**
     * Idempotency key expiration in hours.
     */
    protected int $expirationHours = 24;

    /**
     * Process an idempotent request.
     *
     * Returns cached response if key exists, otherwise executes callback and caches result.
     */
    public function handle(Request $request, string $key, callable $callback): JsonResponse
    {
        if (empty($key)) {
            return $callback();
        }

        $requestHash = $this->generateRequestHash($request);
        $tenantId = $request->user()->tenant->id ?? null;

        // Check for existing idempotency record
        $record = PaymentIdempotencyKey::where('key', $key)
            ->where('tenant_id', $tenantId)
            ->first();

        // If record exists and has a stored response, return it
        if ($record && $record->response && !$record->isExpired()) {
            return response()->json(
                $record->response,
                $record->response_code ?? 200,
                ['Idempotency-Key' => $key]
            );
        }

        // If record is locked, another request is processing - return 409 Conflict
        if ($record && $record->isLocked()) {
            return response()->json([
                'error' => 'Request is currently being processed. Please wait and try again.',
                'retry_after' => 5,
            ], 409, ['Idempotency-Key' => $key, 'Retry-After' => 5]);
        }

        // Create or update the idempotency record
        if (!$record) {
            $record = PaymentIdempotencyKey::create([
                'key' => $key,
                'tenant_id' => $tenantId,
                'endpoint' => $request->path(),
                'request_hash' => $requestHash,
                'expires_at' => now()->addHours($this->expirationHours),
            ]);

            // FIX: Lock immediately after create to close the race condition window
            // where two concurrent requests could both pass the isLocked() check above.
            $record->lock();
        } else {
            // Verify request hasn't changed (different hash = different request)
            if ($record->request_hash !== $requestHash) {
                return response()->json([
                    'error' => 'Idempotency key was used for a different request. Please use a new key.',
                ], 400, ['Idempotency-Key' => $key]);
            }

            // Refresh the lock
            $record->lock();
        }

        try {
            // Execute the actual payment logic
            $response = $callback();

            // Cache the response
            $responseData = json_decode($response->getContent(), true);
            $record->storeResponse($responseData, $response->getStatusCode());

            // Add idempotency key to response headers
            $response->headers->set('Idempotency-Key', $key);

            return $response;
        } catch (\Exception $e) {
            // Unlock on error so it can be retried
            $record->unlock();

            throw $e;
        }
    }

    /**
     * Generate a hash of the request for validation.
     */
    protected function generateRequestHash(Request $request): string
    {
        $data = $request->except(['idempotency_key']);
        ksort($data);

        return hash('sha256', json_encode($data));
    }

    /**
     * Clean up expired idempotency keys.
     */
    public function cleanup(): int
    {
        return PaymentIdempotencyKey::where('expires_at', '<', now())->delete();
    }
}
