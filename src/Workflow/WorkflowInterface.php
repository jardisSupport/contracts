<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Workflow;

use Exception;

/**
 * Workflow interface for orchestrating multi-step processes.
 *
 * Executes a configured chain of workflow nodes with conditional branching.
 * The engine is stateless and single-shot: each invocation creates a fresh
 * WorkflowContext and returns it to the caller. Iteration over inputs and any
 * aggregation across multiple runs are responsibilities of the caller (e.g.
 * an Aggregate-Facade or a flow's entry/final companion).
 *
 * Handler instantiation is delegated to a factory closure that receives both
 * the handler FQCN and the optional per-run $data. This lets the caller wire
 * the engine into a context-aware factory (typically via context() to spawn a
 * fresh generated `{Domain}Context` with $data as payload) without the engine
 * knowing about domain context semantics.
 *
 * Each handler is invoked with the WorkflowContextInterface as its single
 * argument and must return a WorkflowResultInterface. The engine stamps the
 * result with the handler's FQCN before appending it to the context's flat
 * execution chain.
 */
interface WorkflowInterface
{
    /**
     * Executes the workflow with the given configuration and optional input data.
     *
     * @param WorkflowConfigInterface $workflowConfig The workflow configuration with nodes
     * @param mixed $data Optional per-run input. Passed to the handler factory alongside
     *                    the handler class name; typically used by callers to spawn a fresh
     *                    generated `{Domain}Context` with $data as payload (so handlers see
     *                    it via $this->payload()). Null when the run has no extra input
     *                    beyond whatever the outer context already carries.
     * @return WorkflowContextInterface The execution context with the full result chain
     * @throws Exception If workflow execution fails
     */
    public function __invoke(
        WorkflowConfigInterface $workflowConfig,
        mixed $data = null,
    ): WorkflowContextInterface;
}
