<?php
declare(strict_types=1);

namespace App\Service\Email;

use Cake\ORM\TableRegistry;
use Cake\View\View;

/**
 * Abstract Email Service
 *
 * Base class for email services that provides common functionality.
 */
abstract class AbstractEmailService
{
    /**
     * Default sender email (fallback)
     */
    private const DEFAULT_SENDER_EMAIL = 'noreply@smupitesti.online';

    /**
     * Default sender name (fallback)
     */
    private const DEFAULT_SENDER_NAME = 'Spitalul Militar Pitesti';

    /**
     * @var string|null Cached sender email
     */
    private ?string $senderEmail = null;

    /**
     * @var string|null Cached sender name
     */
    private ?string $senderName = null;

    /**
     * Render an email template
     *
     * @param string $template Template name (without path)
     * @param array<string, mixed> $viewVars Variables for the template
     * @return array{html: string, text: string}
     */
    protected function renderTemplate(string $template, array $viewVars): array
    {
        $view = new View();
        $view->disableAutoLayout();

        foreach ($viewVars as $key => $value) {
            $view->set($key, $value);
        }

        // Render HTML version
        $view->setTemplatePath('email/html');
        $view->setTemplate($template);
        $html = $view->render();

        // Render text version
        $view->setTemplatePath('email/text');
        $view->setTemplate($template);
        $text = $view->render();

        return ['html' => $html, 'text' => $text];
    }

    /**
     * Send an email using the configured transport
     *
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $html HTML content
     * @param string $text Plain text content
     * @return bool Success status
     */
    protected function send(string $to, string $subject, string $html, string $text): bool
    {
        $transport = EmailTransportFactory::getTransport();

        return $transport->send(
            $to,
            $subject,
            $html,
            $text,
            $this->getSenderEmail(),
            $this->getSenderName(),
        );
    }

    /**
     * Get the sender email address from settings
     *
     * @return string
     */
    protected function getSenderEmail(): string
    {
        if ($this->senderEmail === null) {
            $siteSettings = TableRegistry::getTableLocator()->get('SiteSettings');
            $this->senderEmail = (string)$siteSettings->getValue(
                'EMAIL_FROM_ADDRESS',
                self::DEFAULT_SENDER_EMAIL,
            );
        }

        return $this->senderEmail;
    }

    /**
     * Get the sender name from settings
     *
     * @return string
     */
    protected function getSenderName(): string
    {
        if ($this->senderName === null) {
            $siteSettings = TableRegistry::getTableLocator()->get('SiteSettings');
            $this->senderName = (string)$siteSettings->getValue(
                'EMAIL_FROM_NAME',
                self::DEFAULT_SENDER_NAME,
            );
        }

        return $this->senderName;
    }

    /**
     * Clear cached sender settings
     *
     * @return void
     */
    public function clearSenderCache(): void
    {
        $this->senderEmail = null;
        $this->senderName = null;
    }
}
