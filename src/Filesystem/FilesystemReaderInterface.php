<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Filesystem;

/**
 * Contract for read-only filesystem operations.
 *
 * Use this for contexts that should not write — e.g. query handlers,
 * report generators, or any read-only bounded context.
 */
interface FilesystemReaderInterface
{
    /**
     * Read a file's contents.
     *
     * @param string $path Relative path within the filesystem root
     * @return string File contents
     * @throws FilesystemExceptionInterface If the file cannot be read
     */
    public function read(string $path): string;

    /**
     * Read a file as a stream resource.
     *
     * Use for large files to avoid memory overhead.
     *
     * @param string $path Relative path within the filesystem root
     * @return resource PHP stream resource
     * @throws FilesystemExceptionInterface If the file cannot be read
     */
    public function readStream(string $path);

    /**
     * Check if a file or directory exists.
     *
     * @param string $path Relative path within the filesystem root
     */
    public function exists(string $path): bool;

    /**
     * Get the file size in bytes.
     *
     * @param string $path Relative path within the filesystem root
     * @throws FilesystemExceptionInterface If the file does not exist
     */
    public function size(string $path): int;

    /**
     * Get the last modification time as Unix timestamp.
     *
     * @param string $path Relative path within the filesystem root
     * @throws FilesystemExceptionInterface If the file does not exist
     */
    public function lastModified(string $path): int;

    /**
     * Get the MIME type.
     *
     * @param string $path Relative path within the filesystem root
     * @throws FilesystemExceptionInterface If the file does not exist or type cannot be detected
     */
    public function mimeType(string $path): string;

    /**
     * List contents of a directory.
     *
     * @param string $path Directory path relative to filesystem root
     * @param bool $recursive Whether to list recursively
     * @return iterable<FileInfoInterface> File and directory entries
     * @throws FilesystemExceptionInterface If the directory cannot be listed
     */
    public function listContents(string $path, bool $recursive = false): iterable;
}
