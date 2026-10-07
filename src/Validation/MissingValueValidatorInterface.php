<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Validation;

/**
 * Marker for value validators that report an ABSENT value.
 *
 * A value validator that reports an ABSENT value — its failure is reported as
 * reason `missing`; every other validator's failure is `invalid`.
 */
interface MissingValueValidatorInterface
{
}
