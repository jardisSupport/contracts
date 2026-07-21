<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Mailer;

/**
 * Contract for mail transport implementations.
 *
 * Transports handle the raw delivery of composed MIME messages.
 * SMTP is the primary transport; API-based transports can implement this later.
 */
interface MailTransportInterface
{
    /**
     * Send a raw MIME message to the given recipients.
     *
     * @param string $sender Envelope sender (MAIL FROM)
     * @param array<string> $recipients Envelope recipients (RCPT TO)
     * @param string $rawMessage Fully composed MIME message
     * @throws MailerExceptionInterface If transport fails
     */
    public function send(string $sender, array $recipients, string $rawMessage): void;

    /**
     * Close the transport connection.
     *
     * Safe to call multiple times (idempotent).
     */
    public function disconnect(): void;
}
