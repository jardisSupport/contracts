<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Kernel;

use JardisSupport\Contract\DbConnection\ConnectionPoolInterface;
use JardisSupport\Contract\EventListener\EventListenerRegistryInterface;
use JardisSupport\Contract\Filesystem\FilesystemServiceInterface;
use JardisSupport\Contract\Mailer\MailerInterface;
use JardisSupport\Contract\Messaging\MessagingServiceInterface;
use PDO;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * Domain infrastructure interface.
 *
 * Provides typed, immutable access to infrastructure services for bounded contexts.
 * Service interfaces are PSR standards where available, Jardis contracts otherwise.
 * Set once at bootstrap via constructor injection, immutable after creation.
 *
 * Services are nullable — not every project needs every service.
 * The domain code checks availability and acts accordingly.
 */
interface DomainKernelInterface
{
    /**
     * Gets the project root directory path.
     *
     * @return string Absolute path to the root of the project the kernel
     *     serves; multiple domains in one project share it
     */
    public function projectRoot(): string;

    /**
     * Gets an environment configuration value.
     *
     * Looks up the kernel's private ENV — the values loaded from the
     * project's `config/env` files. Keys are case-insensitive.
     *
     * @param string $key The configuration key to retrieve
     * @return mixed The value or null if not found
     */
    public function env(string $key): mixed;

    /**
     * Gets the PSR-11 service container.
     *
     * Used by generated `{Domain}Context` classes for class resolution and
     * service lookup. Wiring the container itself is out of this
     * interface's scope — a service earns a typed accessor here once the
     * kernel bootstraps it from canonical ENV keys (see `messaging()`);
     * everything else stays reachable only through this container.
     *
     * @return ContainerInterface Container instance (always available)
     */
    public function container(): ContainerInterface;

    /**
     * Gets the PSR-16 simple cache.
     *
     * @return CacheInterface|null Cache instance or null if not configured
     */
    public function cache(): ?CacheInterface;

    /**
     * Gets the PSR-3 logger.
     *
     * @return LoggerInterface|null Logger instance or null if not configured
     */
    public function logger(): ?LoggerInterface;

    /**
     * Gets the PSR-14 event dispatcher for domain events.
     *
     * @return EventDispatcherInterface|null Dispatcher or null if not configured
     */
    public function eventDispatcher(): ?EventDispatcherInterface;

    /**
     * Gets the event listener registry for self-registering event routers.
     *
     * Paired with eventDispatcher(): implementations back both accessors with
     * the same underlying provider instance — one PSR-14 dispatcher (send),
     * one registry (add listeners). Generated `{Agg}EventRouter` scaffolds use
     * it to register themselves on the domain facade's constructor, so a
     * fresh build carries new routers automatically without any Application
     * wiring. Nullable like every other service: without a registry, event
     * routing stays inactive rather than failing.
     *
     * @return EventListenerRegistryInterface|null Registry or null if event routing is not configured
     */
    public function eventListenerRegistry(): ?EventListenerRegistryInterface;

    /**
     * Gets the PSR-18 HTTP client for external service calls.
     *
     * @return ClientInterface|null HTTP client or null if not configured
     */
    public function httpClient(): ?ClientInterface;

    /**
     * Gets the database connection.
     *
     * @return ConnectionPoolInterface|PDO|null Connection or null if no database configured
     */
    public function dbConnection(): ConnectionPoolInterface|PDO|null;

    /**
     * Gets the mailer for sending emails.
     *
     * @return MailerInterface|null Mailer or null if not configured
     */
    public function mailer(): ?MailerInterface;

    /**
     * Gets the filesystem service for creating filesystem instances.
     *
     * @return FilesystemServiceInterface|null Filesystem service or null if not configured
     */
    public function filesystem(): ?FilesystemServiceInterface;

    /**
     * Gets the messaging service for publishing and consuming messages.
     *
     * @return MessagingServiceInterface|null Messaging service or null if not configured
     */
    public function messaging(): ?MessagingServiceInterface;
}
