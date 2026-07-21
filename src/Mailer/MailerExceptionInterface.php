<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Mailer;

use Throwable;

/**
 * Base exception interface for all mailer errors.
 *
 * All mailer exceptions implement this interface,
 * allowing callers to catch any mailer error generically.
 */
interface MailerExceptionInterface extends Throwable
{
}
