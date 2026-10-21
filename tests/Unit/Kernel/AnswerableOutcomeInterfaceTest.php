<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Tests\Unit\Kernel;

use JardisSupport\Contract\Kernel\AnswerableOutcomeInterface;
use JardisSupport\Contract\Kernel\OutcomeInterface;
use JardisSupport\Contract\Kernel\ResponseStatus;
use JardisSupport\Contract\Tests\Unit\ContractShape;
use PHPUnit\Framework\TestCase;

final class AnswerableOutcomeInterfaceTest extends TestCase
{
    public function testDeclaresExactlyTheFiveAnswerAccessors(): void
    {
        ContractShape::assertMethods(AnswerableOutcomeInterface::class, [
            'response' => '(): object',
            'errors' => '(): array',
            'events' => '(): array',
            'messageKey' => '(): ?string',
            'context' => '(): array',
        ], true);
    }

    public function testIsAnOutcomeAndLeavesTheBaseInterfaceUntouched(): void
    {
        self::assertTrue(is_subclass_of(AnswerableOutcomeInterface::class, OutcomeInterface::class));
        ContractShape::assertMethods(OutcomeInterface::class, [
            'status' => '(): ' . ResponseStatus::class,
            'isFailed' => '(): bool',
        ], true);
    }
}
