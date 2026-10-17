# Jardis Contracts

[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE.md)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D8.2-777BB4.svg)](https://www.php.net/)
[![PHPStan Level](https://img.shields.io/badge/PHPStan-Level%208-brightgreen.svg)](phpstan.neon)
[![PSR-12](https://img.shields.io/badge/Code%20Style-PSR--12-blue.svg)](phpcs.xml)

> Part of **[Jardis](https://jardis.io)** — the Domain-Driven Design platform for PHP. You model your domain; Jardis generates the production-ready hexagonal code (DTOs, Command/Query handlers, repositories, persistence). This package is part of the open-source foundation that generated code runs on.

Consolidated ports and adapters contracts for all Jardis packages — the interface layer that keeps hexagonal architecture honest across the entire ecosystem.

---

## Overview

This package provides all interface contracts for the Jardis ecosystem in a single location. It replaces the previously separate `jardisport/*` packages.

| Namespace | Contracts | Purpose |
|-----------|-----------|---------|
| `Auth` | 23 | UserLookup, CredentialRecord, SessionClaim (login lookup + session claim names), AuthResult, TenantAwareAuthResult, SessionAwareAuthResult, RequestAttribute (subject/subject-id/tenant/session attribute names), Authenticator, Credential(+Type), Guard, HashedToken, PasswordHasher, Session, TokenStore(+Type); since 2.11: AuthServices, SessionManager, TokenIssuer, TokenRedeemer, SessionResult, IssuedToken, RoleAwareAuthResult |
| `ClassVersion` | 2 | Versioned class resolution |
| `Connection` | 1 | Generic connection abstraction |
| `Data` | 3 | Hydration, Identity, FieldMapper |
| `DbConnection` | 4 | ConnectionPool, DbConnection, DatabaseConfig, Savepoint (additive: nested transaction brackets) |
| `DbQuery` | 17 | Query Builder, Conditions, Joins, Expressions, Results |
| `DotEnv` | 1 | Environment variable loading |
| `EventListener` | 1 | EventListenerRegistry (paired with PSR-14 EventDispatcher) |
| `Filesystem` | 6 | Filesystem (Reader/Writer/Service), FileInfo + Exception |
| `Identity` | 12 | **Deprecated since 2.11** — User/Tenant/Membership/Group/Invitation entity contracts, UserStatus (enum), and the six store ports (User, Tenant, Membership, Group, GroupMember, Invitation) |
| `Kernel` | 6 | DomainKernel, ContextResponse, DomainResponse, EventScope, ResponseStatus, GeneratedContextInterface (marker) |
| `Mailer` | 4 | Mailer, MailMessage, MailTransport + Exception |
| `Messaging` | 10 | Publisher, Consumer, MessageHandler + Exceptions |
| `Repository` | 6 | Generic CRUD, PkStrategy, Exceptions (Persist, RecordNotFound, UniqueViolation, TransientConflict) |
| `Scheduling` | 5 | Constraint, CronExpression, Schedule(+Violation), ScheduledTask |
| `Secret` | 2 | Secret resolution + Exception |
| `Validation` | 4 | Validator, ValueValidator, MissingValueValidator (marker), ValidationResult (errors + `kinds` tree: `missing`\|`invalid`; `toFields()` flat findings) |
| `Workflow` | 8 | Workflow engine + orchestration (Workflow, Builder, NodeBuilder, Config, Context, Result, Chain, AggregateResponse) — **deprecated seit 2.12.0, Ersatz `jardissupport/workflow` 2.0** (nur die 7 `Workflow*Interface`; `AggregateResponse` bleibt) — 7 named transitions: `onSuccess`, `onFail`, `onTimeout`, `onSkip`, `onCancel`, `onEvent`, `onExit` |

**115 contracts** across 18 domains.

---

## Installation

```bash
composer require jardissupport/contracts
```

---

## Namespace

```
JardisSupport\Contract\
├── Auth\
├── ClassVersion\
├── Connection\
├── Data\
├── DbConnection\
├── DbQuery\
├── DotEnv\
├── EventListener\
├── Filesystem\
├── Identity\
├── Kernel\
├── Mailer\
├── Messaging\
│   └── Exception\
├── Repository\
│   ├── Exception\
│   └── PrimaryKey\
├── Scheduling\
├── Secret\
├── Validation\
└── Workflow\
```

---

## Kernel — v2 additions (Kernel-Entkopplung)

Three additions to `Kernel\` that let a generated domain drop its compile-time
dependency on `jardiscore/kernel`'s base classes while keeping the same
runtime vocabulary.

### `ResponseStatus` enum

`JardisSupport\Contract\Kernel\ResponseStatus` — an `int`-backed enum of
domain-neutral response status codes (`Success = 200`, `Created = 201`,
`NoContent = 204`, `ValidationError = 400`, `Unauthorized = 401`,
`Forbidden = 403`, `NotFound = 404`, `MethodNotAllowed = 405`, `Conflict = 409`,
`RuleViolation = 422`, `InternalError = 500`), ported 1:1 from `jardiscore/kernel`'s
`JardisCore\Kernel\Response\ResponseStatus`. Generated domains import it from
here instead of from `jardiscore/kernel`.

### `DomainKernelInterface::eventListenerRegistry()`

The Kernel interface gained an 11th accessor:
`eventListenerRegistry(): ?EventListenerRegistryInterface`. It is paired with
`eventDispatcher()` — both are typically backed by the same underlying
provider instance, one side for dispatching (PSR-14), one side for
registering listeners. Generated `{Agg}EventRouter` scaffolds use this
accessor to self-register on the domain facade's constructor. Nullable like
every other service on the interface: without a registry, event routing
simply stays inactive.

### `GeneratedContextInterface` marker

`JardisSupport\Contract\Kernel\GeneratedContextInterface` is an empty marker
interface. Every generated `{Domain}Context` implements it, so the resolve
path recognizes generated context classes via
`is_subclass_of($resolved, GeneratedContextInterface::class)` — independent
of domain boundaries (cross-domain services included) and without requiring
a shared package base class to extend.

It took over the detection role of `BoundedContextInterface`, which was
removed from this package in the Kernel-Entkopplung (2026-07) together with
the `jardiscore/kernel` `BoundedContext` base class it referred to. Generated
contexts implement this marker instead of extending a package class.

---

## Kernel — v2.0.0 (env-konfiguration)

Two changes to `DomainKernelInterface`, released together as v2.0.0:

- **`domainRoot()` → `projectRoot()` (BREAKING).** The accessor is renamed
  and now names what it always carried in practice: the project root (the
  git-clone target), from which the `<projectRoot>/config/env` convention
  derives — not a config path.
- **`messaging(): ?MessagingServiceInterface` (new, twelfth accessor).**
  Publish/consume across the transports of `jardisadapter/messaging`
  (Kafka, RabbitMQ, Redis). Nullable like every other service accessor:
  without a configured transport, messaging simply stays inactive.

---

## Repository exceptions — `UniqueViolationException` (v2.5.0)

`Repository\Exception\UniqueViolationException` (subtype of `PersistException`) tells the caller **which** unique
constraint failed:

| Getter | Type | Meaning |
|---|---|---|
| `getConstraint()` | `?string` | Constraint/index name, `null` if the driver does not expose it |
| `getColumns()` | `list<string>` | Violated columns. **`[]` = unknown** (driver/dialect delivers no columns) — never a partial list |
| `getTable()` | `?string` | Table of the violated constraint, `null` if unknown |

The constructor is `(message, constraint, previous, columns = [], table = null)` — the former three-argument call is
unchanged (additive, minor).

```php
try {
    $repository->insert($row);
} catch (UniqueViolationException $e) {
    $columns = $e->getColumns();   // e.g. ['email'] — [] when unknown
    $table = $e->getTable();       // e.g. 'users' — null when unknown
}
```

### `TransientConflictException` (v2.6.0)

`Repository\Exception\TransientConflictException` (subtype of `PersistException`) marks a **transient concurrency
conflict** of the database — snapshot/serialization conflict or deadlock. The statement was rejected, not wrong:
repeating the whole unit of work in a **new** transaction can succeed. Without this type a caller cannot tell such a
conflict from a technical failure.

| Member | Type | Meaning |
|---|---|---|
| `getDriverCode()` | `?string` | Driver error code / SQLSTATE as text, `null` if the driver does not expose it. Examples (not exhaustive): `'1020'` (MariaDB "record has changed since last read"), `'1213'` (deadlock), `'40001'` (serialization failure), `'40P01'` (PostgreSQL deadlock) |
| `getPrevious()` | `?Throwable` | The original driver exception |

The constructor is `(message = '', driverCode = null, previous = null)`. Purely additive (minor): every existing
`catch (PersistException)` still catches it — put the more specific `catch` first.

```php
try {
    $repository->update($row);
} catch (TransientConflictException $e) {
    $code = $e->getDriverCode();   // e.g. '1020' — null when unknown
    // retry the unit of work in a new transaction, or answer "conflict"
} catch (PersistException $e) {
    // technical failure
}
```

---

## Validation — `ValidationResult::toFields()` (v2.7.0)

`ValidationResult` flattens its error tree into a list of findings, so a caller does not re-implement the walk over
`getErrors()` + `getKinds()`:

```php
$fields = $result->toFields();          // list<array{field: string, reason: string, message: string}>
$fields = $result->toFields('order');   // top-level segment "order" carries no path prefix
```

| Member | Meaning |
|---|---|
| `field` | Dot path of the string keys down to the message. List indices (int keys) add no segment of their own |
| `reason` | `ValidationResult::REASON_MISSING` (`'missing'`) exactly when the validator kind is `KIND_MISSING`, otherwise `REASON_INVALID` (`'invalid'`). A finding without a kind is `invalid`; there is no text fallback |
| `message` | The validator text as delivered — may be `''` |
| `$root` | A **top-level** segment equal to `$root` is stripped from the path (aggregate root); other top-level segments stay in it. `null` strips nothing |

```php
// errors: ['order' => ['email' => ['Required'], 'line' => ['qty' => ['Too small'], 0 => 'x']]]
// kinds:  ['order' => ['email' => ['missing'],  'line' => ['qty' => ['invalid'], 0 => 'invalid']]]
$result->toFields();
// [['field' => 'order.email',    'reason' => 'missing', 'message' => 'Required'],
//  ['field' => 'order.line.qty', 'reason' => 'invalid', 'message' => 'Too small'],
//  ['field' => 'order.line',     'reason' => 'invalid', 'message' => 'x']]
```

Answer texts and message keys stay with the caller: the package delivers path, reason and validator text only. A
caller that needs a default text for an empty `message` (or a `messageKey` per reason) maps `reason` itself.
Purely additive (minor).

---

## Auth — token session: `RequestAttribute` and `TenantAwareAuthResultInterface` (v2.8.0)

Additive, no breaking change. A transport layer that resolves an access token to a session
stores the result under two request attributes whose names are defined exactly once here:

```php
use JardisSupport\Contract\Auth\RequestAttribute;
use JardisSupport\Contract\Auth\TenantAwareAuthResultInterface;

RequestAttribute::SUBJECT; // 'jardisSubject' - authenticated subject identifier
RequestAttribute::TENANT;  // 'jardisTenant'  - tenant of the session (string|null)

if ($result instanceof TenantAwareAuthResultInterface) {
    $tenant = $result->getTenant(); // ?string
}
```

`TenantAwareAuthResultInterface extends AuthResultInterface` adds `getTenant(): ?string`.
`AuthResultInterface` itself is unchanged, so existing implementers stay valid. `jardissupport/auth`
v1.2.0 ships the implementation (`TokenAuthenticator`).

## Auth — subject id and session reference: `SessionAwareAuthResultInterface` (v2.9.0)

Additive, no breaking change. A token session resolves to more than the qualified subject:
consumers no longer split `user:42` themselves and can revoke exactly the session they were
authenticated with.

```php
use JardisSupport\Contract\Auth\RequestAttribute;
use JardisSupport\Contract\Auth\SessionAwareAuthResultInterface;

RequestAttribute::SUBJECT_ID; // 'jardisSubjectId' - bare subject identifier ('42' from 'user:42')
RequestAttribute::SESSION;    // 'jardisSession'   - session reference (hash of the access token)

if ($result instanceof SessionAwareAuthResultInterface) {
    $id        = $result->getSubjectId();        // ?string
    $reference = $result->getSessionReference(); // ?string
}
```

`SessionAwareAuthResultInterface extends TenantAwareAuthResultInterface` adds `getSubjectId(): ?string`
and `getSessionReference(): ?string`; existing implementers stay valid. `jardissupport/auth` v1.3.0
ships the implementation (`TokenAuthenticator`, `SessionManager::invalidateByReference()`).

## Auth — login lookup and session claims: `UserLookupInterface`, `CredentialRecordInterface`, `SessionClaim` (v2.10.0)

Additive, no breaking change. A password login resolves an identifier to credential data through a
port, and the session claim names are defined once.

```php
use JardisSupport\Contract\Auth\CredentialRecordInterface;
use JardisSupport\Contract\Auth\SessionClaim;
use JardisSupport\Contract\Auth\UserLookupInterface;

SessionClaim::TENANT; // 'tenant' - tenant of the session
SessionClaim::ROLE;   // 'role'   - role of the subject within the tenant

/** @var UserLookupInterface $lookup */
$record = $lookup->lookup('anna@example.com'); // ?CredentialRecordInterface
if ($record !== null) {
    $hash    = $record->getPasswordHash(); // string
    $subject = $record->getSubject();      // 'user:42' (format type:id)
    $claims  = $record->getClaims();       // array<string, mixed>, e.g. [SessionClaim::TENANT => 't1']
}
```

`jardissupport/identity` v1.0 and `jardissupport/auth` v1.4 ship the implementations.

## Identity - users, tenants, memberships, groups, invitations (v2.10.0) — DEPRECATED since 2.11.0

> **Deprecated (2.11.0):** Identity is a generic subdomain; it is replaced by the Builder blueprint "Access".
> All 12 `Identity\*` contracts carry `@deprecated 2.11.0`. Nothing is removed — removal is reserved for the next major.

Additive, new namespace `JardisSupport\Contract\Identity`. Entity contracts are read-only views;
the password hash is deliberately **not** part of `UserInterface` (only `UserStoreInterface::passwordHashOf()`
and `CredentialRecordInterface` expose it). Invitations hold the token hash only.

```php
use JardisSupport\Contract\Identity\InvitationStoreInterface;
use JardisSupport\Contract\Identity\UserStatus;
use JardisSupport\Contract\Identity\UserStoreInterface;

/** @var UserStoreInterface $users */
$users->updateStatus($id, UserStatus::Disabled);
$hash = $users->passwordHashOf($id); // ?string

/** @var InvitationStoreInterface $invitations */
$accepted = $invitations->markAccepted($invitationId, new DateTimeImmutable()); // false if already accepted or revoked
```

Ports: `UserStoreInterface`, `TenantStoreInterface`, `MembershipStoreInterface`, `GroupStoreInterface`,
`GroupMemberStoreInterface`, `InvitationStoreInterface`. `jardissupport/identity` v1.0 ships the implementation.

## Auth services (2.11)

Additive. A generated process node reaches `auth` through `DomainKernelInterface::auth(): ?AuthServicesInterface`
(`null` if the app wires no `auth`) without knowing a concrete class. The contracts carry no data classes;
domain events of the session lifecycle stay internal to the implementing package. Subjects are qualified
strings `type:id`.

| Contract | Purpose |
|----------|---------|
| `AuthServicesInterface` | Four accessors: `sessions()`, `tokenIssuer()`, `tokenRedeemer()`, `hasher()` |
| `SessionManagerInterface` | `create(string $subject, array $claims = [])`, `refresh(string $refreshToken)` (both return `SessionResultInterface`), `invalidateByReference(string $sessionReference)`, `invalidateAll(string $subject)` |
| `SessionResultInterface` | `getAccessToken()`, `getRefreshToken()`, `getSession(): SessionInterface` |
| `TokenIssuerInterface` | `issue(TokenType $type, string $subject, int $ttlSeconds, array $claims = []): IssuedTokenInterface` |
| `IssuedTokenInterface` | `getPlain()` (hand out once), `getHashed(): HashedTokenInterface` |
| `TokenRedeemerInterface` | `peek(string $plain, TokenType $expected)` (find + validate, no revocation), `redeem(...)` (validate + revoke once) |
| `RoleAwareAuthResultInterface` | extends `AuthResultInterface`, adds `getRole(): ?string` (claim `SessionClaim::ROLE`) |

Also new: `TokenType::Invitation` (`'invitation'`) and `RequestAttribute::ROLE` (`'jardisRole'`, set only when the
access token carries the role claim). `DomainKernelInterface::auth()` is a new method on a published interface:
kernel implementors must add it.

```php
use JardisSupport\Contract\Auth\TokenType;

$auth = $kernel->auth();                       // ?AuthServicesInterface
$issued = $auth?->tokenIssuer()->issue(TokenType::Invitation, 'user:42', 86400);
$token  = $auth?->tokenRedeemer()->peek($issued->getPlain(), TokenType::Invitation); // no revocation
```

## Auth — session expiry: `SessionInterface::getExpiresAt()` (2.12)

`SessionInterface` carries the expiry, so a consumer reads it through the contract and never from an implementation class:

```php
use JardisSupport\Contract\Auth\SessionInterface;

$expiresAt = $session->getExpiresAt(); // ?DateTimeImmutable - null = the session does not expire
```

This is a new method on a published interface: every `SessionInterface` implementor must add it.
`jardissupport/auth` v1.7.0 ships the implementation (`Data\Session`).

## Design Principles

- **One package, all contracts** — single dependency for the entire Jardis ecosystem
- **Only PSR standards at the Kernel level** — PSR-3, PSR-11, PSR-14, PSR-16, PSR-18, PSR-20 (`psr/clock`) + PDO
- **No implementation code** — interfaces, enums, value objects and exception classes only
- **Hexagonal Architecture** — contracts define ports, implementations live in adapter/support packages

---

## Migration from jardisport/*

Replace namespace imports:

```php
// Before
use JardisPort\Kernel\DomainKernelInterface;
use JardisPort\DbQuery\DbQueryBuilderInterface;

// After
use JardisSupport\Contract\Kernel\DomainKernelInterface;
use JardisSupport\Contract\DbQuery\DbQueryBuilderInterface;
```

Replace composer dependency:

```json
// Before
"require": {
    "jardisport/kernel": "^1.0",
    "jardisport/dbquery": "^1.0"
}

// After
"require": {
    "jardissupport/contracts": "^1.0"
}
```

---

## Related Packages

| Package | Role |
|---------|------|
| `jardiscore/kernel` | `DomainKernel` (the DomainKernel) — implements the `Kernel\*` contracts, provides the 11 infrastructure accessors |
| `jardiscore/app` | HTTP-Delivery layer — FastRoute router behind its own interface, PSR-15 middleware pipeline, `DomainResponse` → PSR-7 mapper, DomainKernel bootstrap bridge |
| Generated `{Domain}Context` | Domain Layer (Builder output) — implements `Kernel\GeneratedContextInterface`, consumes `DomainKernelInterface` via `resource()` |
| `jardissupport/*` | Support package implementations |
| `jardisadapter/*` | Adapter implementations for external systems |

---

## Documentation

Full documentation, guides, and API reference:

**[docs.jardis.io/en/support/contracts](https://docs.jardis.io/en/support/contracts)**

---

## License

Jardis is open source under the [MIT License](LICENSE.md).
Free for any purpose — commercial or non-commercial.

---

*Jardis – Development with Passion*
*Built by [Headgent Development](https://headgent.com)*
