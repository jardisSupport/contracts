---
name: support-contracts
description: All Jardis interface contracts in one package — ports for Auth, ClassVersion, Connection, Data, DbConnection, DbQuery, DotEnv, EventListener, Filesystem, Kernel, Mailer, Messaging, Repository, Scheduling, Secret, Validation, Workflow. Use when type-hinting against a Jardis port, importing ResponseStatus/EventScope/PkStrategy, resolving JardisSupport\Contract\* namespaces, or deciding between package name jardissupport/contracts (plural) and namespace JardisSupport\Contract (singular).
user-invocable: false
zone: post-active
persona: C
prerequisites: [rules-architecture, rules-patterns]
next: []
---

# CONTRACTS_COMPONENT_SKILL
> jardissupport/contracts | NS: `JardisSupport\Contract` | PHP 8.2+ | MIT

## PACKAGE NAME vs. NAMESPACE — read this first
| | Value |
|---|---|
| Composer package | `jardissupport/contracts` — **PLURAL** |
| PSR-4 namespace | `JardisSupport\Contract\*` — **SINGULAR** |
| Install | `composer require jardissupport/contracts` |

**Trap:** `jardissupport/contract` (singular) is the superseded predecessor. It still resolves on
Packagist up to `v2.1.0` — a **higher** number than the valid plural package — so the wrong
`composer require` looks plausible and pulls dead code. Always require the **plural** package; the
namespace stays **singular**. Package name ≠ namespace, that is not a contradiction.

## WHAT IS IN HERE
Contracts only — interfaces, enums, `final readonly` value objects and exception classes. **Zero
implementation code, no tests.** Implementations live in the adapter/support packages; this package
is the port layer that keeps the hexagonal dependency direction honest across the ecosystem.

86 contracts across 17 namespaces under `src/`.

## CONTRACT GROUPS
| Namespace | # | Contracts | Implemented by |
|---|---|---|---|
| `Auth` | 10 | `AuthResultInterface`, `AuthenticatorInterface`, `CredentialInterface`, `CredentialType` (enum), `GuardInterface`, `HashedTokenInterface`, `PasswordHasherInterface`, `SessionInterface`, `TokenStoreInterface`, `TokenType` (enum) | `support-auth` |
| `ClassVersion` | 2 | `ClassVersionInterface`, `ClassVersionConfigInterface` | `support-classversion` |
| `Connection` | 1 | `ConnectionInterface` — generic connection abstraction | — |
| `Data` | 3 | `HydrationInterface`, `IdentityInterface`, `FieldMapperInterface` | `support-data` |
| `DbConnection` | 3 | `DbConnectionInterface`, `ConnectionPoolInterface`, `DatabaseConfigInterface` | `adapter-dbconnection` |
| `DbQuery` | 17 | `DbQueryBuilderInterface`, `DbInsertBuilderInterface`, `DbUpdateBuilderInterface`, `DbDeleteBuilderInterface`, `DbQueryConditionBuilderInterface`, `DbQueryJsonConditionBuilderInterface`, `DbWhereConditionInterface`, `DbComparisonOperatorsInterface`, `DbJoinInterface`, `DbOrderLimitInterface`, `DbWindowBuilderInterface`, `DbQueryExistsInterface`, `DbSqlGeneratorInterface`, `DbPreparedQueryInterface`, `ExpressionInterface`, `QueryResultInterface`, `ExecuteResultInterface` | `support-dbquery` |
| `DotEnv` | 1 | `DotEnvInterface` | `support-dotenv` |
| `EventListener` | 1 | `EventListenerRegistryInterface` — registration side, paired with the PSR-14 dispatcher | `adapter-eventdispatcher` |
| `Filesystem` | 6 | `FilesystemInterface`, `FilesystemReaderInterface`, `FilesystemWriterInterface`, `FilesystemServiceInterface`, `FileInfoInterface`, `FilesystemExceptionInterface` | `adapter-filesystem` |
| `Kernel` | 6 | `DomainKernelInterface`, `GeneratedContextInterface`, `ContextResponseInterface`, `DomainResponseInterface`, `EventScope` (enum), `ResponseStatus` (enum) | `core-kernel` + generated domain |
| `Mailer` | 4 | `MailerInterface`, `MailMessageInterface`, `MailTransportInterface`, `MailerExceptionInterface` | `adapter-mailer` |
| `Messaging` | 10 | `MessagePublisherInterface`, `MessageConsumerInterface`, `PublisherInterface`, `ConsumerInterface`, `MessageHandlerInterface`, `MessagingServiceInterface` + `Exception\{MessageException, ConnectionException, ConsumerException, PublishException}` | `adapter-messaging` |
| `Repository` | 4 | `RepositoryInterface`, `PrimaryKey\PkStrategy` (enum), `Exception\{PersistException, RecordNotFoundException}` | `support-repository` |
| `Scheduling` | 5 | `ScheduleInterface`, `ScheduledTaskInterface`, `CronExpressionInterface`, `ConstraintInterface`, `ScheduleViolation` (final readonly) | `support-scheduling` |
| `Secret` | 2 | `SecretResolverInterface`, `SecretResolutionException` | `support-secret` |
| `Validation` | 3 | `ValidatorInterface`, `ValueValidatorInterface`, `ValidationResult` (final readonly) | `support-validation` |
| `Workflow` | 8 | `WorkflowInterface`, `WorkflowBuilderInterface`, `WorkflowNodeBuilderInterface`, `WorkflowConfigInterface`, `WorkflowContextInterface`, `WorkflowResultInterface`, `WorkflowChainInterface`, `AggregateResponse` | `support-workflow` |

## PSR BOUNDARY
PSR interfaces are **not** re-declared here — they are required as dependencies and used directly:
`psr/container` (PSR-11), `psr/simple-cache` (PSR-16), `psr/log` (PSR-3),
`psr/event-dispatcher` (PSR-14), `psr/http-client` (PSR-18), plus `ext-pdo`.
Jardis declares its own contract only where no PSR exists (Mailer, Filesystem, Messaging,
Repository, DbQuery, Workflow, Auth, …).

## ENUMS YOU WILL ACTUALLY IMPORT
```php
use JardisSupport\Contract\Kernel\ResponseStatus;   // int-backed
// Success 200 · Created 201 · NoContent 204 · ValidationError 400 · Unauthorized 401
// Forbidden 403 · NotFound 404 · MethodNotAllowed 405 · Conflict 409
// RuleViolation 422 · InternalError 500

use JardisSupport\Contract\Kernel\EventScope;       // string-backed
// Internal = 'internal'  — aggregate-level, stays within the system
// Domain   = 'domain'    — announced, leaves the system

use JardisSupport\Contract\Repository\PrimaryKey\PkStrategy;  // pure enum
// AUTOINCREMENT · INTEGER · NONE

use JardisSupport\Contract\Auth\CredentialType;     // string-backed
use JardisSupport\Contract\Auth\TokenType;          // string-backed
```

## KERNEL CONTRACTS — GENERATED-CODE SURFACE
- `DomainKernelInterface` — the DomainKernel contract: 11 nullable service accessors, among them
  `eventListenerRegistry(): ?EventListenerRegistryInterface`, the registration counterpart to
  `eventDispatcher()`. Without a registry, event routing simply stays inactive.
- `GeneratedContextInterface` — deliberately **empty marker**. Every generated `{Domain}Context`
  implements it — no shared base class needed and independent of domain boundaries. There is no
  `BoundedContextInterface` in this package.
- `DomainResponseInterface` / `ContextResponseInterface` — the response vocabulary that generated
  domains and the delivery layer share.

Generated domains import `ResponseStatus` and the Kernel contracts **from here**, not from
`jardiscore/kernel` — that is what lets a generated domain drop the compile-time dependency on the
kernel package's base classes.

## USAGE RULES
- **Type-hint against the contract, never the implementation** — `RepositoryInterface`, not the
  concrete repository class. Constructor injection, per `rules-architecture` (five pillars, §5).
- A **domain/adapter package declares the port here** and implements it in its own package —
  the dependency arrow points inward to the contract, never the other way round.
- Adding a method to a published interface is a **breaking change** for every implementor —
  treat contract edits as a coordinated fleet release (see `do-git-update`).
- Exceptions in this package are concrete classes on purpose (`MessageException` hierarchy,
  `PersistException`, `RecordNotFoundException`, `SecretResolutionException`) so callers can catch
  them without depending on an implementation package.

## LAYER
- **Domain / generated code:** imports Kernel + Data + Repository + Workflow contracts.
- **Application:** type-hints ports; never imports an adapter package directly.
- **Infrastructure (adapter/support):** implements the ports declared here.

## REFERENCE
- Package README: `/Users/Rolf/Development/headgent/jardis/support/contracts/README.md`
- Sibling package skills: `core-kernel`, `support-repository`, `support-workflow`, `support-data`,
  `adapter-messaging`, `adapter-filesystem`, `support-validation`
- Architecture rules: `rules-architecture`, `rules-patterns`
