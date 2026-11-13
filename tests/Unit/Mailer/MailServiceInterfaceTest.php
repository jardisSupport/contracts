<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Tests\Unit\Mailer;

use JardisSupport\Contract\Mailer\MailServiceInterface;
use JardisSupport\Contract\Tests\Unit\ContractShape;
use PHPUnit\Framework\TestCase;

/**
 * Holds fast the shape of the mail service contract: one method `sendMail` with the
 * recipient (string|array), subject, optional text and HTML bodies, an options array,
 * and no return value.
 */
final class MailServiceInterfaceTest extends TestCase
{
    /** Reflection reports the union `string|array` in its normalised order `array|string`. */
    public function testDeclaresExactlySendMailWithItsSignature(): void
    {
        ContractShape::assertMethods(MailServiceInterface::class, [
            'sendMail' => '(array|string $to, string $subject, ?string $text = null, ?string $html = null,'
                . ' array $options = []): void',
        ]);
    }

    public function testIsIndependentOfMailerInterface(): void
    {
        // Additive service next to MailerInterface::send(): it must not extend or replace it.
        self::assertSame([], (new \ReflectionClass(MailServiceInterface::class))->getInterfaceNames());
    }
}
