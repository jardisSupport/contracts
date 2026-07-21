<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Messaging;

use JardisSupport\Contract\Connection\ConnectionInterface;
use JardisSupport\Contract\Messaging\Exception\ConsumerException;

/**
 * Consumer interface
 *
 * Defines the contract for message consumers that handle the actual
 * message consumption from specific message brokers
 */
interface ConsumerInterface
{
    /**
     * Start consuming messages from topic/channel/queue
     *
     * @param string $topic Topic, channel, queue, or routing key
     * @param callable $callback Message handler: function(string $message, array $metadata): bool
     * @param array<string, mixed> $options Consumer-specific options
     * @throws ConsumerException
     */
    public function consume(string $topic, callable $callback, array $options = []): void;

    /**
     * Stop consuming messages
     */
    public function stop(): void;

    /**
     * Get the underlying connection
     */
    public function getConnection(): ConnectionInterface;
}
