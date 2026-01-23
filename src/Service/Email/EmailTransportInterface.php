<?php
declare(strict_types=1);

namespace App\Service\Email;

/**
 * Email Transport Interface
 *
 * Defines the contract for email transport implementations.
 */
interface EmailTransportInterface
{
    /**
     * Send an email
     *
     * @param string $to Recipient email address
     * @param string $subject Email subject
     * @param string $html HTML content
     * @param string $text Plain text content
     * @param string $fromEmail Sender email address
     * @param string $fromName Sender name
     * @return bool Success status
     */
    public function send(
        string $to,
        string $subject,
        string $html,
        string $text,
        string $fromEmail,
        string $fromName,
    ): bool;
}
