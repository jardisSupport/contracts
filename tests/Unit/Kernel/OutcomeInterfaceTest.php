<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Tests\Unit\Kernel;

use JardisSupport\Contract\Kernel\OutcomeInterface;
use JardisSupport\Contract\Kernel\ResponseStatus;
use JardisSupport\Contract\Tests\Unit\ContractShape;
use PHPUnit\Framework\TestCase;

final class OutcomeInterfaceTest extends TestCase
{
    public function testDeclaresExactlyStatusAndIsFailed(): void
    {
        ContractShape::assertMethods(OutcomeInterface::class, [
            'status' => '(): ' . ResponseStatus::class,
            'isFailed' => '(): bool',
        ], true);
    }

    public function testAnImplementorAnswersFailureFromItsOwnStatus(): void
    {
        $outcome = new class (ResponseStatus::RuleViolation) implements OutcomeInterface {
            public function __construct(private readonly ResponseStatus $status)
            {
            }

            public function status(): ResponseStatus
            {
                return $this->status;
            }

            public function isFailed(): bool
            {
                return $this->status->value >= 400;
            }
        };

        self::assertSame(ResponseStatus::RuleViolation, $outcome->status());
        self::assertTrue($outcome->isFailed());
    }
}
