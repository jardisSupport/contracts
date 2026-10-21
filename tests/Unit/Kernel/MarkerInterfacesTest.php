<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Tests\Unit\Kernel;

use JardisSupport\Contract\Kernel\AccessDeniedExceptionInterface;
use JardisSupport\Contract\Kernel\ConcurrencyConflictExceptionInterface;
use JardisSupport\Contract\Kernel\ReportedFailureExceptionInterface;
use JardisSupport\Contract\Kernel\ValidationExceptionInterface;
use JardisSupport\Contract\Tests\Unit\ContractShape;
use PHPUnit\Framework\TestCase;

final class MarkerInterfacesTest extends TestCase
{
    public function testBothAreInterfacesWithTheDocumentedMethods(): void
    {
        ContractShape::assertMethods(ConcurrencyConflictExceptionInterface::class, [
            'getReason' => '(): string',
            'getAggregate' => '(): string',
            'getContext' => '(): array',
            'getViolated' => '(): array',
        ], true);
        ContractShape::assertMethods(ValidationExceptionInterface::class, [
            'getFields' => '(): array',
        ], true);

        self::assertTrue(
            (new \ReflectionClass(ConcurrencyConflictExceptionInterface::class))->isSubclassOf(\Throwable::class)
        );
        self::assertTrue(
            (new \ReflectionClass(ValidationExceptionInterface::class))->isSubclassOf(\Throwable::class)
        );
    }

    public function testAGeneratedStyleExceptionCanImplementBothMarkers(): void
    {
        $exception = new class ('boom') extends \RuntimeException implements
            ConcurrencyConflictExceptionInterface,
            ValidationExceptionInterface {
            public function getReason(): string
            {
                return 'stale_version';
            }

            public function getAggregate(): string
            {
                return 'Order';
            }

            public function getContext(): array
            {
                return ['expected' => 3];
            }

            public function getViolated(): array
            {
                return ['version'];
            }

            public function getFields(): array
            {
                return [[
                    'field' => 'name',
                    'reason' => 'required',
                    'message' => 'Name missing',
                    'messageKey' => 'order.name.required',
                ]];
            }
        };

        try {
            throw $exception;
        } catch (\Throwable $caught) {
            self::assertInstanceOf(ConcurrencyConflictExceptionInterface::class, $caught);
            self::assertInstanceOf(ValidationExceptionInterface::class, $caught);
            self::assertSame('stale_version', $caught->getReason());
            self::assertSame('Order', $caught->getAggregate());
            self::assertSame(['expected' => 3], $caught->getContext());
            self::assertSame(['version'], $caught->getViolated());
            self::assertSame('name', $caught->getFields()[0]['field']);
        }
    }

    public function testReportedFailureIsAnEmptyThrowableMarker(): void
    {
        ContractShape::assertMethods(ReportedFailureExceptionInterface::class, [], true);

        self::assertTrue(
            (new \ReflectionClass(ReportedFailureExceptionInterface::class))->isSubclassOf(\Throwable::class)
        );

        $exception = new class ('already logged') extends \RuntimeException implements
            ReportedFailureExceptionInterface {
        };

        try {
            throw $exception;
        } catch (\Throwable $caught) {
            self::assertInstanceOf(ReportedFailureExceptionInterface::class, $caught);
            self::assertSame('already logged', $caught->getMessage());
        }
    }

    public function testAccessDeniedCarriesMessageKeyAndContextAsData(): void
    {
        ContractShape::assertMethods(AccessDeniedExceptionInterface::class, [
            'getMessageKey' => '(): string',
            'getContext' => '(): array',
        ], true);

        self::assertTrue(
            (new \ReflectionClass(AccessDeniedExceptionInterface::class))->isSubclassOf(\Throwable::class)
        );

        $exception = new class ('denied') extends \RuntimeException implements AccessDeniedExceptionInterface {
            public function getMessageKey(): string
            {
                return 'test.key';
            }

            public function getContext(): array
            {
                return ['right' => 'a:b'];
            }
        };

        try {
            throw $exception;
        } catch (\Throwable $caught) {
            self::assertInstanceOf(AccessDeniedExceptionInterface::class, $caught);
            self::assertSame('test.key', $caught->getMessageKey());
            self::assertSame(['right' => 'a:b'], $caught->getContext());
        }
    }
}
