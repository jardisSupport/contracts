<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Filesystem;

/**
 * Contract for filesystem operations.
 *
 * Unified API for local and cloud storage. Combines read and write operations.
 * For restricted access, inject FilesystemReaderInterface or FilesystemWriterInterface instead.
 */
interface FilesystemInterface extends FilesystemReaderInterface, FilesystemWriterInterface
{
}
