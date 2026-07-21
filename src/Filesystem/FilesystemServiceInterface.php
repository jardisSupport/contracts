<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Filesystem;

/**
 * Contract for creating filesystem instances.
 *
 * Provides factory methods for common storage backends.
 * For advanced configuration, use the concrete FilesystemService::create() method.
 */
interface FilesystemServiceInterface
{
    /**
     * Create a local filesystem instance.
     *
     * @param string $root Absolute path to the root directory
     */
    public function local(string $root): FilesystemInterface;

    /**
     * Create an S3-compatible filesystem instance.
     *
     * @param string $bucket S3 bucket name
     * @param string $region AWS region (e.g. 'eu-central-1')
     * @param string $key Access Key ID
     * @param string $secret Secret Access Key
     * @param string $endpoint S3 endpoint URL (default: AWS)
     * @param string $prefix Path prefix in bucket
     */
    public function s3(
        string $bucket,
        string $region,
        string $key,
        #[\SensitiveParameter]
        string $secret,
        string $endpoint = 'https://s3.amazonaws.com',
        string $prefix = '',
    ): FilesystemInterface;
}
