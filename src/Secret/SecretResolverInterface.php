<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Secret;

/**
 * Contract for resolving encrypted or protected values in environment configurations.
 * Implementations handle the actual decryption/retrieval of secret values.
 */
interface SecretResolverInterface
{
    /**
     * Check if this resolver can handle the given encrypted value.
     */
    public function supports(string $encryptedValue): bool;

    /**
     * Resolve an encrypted or protected value to its plaintext form.
     *
     * @throws SecretResolutionException When the value cannot be resolved
     */
    public function resolve(string $encryptedValue): string;
}
