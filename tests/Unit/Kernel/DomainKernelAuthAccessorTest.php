<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Tests\Unit\Kernel;

use JardisSupport\Contract\Auth\AuthServicesInterface;
use JardisSupport\Contract\Kernel\DomainKernelInterface;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

final class DomainKernelAuthAccessorTest extends TestCase
{
    public function testAuthAccessorIsNullableAuthServices(): void
    {
        $method = new ReflectionMethod(DomainKernelInterface::class, 'auth');
        $type = $method->getReturnType();

        self::assertInstanceOf(\ReflectionNamedType::class, $type);
        self::assertSame(AuthServicesInterface::class, $type->getName());
        self::assertTrue($type->allowsNull());
        self::assertSame(0, $method->getNumberOfParameters());
    }

    public function testKernelStillDeclaresMailerNextToAuth(): void
    {
        $names = array_map(
            static fn(ReflectionMethod $m): string => $m->getName(),
            (new \ReflectionClass(DomainKernelInterface::class))->getMethods()
        );

        self::assertContains('mailer', $names);
        self::assertContains('auth', $names);
    }
}
