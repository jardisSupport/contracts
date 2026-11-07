<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Data;

/**
 * A field the caller did not deliver in a partial write.
 *
 * Absent differs from null: null is a delivered value and clears a nullable
 * field, Absent leaves the field as it is. Validation skips the field and
 * column mapping omits it.
 *
 * This is the state of a write payload. A validator that reports a missing
 * value is a different concept, see
 * \JardisSupport\Contract\Validation\MissingValueValidatorInterface.
 *
 * Pure enum on purpose: a backed case would be written as its value by the
 * hydration of BackedEnum properties.
 */
enum Absent
{
    case Value;
}
