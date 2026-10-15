<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Identity;

use DateTimeImmutable;

/**
 * An invitation of an email address into a tenant. Only the token hash is held, never the plain token.
 *
 * @deprecated 2.11.0 Identity ist eine Generic Subdomain; ersetzt durch die Builder-Blaupause „Access“
 */
interface InvitationInterface
{
    public function getId(): string;

    public function getTenantId(): string;

    public function getEmail(): string;

    public function getRole(): string;

    public function getTokenHash(): string;

    public function getExpiresAt(): DateTimeImmutable;

    public function getAcceptedAt(): ?DateTimeImmutable;

    public function getRevokedAt(): ?DateTimeImmutable;

    public function getCreatedAt(): DateTimeImmutable;
}
