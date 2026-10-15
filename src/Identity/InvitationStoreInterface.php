<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Identity;

use DateTimeImmutable;

/**
 * Persistence port for invitations.
 *
 * @deprecated 2.11.0 Identity ist eine Generic Subdomain; ersetzt durch die Builder-Blaupause „Access“
 */
interface InvitationStoreInterface
{
    public function store(InvitationInterface $invitation): void;

    public function findByTokenHash(string $tokenHash): ?InvitationInterface;

    /** Finds the open (neither accepted nor revoked) invitation for an email in a tenant. */
    public function findPending(string $tenantId, string $email): ?InvitationInterface;

    /** @return list<InvitationInterface> */
    public function listPendingForTenant(string $tenantId): array;

    /**
     * Marks the invitation as accepted (CAS: succeeds only if it is neither accepted nor revoked).
     *
     * @return bool False if it was already accepted or revoked
     */
    public function markAccepted(string $id, DateTimeImmutable $at): bool;

    public function markRevoked(string $id, DateTimeImmutable $at): void;
}
