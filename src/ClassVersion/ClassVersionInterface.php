<?php

declare(strict_types=1);

namespace JardisSupport\Contract\ClassVersion;

interface ClassVersionInterface
{
    /**
     * @template T
     * @param class-string<T> $className
     * @param ?string $version
     * @return mixed|T
     */
    public function __invoke(string $className, ?string $version = null): mixed;
}
