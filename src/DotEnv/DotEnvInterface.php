<?php

declare(strict_types=1);

namespace JardisSupport\Contract\DotEnv;

use Exception;

/**
 * The DotEnv Interface
 */
interface DotEnvInterface
{
    /** @throws Exception */
    public function loadPublic(string $pathToEnvFiles): void;

    /**
     * @return array<string, mixed>|null
     * @throws Exception
     */
    public function loadPrivate(string $pathToEnvFiles): mixed;
}
