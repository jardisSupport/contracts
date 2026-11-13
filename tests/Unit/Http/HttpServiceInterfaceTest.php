<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Tests\Unit\Http;

use JardisSupport\Contract\Http\HttpServiceInterface;
use JardisSupport\Contract\Tests\Unit\ContractShape;
use PHPUnit\Framework\TestCase;

/**
 * Holds fast the shape of the HTTP service contract: exactly six verbs, bodiless verbs take
 * (uri, headers), body verbs take (uri, data, headers), all default to empty arrays and
 * answer with the PSR-7 response.
 */
final class HttpServiceInterfaceTest extends TestCase
{
    public function testDeclaresExactlyTheSixVerbsWithTheirSignatures(): void
    {
        $response = 'Psr\Http\Message\ResponseInterface';
        $bodiless = '(string $uri, array $headers = []): ' . $response;
        $withBody = '(string $uri, array $data = [], array $headers = []): ' . $response;

        ContractShape::assertMethods(HttpServiceInterface::class, [
            'get' => $bodiless,
            'delete' => $bodiless,
            'head' => $bodiless,
            'post' => $withBody,
            'put' => $withBody,
            'patch' => $withBody,
        ]);
    }

    public function testDoesNotExtendThePsr18ClientInterface(): void
    {
        // Additive service next to httpClient(): it must not narrow or replace the PSR-18 port.
        self::assertSame([], (new \ReflectionClass(HttpServiceInterface::class))->getInterfaceNames());
    }
}
