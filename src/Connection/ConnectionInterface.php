<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Connection;

/**
 * Base connection interface for all Jardis adapters
 *
 * Defines the minimal lifecycle contract that every connection
 * (Database, Redis, Kafka, RabbitMQ, etc.) must implement.
 * This interface is the common foundation shared across all
 * port packages that define connection-aware contracts.
 */
interface ConnectionInterface
{
    /**
     * Establish the connection
     *
     * Idempotent — multiple calls are safe.
     * If already connected: no-op.
     *
     * @throws \RuntimeException on connection failure
     */
    public function connect(): void;

    /**
     * Close the connection
     *
     * Idempotent — multiple calls are safe.
     */
    public function disconnect(): void;

    /**
     * Check if the connection is active
     */
    public function isConnected(): bool;
}
