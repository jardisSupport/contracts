<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Messaging;

use JardisSupport\Contract\Connection\ConnectionInterface;
use JardisSupport\Contract\Messaging\Exception\PublishException;

/**
 * Publisher interface
 *
 * Defines the contract for message publishers that handle the actual
 * message publishing to specific message brokers
 */
interface PublisherInterface
{
    /**
     * Publish a message to the specified topic
     *
     * @param string $topic The topic, channel, or routing key
     * @param string $message The message payload (already serialized)
     * @param array<string, mixed> $options Publisher-specific options
     * @return bool True on success
     * @throws PublishException
     */
    public function publish(string $topic, string $message, array $options = []): bool;

    /**
     * Get the underlying connection
     */
    public function getConnection(): ConnectionInterface;
}
