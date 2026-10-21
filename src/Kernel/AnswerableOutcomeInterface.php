<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Kernel;

/**
 * An {@see OutcomeInterface} that carries everything the answer of the use case is built from.
 *
 * {@see OutcomeInterface} answers how a unit of work ended (status, failed or not) and is all the
 * transaction bracket needs. The package that builds the answer from that outcome
 * (`jardissupport/response`) needs more: the typed response, the collected events, the error texts, and -
 * for a reject - the message key and the plain data of the reject. They meet here without the answer
 * package knowing the package that ran the work.
 *
 * Separate from {@see OutcomeInterface} on purpose: a method added to a published interface would break
 * every implementor, so the larger need is a new interface beside it.
 */
interface AnswerableOutcomeInterface extends OutcomeInterface
{
    /**
     * The typed response of the unit of work; for a reject the response object of the node that rejected.
     */
    public function response(): object;

    /**
     * The error texts collected on the way.
     *
     * @return list<string>
     */
    public function errors(): array;

    /**
     * The events collected on the way, in the order they arose.
     *
     * @return list<object>
     */
    public function events(): array;

    /**
     * The message key of a reject; null for a finished unit of work.
     */
    public function messageKey(): ?string;

    /**
     * The plain data of a reject; empty for a finished unit of work.
     *
     * @return array<string, mixed>
     */
    public function context(): array;
}
