<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Kernel;

use Throwable;

/**
 * Marker for a failed input validation of a command.
 *
 * Generated command validation throws an exception implementing this interface when the
 * input of a command is rejected. A delivery layer catches the interface, not the generated
 * class, and maps it to HTTP 400.
 */
interface ValidationExceptionInterface extends Throwable
{
    /**
     * The failed fields.
     *
     * @return list<array{field: string, reason: string, message: string, messageKey: string}>
     */
    public function getFields(): array;
}
