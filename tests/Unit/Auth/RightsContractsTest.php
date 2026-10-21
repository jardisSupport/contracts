<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Tests\Unit\Auth;

use JardisSupport\Contract\Auth\AuthResultInterface;
use JardisSupport\Contract\Auth\RightsAwareAuthResultInterface;
use JardisSupport\Contract\Auth\RightsCatalogInterface;
use JardisSupport\Contract\Tests\Unit\ContractShape;
use PHPUnit\Framework\TestCase;

/**
 * Conformance of the rights contracts: exact method list/signatures via reflection and
 * implementability by an anonymous class.
 */
final class RightsContractsTest extends TestCase
{
    public function testRightsAwareAuthResultInterface(): void
    {
        ContractShape::assertMethods(RightsAwareAuthResultInterface::class, [
            'isSuccess' => '(): bool',
            'getSubject' => '(): ?string',
            'getReason' => '(): ?string',
            'getRights' => '(): array',
        ]);

        $result = new class implements RightsAwareAuthResultInterface {
            public function isSuccess(): bool
            {
                return true;
            }

            public function getSubject(): ?string
            {
                return 'user:1';
            }

            public function getReason(): ?string
            {
                return null;
            }

            public function getRights(): array
            {
                return ['sales.process:*', 'fulfillment.shipment:read'];
            }
        };

        self::assertInstanceOf(AuthResultInterface::class, $result);
        self::assertInstanceOf(RightsAwareAuthResultInterface::class, $result);
        self::assertSame(['sales.process:*', 'fulfillment.shipment:read'], $result->getRights());
    }

    public function testRightsCatalogInterface(): void
    {
        ContractShape::assertMethods(RightsCatalogInterface::class, [
            'rights' => '(): array',
        ]);

        $catalog = new class implements RightsCatalogInterface {
            public function rights(): array
            {
                return [
                    'fulfillment.shipment:read' => 'GET /fulfillment/shipments/queries/byIdentifiers',
                    'sales.process:voucherRedeemCheck' => 'POST /sales/voucherRedeemCheck',
                ];
            }
        };

        self::assertInstanceOf(RightsCatalogInterface::class, $catalog);
        self::assertSame(
            ['fulfillment.shipment:read', 'sales.process:voucherRedeemCheck'],
            array_keys($catalog->rights())
        );
    }
}
