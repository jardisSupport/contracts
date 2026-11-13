<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Http;

use Psr\Http\Message\ResponseInterface;

/**
 * HTTP service of the kernel for domain code.
 *
 * Ready-made calls for the common HTTP verbs, reached via
 * `DomainKernelInterface::http()`. Domain code imports only this contract and
 * PSR-7 — no adapter class is needed. Bodies of `post`/`put`/`patch` are JSON;
 * any other body form stays with the PSR-18 client (`httpClient()`).
 *
 * A 4xx/5xx status is NOT an error here: it comes back as a response and the
 * caller inspects the status. Only a failed transport throws.
 */
interface HttpServiceInterface
{
    /**
     * Sends a GET request.
     *
     * @param string $uri Absolute or client-relative URI
     * @param array<string, string> $headers Request headers, name => value
     * @throws \Psr\Http\Client\ClientExceptionInterface On transport failure only
     */
    public function get(string $uri, array $headers = []): ResponseInterface;

    /**
     * Sends a DELETE request.
     *
     * @param string $uri Absolute or client-relative URI
     * @param array<string, string> $headers Request headers, name => value
     * @throws \Psr\Http\Client\ClientExceptionInterface On transport failure only
     */
    public function delete(string $uri, array $headers = []): ResponseInterface;

    /**
     * Sends a HEAD request.
     *
     * @param string $uri Absolute or client-relative URI
     * @param array<string, string> $headers Request headers, name => value
     * @throws \Psr\Http\Client\ClientExceptionInterface On transport failure only
     */
    public function head(string $uri, array $headers = []): ResponseInterface;

    /**
     * Sends a POST request with a JSON body.
     *
     * @param string $uri Absolute or client-relative URI
     * @param array<string, mixed> $data Request body, encoded as JSON
     * @param array<string, string> $headers Request headers, name => value
     * @throws \Psr\Http\Client\ClientExceptionInterface On transport failure only
     */
    public function post(string $uri, array $data = [], array $headers = []): ResponseInterface;

    /**
     * Sends a PUT request with a JSON body.
     *
     * @param string $uri Absolute or client-relative URI
     * @param array<string, mixed> $data Request body, encoded as JSON
     * @param array<string, string> $headers Request headers, name => value
     * @throws \Psr\Http\Client\ClientExceptionInterface On transport failure only
     */
    public function put(string $uri, array $data = [], array $headers = []): ResponseInterface;

    /**
     * Sends a PATCH request with a JSON body.
     *
     * @param string $uri Absolute or client-relative URI
     * @param array<string, mixed> $data Request body, encoded as JSON
     * @param array<string, string> $headers Request headers, name => value
     * @throws \Psr\Http\Client\ClientExceptionInterface On transport failure only
     */
    public function patch(string $uri, array $data = [], array $headers = []): ResponseInterface;
}
