<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Tests\Unit\Auth;

use DateTimeImmutable;
use JardisSupport\Contract\Auth\AuthResultInterface;
use JardisSupport\Contract\Auth\AuthServicesInterface;
use JardisSupport\Contract\Auth\HashedTokenInterface;
use JardisSupport\Contract\Auth\IssuedTokenInterface;
use JardisSupport\Contract\Auth\PasswordHasherInterface;
use JardisSupport\Contract\Auth\RoleAwareAuthResultInterface;
use JardisSupport\Contract\Auth\SessionInterface;
use JardisSupport\Contract\Auth\SessionManagerInterface;
use JardisSupport\Contract\Auth\SessionResultInterface;
use JardisSupport\Contract\Auth\TokenIssuerInterface;
use JardisSupport\Contract\Auth\TokenRedeemerInterface;
use JardisSupport\Contract\Auth\TokenType;
use JardisSupport\Contract\Tests\Unit\ContractShape;
use PHPUnit\Framework\TestCase;

/**
 * Conformance of the 2.11 auth-service contracts: exact method list/signatures via reflection and
 * implementability by an anonymous class.
 */
final class AuthServicesContractsTest extends TestCase
{
    private function hashedToken(): HashedTokenInterface
    {
        return new class implements HashedTokenInterface {
            public function getHash(): string
            {
                return 'h';
            }

            public function getType(): TokenType
            {
                return TokenType::Invitation;
            }

            public function getSubject(): ?string
            {
                return 'user:1';
            }

            public function getClaims(): array
            {
                return [];
            }

            public function getExpiresAt(): ?DateTimeImmutable
            {
                return null;
            }

            public function getCreatedAt(): DateTimeImmutable
            {
                return new DateTimeImmutable('2026-01-01');
            }

            public function isExpired(): bool
            {
                return false;
            }

            public function isRevoked(): bool
            {
                return false;
            }
        };
    }

    private function session(): SessionInterface
    {
        return new class implements SessionInterface {
            public function getSubject(): string
            {
                return 'user:1';
            }

            public function getTokenHash(): string
            {
                return 'h';
            }

            public function getExpiresAt(): ?DateTimeImmutable
            {
                return null;
            }

            public function isExpired(): bool
            {
                return false;
            }

            public function getMetadata(): array
            {
                return [];
            }
        };
    }

    public function testSessionInterface(): void
    {
        ContractShape::assertMethods(SessionInterface::class, [
            'getSubject' => '(): string',
            'getTokenHash' => '(): string',
            'getExpiresAt' => '(): ?DateTimeImmutable',
            'isExpired' => '(): bool',
            'getMetadata' => '(): array',
        ]);

        self::assertNull($this->session()->getExpiresAt());
    }

    public function testSessionResultInterface(): void
    {
        ContractShape::assertMethods(SessionResultInterface::class, [
            'getAccessToken' => '(): string',
            'getRefreshToken' => '(): string',
            'getSession' => '(): JardisSupport\Contract\Auth\SessionInterface',
        ]);

        $session = $this->session();
        $result = new class ($session) implements SessionResultInterface {
            public function __construct(private readonly SessionInterface $session)
            {
            }

            public function getAccessToken(): string
            {
                return 'a';
            }

            public function getRefreshToken(): string
            {
                return 'r';
            }

            public function getSession(): SessionInterface
            {
                return $this->session;
            }
        };

        self::assertSame('a', $result->getAccessToken());
        self::assertSame($session, $result->getSession());
    }

    public function testIssuedTokenInterface(): void
    {
        ContractShape::assertMethods(IssuedTokenInterface::class, [
            'getPlain' => '(): string',
            'getHashed' => '(): JardisSupport\Contract\Auth\HashedTokenInterface',
        ]);

        $hashed = $this->hashedToken();
        $issued = new class ($hashed) implements IssuedTokenInterface {
            public function __construct(private readonly HashedTokenInterface $hashed)
            {
            }

            public function getPlain(): string
            {
                return 'plain';
            }

            public function getHashed(): HashedTokenInterface
            {
                return $this->hashed;
            }
        };

        self::assertSame('plain', $issued->getPlain());
        self::assertSame($hashed, $issued->getHashed());
    }

    public function testSessionManagerInterface(): void
    {
        ContractShape::assertMethods(SessionManagerInterface::class, [
            'create' => '(string $subject, array $claims = []): JardisSupport\Contract\Auth\SessionResultInterface',
            'refresh' => '(string $refreshToken): JardisSupport\Contract\Auth\SessionResultInterface',
            'invalidateByReference' => '(string $sessionReference): void',
            'invalidateAll' => '(string $subject): void',
        ]);

        $manager = new class ($this->session()) implements SessionManagerInterface {
            /** @var list<string> */
            public array $calls = [];

            public function __construct(private readonly SessionInterface $session)
            {
            }

            public function create(string $subject, array $claims = []): SessionResultInterface
            {
                $this->calls[] = 'create:' . $subject;

                return $this->result();
            }

            public function refresh(string $refreshToken): SessionResultInterface
            {
                $this->calls[] = 'refresh:' . $refreshToken;

                return $this->result();
            }

            public function invalidateByReference(string $sessionReference): void
            {
                $this->calls[] = 'ref:' . $sessionReference;
            }

            public function invalidateAll(string $subject): void
            {
                $this->calls[] = 'all:' . $subject;
            }

            private function result(): SessionResultInterface
            {
                $session = $this->session;

                return new class ($session) implements SessionResultInterface {
                    public function __construct(private readonly SessionInterface $session)
                    {
                    }

                    public function getAccessToken(): string
                    {
                        return 'a';
                    }

                    public function getRefreshToken(): string
                    {
                        return 'r';
                    }

                    public function getSession(): SessionInterface
                    {
                        return $this->session;
                    }
                };
            }
        };

        $manager->create('user:1');
        $manager->refresh('rt');
        $manager->invalidateByReference('ref');
        $manager->invalidateAll('user:1');

        self::assertSame(['create:user:1', 'refresh:rt', 'ref:ref', 'all:user:1'], $manager->calls);
    }

    public function testTokenIssuerInterface(): void
    {
        ContractShape::assertMethods(TokenIssuerInterface::class, [
            'issue' => '(JardisSupport\Contract\Auth\TokenType $type, string $subject, int $ttlSeconds, '
                . 'array $claims = []): JardisSupport\Contract\Auth\IssuedTokenInterface',
        ]);

        $hashed = $this->hashedToken();
        $issuer = new class ($hashed) implements TokenIssuerInterface {
            public function __construct(private readonly HashedTokenInterface $hashed)
            {
            }

            public function issue(
                TokenType $type,
                string $subject,
                int $ttlSeconds,
                array $claims = []
            ): IssuedTokenInterface {
                $hashed = $this->hashed;

                return new class ($hashed) implements IssuedTokenInterface {
                    public function __construct(private readonly HashedTokenInterface $hashed)
                    {
                    }

                    public function getPlain(): string
                    {
                        return 'plain';
                    }

                    public function getHashed(): HashedTokenInterface
                    {
                        return $this->hashed;
                    }
                };
            }
        };

        self::assertSame('plain', $issuer->issue(TokenType::Invitation, 'user:1', 60)->getPlain());
    }

    public function testTokenRedeemerInterface(): void
    {
        ContractShape::assertMethods(TokenRedeemerInterface::class, [
            'peek' => '(string $plain, JardisSupport\Contract\Auth\TokenType $expected): '
                . 'JardisSupport\Contract\Auth\HashedTokenInterface',
            'redeem' => '(string $plain, JardisSupport\Contract\Auth\TokenType $expected): '
                . 'JardisSupport\Contract\Auth\HashedTokenInterface',
        ]);

        $hashed = $this->hashedToken();
        $redeemer = new class ($hashed) implements TokenRedeemerInterface {
            public function __construct(private readonly HashedTokenInterface $hashed)
            {
            }

            public function peek(string $plain, TokenType $expected): HashedTokenInterface
            {
                return $this->hashed;
            }

            public function redeem(string $plain, TokenType $expected): HashedTokenInterface
            {
                return $this->hashed;
            }
        };

        self::assertSame($hashed, $redeemer->peek('p', TokenType::Invitation));
        self::assertSame($hashed, $redeemer->redeem('p', TokenType::Invitation));
    }

    public function testAuthServicesInterfaceHasExactlyFourAccessors(): void
    {
        ContractShape::assertMethods(AuthServicesInterface::class, [
            'sessions' => '(): JardisSupport\Contract\Auth\SessionManagerInterface',
            'tokenIssuer' => '(): JardisSupport\Contract\Auth\TokenIssuerInterface',
            'tokenRedeemer' => '(): JardisSupport\Contract\Auth\TokenRedeemerInterface',
            'hasher' => '(): JardisSupport\Contract\Auth\PasswordHasherInterface',
        ]);

        $manager = $this->createStub(SessionManagerInterface::class);
        $issuer = $this->createStub(TokenIssuerInterface::class);
        $redeemer = $this->createStub(TokenRedeemerInterface::class);
        $hasher = $this->createStub(PasswordHasherInterface::class);

        $services = new class ($manager, $issuer, $redeemer, $hasher) implements AuthServicesInterface {
            public function __construct(
                private readonly SessionManagerInterface $sessions,
                private readonly TokenIssuerInterface $issuer,
                private readonly TokenRedeemerInterface $redeemer,
                private readonly PasswordHasherInterface $hasher,
            ) {
            }

            public function sessions(): SessionManagerInterface
            {
                return $this->sessions;
            }

            public function tokenIssuer(): TokenIssuerInterface
            {
                return $this->issuer;
            }

            public function tokenRedeemer(): TokenRedeemerInterface
            {
                return $this->redeemer;
            }

            public function hasher(): PasswordHasherInterface
            {
                return $this->hasher;
            }
        };

        self::assertSame($manager, $services->sessions());
        self::assertSame($issuer, $services->tokenIssuer());
        self::assertSame($redeemer, $services->tokenRedeemer());
        self::assertSame($hasher, $services->hasher());
    }

    public function testRoleAwareAuthResultInterface(): void
    {
        ContractShape::assertMethods(RoleAwareAuthResultInterface::class, [
            'isSuccess' => '(): bool',
            'getSubject' => '(): ?string',
            'getReason' => '(): ?string',
            'getRole' => '(): ?string',
        ]);

        $result = new class implements RoleAwareAuthResultInterface {
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

            public function getRole(): ?string
            {
                return 'admin';
            }
        };

        self::assertInstanceOf(AuthResultInterface::class, $result);
        self::assertSame('admin', $result->getRole());
    }
}
