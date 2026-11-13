<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Tests\Unit\Kernel;

use JardisSupport\Contract\Http\HttpServiceInterface;
use JardisSupport\Contract\Kernel\DomainKernelInterface;
use JardisSupport\Contract\Mailer\MailerInterface;
use JardisSupport\Contract\Mailer\MailServiceInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use ReflectionMethod;

/**
 * Holds fast the kernel accessors http() and mail(): both nullable, parameterless, typed with
 * the new service contracts, and httpClient()/mailer() stay as they were (no narrowed return type).
 */
final class DomainKernelServiceAccessorsTest extends TestCase
{
    public function testHttpAccessorIsNullableHttpService(): void
    {
        $this->assertNullableAccessor('http', HttpServiceInterface::class);
    }

    public function testMailAccessorIsNullableMailService(): void
    {
        $this->assertNullableAccessor('mail', MailServiceInterface::class);
    }

    public function testHttpClientStaysPsr18AndMailerStaysMailerInterface(): void
    {
        $this->assertNullableAccessor('httpClient', ClientInterface::class);
        $this->assertNullableAccessor('mailer', MailerInterface::class);
    }

    private function assertNullableAccessor(string $name, string $expectedType): void
    {
        $method = new ReflectionMethod(DomainKernelInterface::class, $name);
        $type = $method->getReturnType();

        self::assertInstanceOf(\ReflectionNamedType::class, $type);
        self::assertSame($expectedType, $type->getName());
        self::assertTrue($type->allowsNull());
        self::assertSame(0, $method->getNumberOfParameters());
    }
}
