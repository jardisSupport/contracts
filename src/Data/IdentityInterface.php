<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Data;

/**
 * Identity generation for entities and aggregates.
 */
interface IdentityInterface
{
    /**
     * Generates a UUID v4 (random).
     *
     * @return string UUID v4 in canonical format
     */
    public function generateUuid4(): string;

    /**
     * Generates a UUID v7 (time-ordered).
     *
     * UUIDs are lexicographically sortable by creation time.
     *
     * @return string UUID v7 in canonical format
     */
    public function generateUuid7(): string;

    /**
     * Generates a UUID v5 (name-based, deterministic).
     *
     * Same namespace + name always produces the same UUID.
     * Useful for deriving stable identities from business data.
     *
     * @param string $namespace A valid UUID used as namespace
     * @param string $name The name to hash within the namespace
     * @return string UUID v5 in canonical format
     */
    public function generateUuid5(string $namespace, string $name): string;

    /**
     * Generates a NanoID — a compact, URL-safe random identifier.
     *
     * @param int $length Length of the generated ID (default: 21)
     * @param string $alphabet Characters to use (default: A-Za-z0-9_-)
     * @return string NanoID string
     */
    public function generateNanoId(
        int $length = 21,
        string $alphabet = '_-0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ): string;
}
