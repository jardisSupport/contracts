<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Workflow;

/**
 * Marker for a typed, read-only aggregate read-projection ("{Agg}Response").
 *
 * A process orchestrator collects the aggregates it reads as one ordered
 * list<AggregateResponse> — multiple aggregate types may be mixed while their
 * order is preserved. The type-blind process middle discriminates by object
 * type (instanceof), so this marker carries no methods on purpose: it imposes
 * no identity assumption (the PK-fallback case has no common key) and leaves
 * each generated {Agg}Response free to expose its own shape.
 */
interface AggregateResponse
{
}
