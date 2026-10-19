<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Kernel;

use Throwable;

/**
 * Marker for a failure whose cause was already reported where it arose.
 *
 * An exception implementing this interface carries a failure that the inner door (the
 * component that owned the cause) has already logged. A delivery layer or door that
 * catches it maps the status as usual but does not log the cause a second time.
 */
interface ReportedFailureExceptionInterface extends Throwable
{
}
