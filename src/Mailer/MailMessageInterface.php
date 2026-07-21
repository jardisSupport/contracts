<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Mailer;

/**
 * Contract for an email message value object.
 *
 * Represents a fully composed email with addresses, subject, body and optional attachments.
 * Implementations should be immutable — builder methods return new instances.
 */
interface MailMessageInterface
{
    /**
     * Get the sender address.
     *
     * @return array{address: string, name?: string}|null
     */
    public function from(): ?array;

    /**
     * Get the recipient addresses.
     *
     * @return array<array{address: string, name?: string}>
     */
    public function to(): array;

    /**
     * Get the CC addresses.
     *
     * @return array<array{address: string, name?: string}>
     */
    public function cc(): array;

    /**
     * Get the BCC addresses.
     *
     * @return array<array{address: string, name?: string}>
     */
    public function bcc(): array;

    /**
     * Get the reply-to address.
     *
     * @return array{address: string, name?: string}|null
     */
    public function replyTo(): ?array;

    /**
     * Get the subject line.
     */
    public function subject(): ?string;

    /**
     * Get the plain text body.
     */
    public function text(): ?string;

    /**
     * Get the HTML body.
     */
    public function html(): ?string;

    /**
     * Get the attachments.
     *
     * @return array<array{content: string, filename: string, mimeType: string, inline: bool}>
     */
    public function attachments(): array;
}
