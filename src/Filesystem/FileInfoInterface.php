<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Filesystem;

/**
 * Contract for file/directory metadata returned by directory listings.
 */
interface FileInfoInterface
{
    /**
     * Get the relative path within the filesystem root.
     */
    public function path(): string;

    /**
     * Get the file size in bytes. Returns 0 for directories.
     */
    public function size(): int;

    /**
     * Get the last modification time as Unix timestamp.
     */
    public function lastModified(): int;

    /**
     * Check if this entry is a file.
     */
    public function isFile(): bool;

    /**
     * Check if this entry is a directory.
     */
    public function isDirectory(): bool;
}
