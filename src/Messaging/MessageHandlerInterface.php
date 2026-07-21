<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Messaging;

/**
 * Message handler interface
 *
 * Defines the contract for handling consumed messages
 */
interface MessageHandlerInterface
{
    /**
     * Handle received message
     *
     * @param string|array<mixed> $message Deserialized message payload
     * @param array<string, mixed> $metadata Message metadata (headers, timestamp, etc.)
     * @return bool True to acknowledge/commit, false to reject/requeue
     */
    public function handle(string|array $message, array $metadata): bool;
}
