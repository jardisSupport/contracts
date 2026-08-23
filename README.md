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
| `Auth` | 10 | AuthResult, Authenticator, Credential(+Type), Guard, HashedToken, PasswordHasher, Session, TokenStore(+Type) |
| `ClassVersion` | 2 | Versioned class resolution |
| `Connection` | 1 | Generic connection abstraction |
| `Data` | 3 | Hydration, Identity, FieldMapper |
| `DbConnection` | 3 | ConnectionPool, DbConnection, DatabaseConfig |
| `DbQuery` | 17 | Query Builder, Conditions, Joins, Expressions, Results |
| `DotEnv` | 1 | Environment variable loading |
| `EventListener` | 1 | EventListenerRegistry (paired with PSR-14 EventDispatcher) |
| `Filesystem` | 6 | Filesystem (Reader/Writer/Service), FileInfo + Exception |
| `Kernel` | 6 | DomainKernel, ContextResponse, DomainResponse, EventScope, ResponseStatus, GeneratedContextInterface (marker) |
| `Mailer` | 4 | Mailer, MailMessage, MailTransport + Exception |
| `Messaging` | 10 | Publisher, Consumer, MessageHandler + Exceptions |
| `Repository` | 4 | Generic CRUD, PkStrategy, Exceptions |
| `Scheduling` | 5 | Constraint, CronExpression, Schedule(+Violation), ScheduledTask |
| `Secret` | 2 | Secret resolution + Exception |
| `Validation` | 3 | Validator, ValueValidator, ValidationResult |
| `Workflow` | 8 | Workflow engine + orchestration (Workflow, Builder, NodeBuilder, Config, Context, Result, Chain, AggregateResponse) — 7 named transitions: `onSuccess`, `onFail`, `onTimeout`, `onSkip`, `onCancel`, `onEvent`, `onExit` |

**86 contracts** across 17 domains.

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

## Design Principles

- **One package, all contracts** — single dependency for the entire Jardis ecosystem
- **Only PSR standards at the Kernel level** — PSR-3, PSR-11, PSR-14, PSR-16, PSR-18 + PDO
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
