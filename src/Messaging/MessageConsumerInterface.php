<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Messaging;

use JardisSupport\Contract\Messaging\Exception\ConsumerException;

/**
 * Message consumer interface
 *
 * Defines the public API for consuming messages with handlers
 */
interface MessageConsumerInterface
{
    /**
     * Start consuming messages with a handler
     *
     * @param string $topic Topic, channel, queue, or routing key
     * @param MessageHandlerInterface $handler Message handler
     * @param array<string, mixed> $options Consumer options
     * @throws ConsumerException
     */
    public function consume(string $topic, MessageHandlerInterface $handler, array $options = []): void;

    /**
     * Stop consuming messages
     */
    public function stop(): void;
}
