<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Kernel;

/**
 * The outcome of one unit of work: its status, and whether that status is a failure.
 *
 * A package that runs work and a package that must react to how the work ended meet here without
 * knowing each other: the process engine returns an outcome, the transaction bracket decides on
 * commit or rollback from it, the answer of the use case is built from it. Nothing else belongs on
 * this interface - payload, events and errors stay with the implementation.
 *
 * "Failed" means the unit of work did not reach its goal (status 400 and above, the same line
 * {@see DomainResponseInterface} draws for a response). It is answered by the outcome itself, so no
 * caller compares status numbers.
 */
interface OutcomeInterface
{
    /**
     * The status the unit of work ended with.
     */
    public function status(): ResponseStatus;

    /**
     * True when the unit of work failed (status 400 and above); false for a success.
     */
    public function isFailed(): bool;
}
