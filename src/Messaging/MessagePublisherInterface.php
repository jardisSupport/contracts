<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Messaging;

use JardisSupport\Contract\Messaging\Exception\ConnectionException;
use JardisSupport\Contract\Messaging\Exception\PublishException;

/**
 * Contract for message publishers
 *
 * Defines the public API for publishing messages to message brokers
 */
interface MessagePublisherInterface
{
    /**
     * Publish a message to the specified topic/channel/queue
     *
     * @param string $topic The topic, channel or queue name
     * @param string|object|object|array<mixed> $message The message payload
     *        (strings passed as-is, arrays encoded to JSON)
     * @param array<string, mixed> $options Adapter-specific options
     * @return bool True on success
     * @throws ConnectionException
     * @throws PublishException
     */
    public function publish(string $topic, string|object|array $message, array $options = []): bool;
}
