<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Workflow;

/**
 * Interface for the execution context passed through a workflow's handler chain.
 *
 * Extends the narrow {@see WorkflowChainInterface} protocol (append + lookups
 * that the engine relies on) with three opaque mantle slots —
 * reference/response/exception — that carry companion state between the flow's
 * entry/final companions and the routing graph.
 *
 * Replaces the prior $result/$callStack tuple returned by Workflow::__invoke().
 */
interface WorkflowContextInterface extends WorkflowChainInterface
{
    /**
     * Returns the mantle reference slot — opaque payload set by the flow's
     * entry companion (or any handler) and consumed by downstream handlers
     * and the final companion. `null` when never set.
     */
    public function reference(): mixed;

    /**
     * Stores a value in the mantle reference slot. Idempotent and last-write-wins.
     */
    public function setReference(mixed $value): void;

    /**
     * Returns the mantle response slot — opaque payload typically built up by
     * the flow's final companion and read by the orchestrator's caller.
     * `null` when never set.
     */
    public function response(): mixed;

    /**
     * Stores a value in the mantle response slot. Idempotent and last-write-wins.
     */
    public function setResponse(mixed $value): void;

    /**
     * Returns the exception captured by the orchestrator when the routing graph
     * threw, or null when the run completed cleanly. The final companion may
     * inspect this slot to render a partial/error response.
     */
    public function getException(): ?\Throwable;

    /**
     * Records an exception thrown by the routing graph. The orchestrator calls
     * this before re-throwing so the final companion can observe it.
     */
    public function setException(\Throwable $e): void;
}
