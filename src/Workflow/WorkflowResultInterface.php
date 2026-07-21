<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Workflow;

/**
 * Interface for workflow handler result objects.
 *
 * Provides explicit control over workflow transitions by requiring handlers
 * to return a WorkflowResult instead of arbitrary values. This eliminates
 * ambiguity in truthy/falsy evaluation and enables named transitions.
 *
 * Implementations should support:
 * - Named transitions only (onSuccess, onFail, onTimeout, onSkip, onCancel, onEvent, onExit)
 * - Data payload from handler execution
 * - Handler-FQCN stamping by the workflow engine
 */
interface WorkflowResultInterface
{
    /** Erfolgreicher Abschluss des Handlers. */
    public const ON_SUCCESS = 'onSuccess';

    /** Fachlicher Misserfolg (Validierung, Geschaeftsregel). */
    public const ON_FAIL = 'onFail';

    /** Geplanter Recovery-Pfad: Service-Side-Timeout in fachliches Routing uebersetzt. */
    public const ON_TIMEOUT = 'onTimeout';

    /** Handler nicht anwendbar — Flow ueberspringt zum Re-Konvergenz-Punkt. */
    public const ON_SKIP = 'onSkip';

    /** Fachlicher Abbruch (Stornierung, Zustimmung zurueckgezogen) — Cleanup-Pfad. */
    public const ON_CANCEL = 'onCancel';

    /** Aktiver async-Hand-off via DomainEvent — Folge-Runs entstehen extern. */
    public const ON_EVENT = 'onEvent';

    /** Schleife/Block terminiert — weiter im umgebenden Flow (Loop-Exit-Kante). */
    public const ON_EXIT = 'onExit';

    /**
     * Returns the result status.
     *
     * Always one of the seven ON_* transition constants
     * (ON_SUCCESS, ON_FAIL, ON_TIMEOUT, ON_SKIP, ON_CANCEL, ON_EVENT, ON_EXIT).
     */
    public function getStatus(): string;

    /**
     * Returns the optional data payload from the handler.
     */
    public function getData(): mixed;

    /**
     * Returns the FQCN of the handler that produced this result, or null when
     * the result has not yet been stamped by the workflow engine.
     *
     * The engine stamps a result during {@see WorkflowContextInterface::append()}
     * via {@see self::withHandler()} so consumers can identify the origin of
     * each entry in the flat execution chain.
     */
    public function getHandlerFqcn(): ?string;

    /**
     * Returns a new immutable instance stamped with the given handler FQCN.
     * All other fields are preserved. Existing FQCN (if any) is replaced.
     *
     * @param class-string $fqcn
     */
    public function withHandler(string $fqcn): static;
}
