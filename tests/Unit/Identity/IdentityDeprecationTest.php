<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Tests\Unit\Identity;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Identity is deprecated since 2.11.0 but not removed (removal only in the next major).
 */
final class IdentityDeprecationTest extends TestCase
{
    public function testAllTwelveIdentityContractsExistAndCarryTheDeprecationTag(): void
    {
        $files = glob(dirname(__DIR__, 3) . '/src/Identity/*.php');
        self::assertIsArray($files);
        self::assertCount(12, $files);

        foreach ($files as $file) {
            $class = 'JardisSupport\\Contract\\Identity\\' . basename($file, '.php');
            $doc = (new ReflectionClass($class))->getDocComment();

            self::assertIsString($doc, $class);
            self::assertStringContainsString('@deprecated 2.11.0', $doc, $class);
        }
    }
}
