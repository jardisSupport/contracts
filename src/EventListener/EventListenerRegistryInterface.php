<?php

declare(strict_types=1);

namespace JardisSupport\Contract\EventListener;

/**
 * Registry for domain event listeners.
 *
 * Fills the gap PSR-14 left open: how to register listeners on a provider.
 * PSR-14 defines EventDispatcherInterface (send) and ListenerProviderInterface (read),
 * but not how listeners are added to a provider.
 *
 * Implementations wrap concrete dispatcher libraries:
 * - jardisadapter/eventdispatcher: wraps ListenerProvider::listen()
 * - symfony/event-dispatcher: wraps EventDispatcher::addListener()
 * - league/event: wraps ListenerAcceptor::subscribeTo()
 *
 * Used by generated EventRouter scaffold classes to register listeners
 * in a framework-agnostic way.
 */
interface EventListenerRegistryInterface
{
    /**
     * Registers a listener for a specific event class.
     *
     * @param class-string $eventClass The fully qualified event class name
     * @param callable $listener The listener to invoke when the event is dispatched
     * @param int $priority Higher value = earlier execution (default 0)
     */
    public function listen(string $eventClass, callable $listener, int $priority = 0): void;
}
