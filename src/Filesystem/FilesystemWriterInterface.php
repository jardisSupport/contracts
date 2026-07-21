<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Filesystem;

/**
 * Contract for write filesystem operations.
 *
 * Use this for contexts that need write access.
 * Combined with FilesystemReaderInterface in FilesystemInterface.
 */
interface FilesystemWriterInterface
{
    /**
     * Write contents to a file.
     *
     * Creates the file if it does not exist, overwrites if it does.
     *
     * @param string $path Relative path within the filesystem root
     * @param string $content File contents
     * @throws FilesystemExceptionInterface If the file cannot be written
     */
    public function write(string $path, string $content): void;

    /**
     * Write a stream to a file.
     *
     * Use for large files to avoid memory overhead.
     *
     * @param string $path Relative path within the filesystem root
     * @param resource $resource PHP stream resource
     * @throws FilesystemExceptionInterface If the file cannot be written
     */
    public function writeStream(string $path, $resource): void;

    /**
     * Delete a file.
     *
     * @param string $path Relative path within the filesystem root
     * @throws FilesystemExceptionInterface If the file cannot be deleted
     */
    public function delete(string $path): void;

    /**
     * Copy a file.
     *
     * @param string $source Source path
     * @param string $destination Destination path
     * @throws FilesystemExceptionInterface If the copy operation fails
     */
    public function copy(string $source, string $destination): void;

    /**
     * Move (rename) a file.
     *
     * @param string $source Source path
     * @param string $destination Destination path
     * @throws FilesystemExceptionInterface If the move operation fails
     */
    public function move(string $source, string $destination): void;

    /**
     * Create a directory.
     *
     * Creates parent directories as needed.
     *
     * @param string $path Relative path within the filesystem root
     * @throws FilesystemExceptionInterface If the directory cannot be created
     */
    public function createDirectory(string $path): void;

    /**
     * Delete a directory and all its contents.
     *
     * @param string $path Relative path within the filesystem root
     * @throws FilesystemExceptionInterface If the directory cannot be deleted
     */
    public function deleteDirectory(string $path): void;
}
