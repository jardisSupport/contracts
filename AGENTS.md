# jardissupport/contracts

All Jardis interface contracts in one package — ports for Auth, ClassVersion, Connection, Data, DbConnection, DbQuery, DotEnv, EventListener, Filesystem, Kernel, Mailer, Messaging, Repository, Scheduling, Secret, Validation and Workflow (87 contracts across 17 namespaces). Interfaces, enums, `final readonly` value objects and exception classes only — no implementation code.

## Usage essentials

- **Package name vs. namespace:** Composer package `jardissupport/contracts` (**plural**), namespace `JardisSupport\Contract\*` (**singular**) — package name ≠ namespace, that is not a contradiction. `jardissupport/contract` (singular) is the superseded predecessor; always `composer require jardissupport/contracts`.
- **Type-hint against the contract, never the implementation** — a domain/adapter package declares the port here and implements it in its own package; the dependency arrow points inward to the contract.
- **Enums you will import:** `Kernel\ResponseStatus` (int-backed, `Success` 200 … `RuleViolation` 422, `InternalError` 500), `Kernel\EventScope` (`Internal`/`Domain`), `Repository\PrimaryKey\PkStrategy`, `Auth\CredentialType`, `Auth\TokenType`.
- **Kernel contracts for generated code:** `DomainKernelInterface` (12 accessors incl. `eventListenerRegistry()` and `messaging()`; `projectRoot()` was renamed from `domainRoot()` in v2.0.0), `GeneratedContextInterface` (deliberately empty marker implemented by every generated `{Domain}Context`), `DomainResponseInterface`, `ContextResponseInterface`. Generated domains import these from here, not from `jardiscore/kernel`.
- **PSR boundary:** PSR interfaces (PSR-3, 11, 14, 16, 18) are not re-declared here; Jardis declares its own contract only where no PSR exists.
- **Don't:** add a method to a published interface casually — it is a breaking change for every implementor (coordinated fleet release); put implementation code or tests into this package; require the singular `jardissupport/contract`.
- **Skill:** `support-contracts` (`.claude/skills/support-contracts/SKILL.md`) — consult it before using the API.

## Full reference

https://docs.jardis.io/en/support/contracts
