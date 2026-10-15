<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Tests\Unit;

use PHPUnit\Framework\Assert;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;

/**
 * Reflection helper: asserts that an interface declares exactly the expected public methods
 * with the expected parameter and return signatures.
 */
final class ContractShape
{
    /**
     * @param class-string $interface
     * @param array<string, string> $expected method name => "(<params>): <return>"
     */
    public static function assertMethods(string $interface, array $expected): void
    {
        $reflection = new ReflectionClass($interface);
        Assert::assertTrue($reflection->isInterface(), $interface . ' must be an interface');

        $actual = [];
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $actual[$method->getName()] = self::signature($method);
        }

        ksort($actual);
        ksort($expected);
        Assert::assertSame($expected, $actual, 'Method list/signatures of ' . $interface);
    }

    private static function signature(ReflectionMethod $method): string
    {
        $params = array_map(
            static fn(ReflectionParameter $p): string => self::type($p->getType()) . ' $' . $p->getName()
                . ($p->isDefaultValueAvailable() ? ' = ' . json_encode($p->getDefaultValue()) : ''),
            $method->getParameters()
        );

        return '(' . implode(', ', $params) . '): ' . self::type($method->getReturnType());
    }

    private static function type(?\ReflectionType $type): string
    {
        if ($type === null) {
            return 'mixed';
        }
        if ($type instanceof ReflectionNamedType) {
            return ($type->allowsNull() && $type->getName() !== 'mixed' && $type->getName() !== 'null' ? '?' : '')
                . $type->getName();
        }

        return (string) $type;
    }
}
