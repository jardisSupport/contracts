<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Tests\Unit\Auth;

use JardisSupport\Contract\Auth\RequestAttribute;
use JardisSupport\Contract\Auth\SeeAllRight;
use JardisSupport\Contract\Auth\SessionClaim;
use JardisSupport\Contract\Auth\TokenType;
use PHPUnit\Framework\TestCase;

final class EnumAndConstantValuesTest extends TestCase
{
    public function testTokenTypeValuesAreStable(): void
    {
        self::assertSame(
            [
                'Access' => 'access',
                'Refresh' => 'refresh',
                'ApiKey' => 'api_key',
                'Verification' => 'verification',
                'PasswordReset' => 'password_reset',
                'Invitation' => 'invitation',
            ],
            array_column(array_map(
                static fn(TokenType $t): array => ['n' => $t->name, 'v' => $t->value],
                TokenType::cases()
            ), 'v', 'n')
        );
        self::assertSame(TokenType::Invitation, TokenType::from('invitation'));
    }

    public function testRequestAttributeNames(): void
    {
        self::assertSame('jardisSubject', RequestAttribute::SUBJECT);
        self::assertSame('jardisTenant', RequestAttribute::TENANT);
        self::assertSame('jardisSubjectId', RequestAttribute::SUBJECT_ID);
        self::assertSame('jardisSession', RequestAttribute::SESSION);
        self::assertSame('jardisRole', RequestAttribute::ROLE);
        self::assertSame('jardisRights', RequestAttribute::RIGHTS);
    }

    public function testGroupRequestAttributeNames(): void
    {
        self::assertSame('jardisGroups', RequestAttribute::GROUPS);
        self::assertSame('jardisPersonalGroup', RequestAttribute::PERSONAL_GROUP);
        self::assertSame('jardisShareTargets', RequestAttribute::SHARE_TARGETS);
    }

    public function testSeeAllRightResourceIsPinned(): void
    {
        self::assertSame('seeAll', SeeAllRight::RESOURCE);
    }

    public function testSeeAllRightResourceHasNoDotAndNoColon(): void
    {
        self::assertStringNotContainsString('.', SeeAllRight::RESOURCE);
        self::assertStringNotContainsString(':', SeeAllRight::RESOURCE);
    }

    public function testSeeAllEntrySplitsIntoExactlyTwoParts(): void
    {
        $entry = SeeAllRight::RESOURCE . ':' . 'sales.order:read';

        $parts = explode(':', $entry, 2);

        self::assertCount(2, $parts);
        self::assertSame(SeeAllRight::RESOURCE, $parts[0]);
        self::assertSame('sales.order:read', $parts[1]);
    }

    public function testRightsClaimAndAttributeValues(): void
    {
        self::assertSame('rights', SessionClaim::RIGHTS);
        self::assertSame('jardisRights', RequestAttribute::RIGHTS);
        self::assertNotSame(SessionClaim::RIGHTS, RequestAttribute::RIGHTS);
    }

    public function testRoleAttributeIsDistinctFromSessionClaim(): void
    {
        self::assertSame('role', SessionClaim::ROLE);
        self::assertNotSame(SessionClaim::ROLE, RequestAttribute::ROLE);
    }
}
