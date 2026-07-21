<?php

declare(strict_types=1);

namespace JardisSupport\Contract\ClassVersion;

interface ClassVersionConfigInterface
{
    /**
     * @param string|null $version
     * @return string|null
     */
    public function version(?string $version = null): ?string;

    /**
     * Returns the fallback chain for a given version.
     *
     * The chain includes the resolved version itself as the first entry,
     * followed by any configured fallback versions in order.
     * The base class (no version) is always the implicit last fallback
     * and is NOT included in the returned array.
     *
     * Example: For version 'v2' with fallbacks ['v2' => ['v1']],
     * returns ['v2', 'v1'].
     *
     * @return array<string>
     */
    public function fallbackChain(?string $version = null): array;
}
