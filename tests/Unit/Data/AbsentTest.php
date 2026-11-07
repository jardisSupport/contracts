<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Tests\Unit\Data;

use BackedEnum;
use JardisSupport\Contract\Data\Absent;
use PHPUnit\Framework\TestCase;

final class AbsentTest extends TestCase
{
    public function testHasExactlyOneCase(): void
    {
        self::assertSame([Absent::Value], Absent::cases());
    }

    public function testIsPureSoHydrationCannotWriteItAsAValue(): void
    {
        self::assertNotInstanceOf(BackedEnum::class, Absent::Value);
    }
}
