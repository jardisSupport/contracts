<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Auth;

/**
 * The groups of one person (subject) within a tenant, as answered by `GroupLookupInterface::groupsOf()`.
 */
interface SubjectGroupsInterface
{
    /**
     * Ids of all groups the person belongs to, including the personal group (if any).
     *
     * @return list<int>
     */
    public function getGroups(): array;

    /**
     * Id of the personal group of the person, or null if the person has none.
     */
    public function getPersonalGroup(): ?int;
}
