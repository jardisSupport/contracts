<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Tests\Unit\Auth;

use JardisSupport\Contract\Auth\GroupLookupInterface;
use JardisSupport\Contract\Auth\SubjectGroupsInterface;
use JardisSupport\Contract\Tests\Unit\ContractShape;
use PHPUnit\Framework\TestCase;

/**
 * Conformance of the group contracts: exact method list/signatures via reflection and
 * implementability by an anonymous class.
 */
final class GroupContractsTest extends TestCase
{
    public function testSubjectGroupsInterface(): void
    {
        ContractShape::assertMethods(SubjectGroupsInterface::class, [
            'getGroups' => '(): array',
            'getPersonalGroup' => '(): ?int',
        ]);

        $groups = new class implements SubjectGroupsInterface {
            public function getGroups(): array
            {
                return [7, 12];
            }

            public function getPersonalGroup(): ?int
            {
                return 7;
            }
        };

        self::assertInstanceOf(SubjectGroupsInterface::class, $groups);
        self::assertSame([7, 12], $groups->getGroups());
        self::assertSame(7, $groups->getPersonalGroup());
    }

    public function testGroupLookupInterface(): void
    {
        ContractShape::assertMethods(GroupLookupInterface::class, [
            'groupsOf' => '(string $subjectId, ?string $tenant): JardisSupport\Contract\Auth\SubjectGroupsInterface',
            'personalGroupOf' => '(string $subjectId, ?string $tenant): ?int',
            'existingGroups' => '(array $groups, ?string $tenant): array',
            'fallbackGroupOf' => '(int $kind, ?string $tenant): ?int',
        ]);

        $lookup = new class implements GroupLookupInterface {
            public function groupsOf(string $subjectId, ?string $tenant): SubjectGroupsInterface
            {
                return new class implements SubjectGroupsInterface {
                    public function getGroups(): array
                    {
                        return [7];
                    }

                    public function getPersonalGroup(): ?int
                    {
                        return 7;
                    }
                };
            }

            public function personalGroupOf(string $subjectId, ?string $tenant): ?int
            {
                return 7;
            }

            public function existingGroups(array $groups, ?string $tenant): array
            {
                return array_values(array_filter($groups, static fn(int $g): bool => $g < 10));
            }

            public function fallbackGroupOf(int $kind, ?string $tenant): ?int
            {
                return $kind === 1 ? 3 : null;
            }
        };

        self::assertInstanceOf(GroupLookupInterface::class, $lookup);
        self::assertSame([7], $lookup->groupsOf('42', 't1')->getGroups());
        self::assertSame(7, $lookup->personalGroupOf('42', null));
        self::assertSame([3, 9], $lookup->existingGroups([3, 9, 15], 't1'));
        self::assertSame(3, $lookup->fallbackGroupOf(1, 't1'));
        self::assertNull($lookup->fallbackGroupOf(2, 't1'));
    }
}
