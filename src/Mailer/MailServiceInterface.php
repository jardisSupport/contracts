<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Mailer;

/**
 * Mail service of the kernel for domain code.
 *
 * One ready-made call: recipient, subject, text and/or HTML body. Reached via
 * `DomainKernelInterface::mail()`; domain code builds no message object and
 * imports no adapter class. For fully composed messages (attachments, embedded
 * images) stay with `MailerInterface::send()`.
 */
interface MailServiceInterface
{
    /**
     * Sends one email.
     *
     * @param string|list<string> $to One recipient address or a list of them
     * @param string $subject Subject line
     * @param string|null $text Plain text body
     * @param string|null $html HTML body
     * @param array{
     *     from?: string,
     *     fromName?: string,
     *     cc?: list<string>,
     *     bcc?: list<string>,
     *     replyTo?: string,
     *     headers?: array<string, string>
     * } $options
     *     Optional sender, copies, reply-to and extra headers; absent keys fall back to the configured defaults
     * @throws \InvalidArgumentException If neither text nor HTML is given
     * @throws MailerExceptionInterface If the transfer fails
     */
    public function sendMail(
        string|array $to,
        string $subject,
        ?string $text = null,
        ?string $html = null,
        array $options = []
    ): void;
}
