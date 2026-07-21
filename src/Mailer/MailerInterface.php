<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Mailer;

/**
 * Contract for sending email messages.
 *
 * Implementations handle transport details (SMTP, API, etc.).
 * The caller builds the message, the mailer delivers it.
 */
interface MailerInterface
{
    /**
     * Send an email message.
     *
     * @param MailMessageInterface $message The message to send
     * @throws MailerExceptionInterface If sending fails
     */
    public function send(MailMessageInterface $message): void;
}
