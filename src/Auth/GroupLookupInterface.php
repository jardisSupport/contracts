<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Auth;

/**
 * Answers the group questions of the access layer: which groups does a person belong to, which group
 * is a person's own, which of a set of group ids exist, and which fallback group catches an information kind.
 *
 * All questions are scoped by the tenant (null = no tenant). Implementations live outside this package.
 */
interface GroupLookupInterface
{
    /**
     * The groups of the person, including the personal group.
     *
     * @param string $subjectId bare subject identifier (without type prefix), see `RequestAttribute::SUBJECT_ID`
     */
    public function groupsOf(string $subjectId, ?string $tenant): SubjectGroupsInterface;

    /**
     * Id of the personal group of the person, or null if the person has none.
     *
     * @param string $subjectId bare subject identifier (without type prefix)
     */
    public function personalGroupOf(string $subjectId, ?string $tenant): ?int;

    /**
     * The subset of the given group ids that exist within the tenant.
     *
     * @param list<int> $groups
     * @return list<int>
     */
    public function existingGroups(array $groups, ?string $tenant): array;

    /**
     * Id of the fallback group of the information kind, or null if the kind has none.
     */
    public function fallbackGroupOf(int $kind, ?string $tenant): ?int;
}
