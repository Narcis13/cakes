<?php
declare(strict_types=1);

namespace App\Service\Email;

use Cake\Log\Log;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\ORM\TableRegistry;
use Exception;

/**
 * SMTP Email Transport
 *
 * Implements email sending via SMTP using CakePHP's native Mailer.
 */
class SmtpTransport implements EmailTransportInterface
{
    private const TRANSPORT_NAME = 'dynamic_smtp';

    /**
     * @var array<string, string>|null Cached SMTP settings
     */
    private ?array $smtpSettings = null;

    /**
     * Load SMTP settings from database
     *
     * @return array<string, string>
     */
    private function loadSmtpSettings(): array
    {
        if ($this->smtpSettings === null) {
            $siteSettings = TableRegistry::getTableLocator()->get('SiteSettings');

            $this->smtpSettings = [
                'host' => (string)$siteSettings->getValue('SMTP_HOST', ''),
                'port' => (string)$siteSettings->getValue('SMTP_PORT', '587'),
                'username' => (string)$siteSettings->getValue('SMTP_USER', ''),
                'password' => (string)$siteSettings->getValue('SMTP_PASSWORD', ''),
                'tls' => (string)$siteSettings->getValue('SMTP_TLS', '1'),
            ];
        }

        return $this->smtpSettings;
    }

    /**
     * Configure the SMTP transport dynamically
     *
     * @return void
     * @throws \Exception If SMTP is not properly configured
     */
    private function configureTransport(): void
    {
        $settings = $this->loadSmtpSettings();

        if (empty($settings['host']) || empty($settings['username'])) {
            throw new Exception('SMTP not properly configured. Please set SMTP_HOST and SMTP_USER in settings.');
        }

        // Remove existing transport if it exists
        if (TransportFactory::getConfig(self::TRANSPORT_NAME) !== null) {
            TransportFactory::drop(self::TRANSPORT_NAME);
        }

        // Create new transport with current settings
        TransportFactory::setConfig(self::TRANSPORT_NAME, [
            'className' => 'Smtp',
            'host' => $settings['host'],
            'port' => (int)$settings['port'],
            'username' => $settings['username'],
            'password' => $settings['password'],
            'tls' => $settings['tls'] === '1',
            'timeout' => 30,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function send(
        string $to,
        string $subject,
        string $html,
        string $text,
        string $fromEmail,
        string $fromName,
    ): bool {
        try {
            $this->configureTransport();

            $mailer = new Mailer();
            $mailer->setTransport(self::TRANSPORT_NAME);

            $mailer
                ->setFrom($fromEmail, $fromName)
                ->setTo($to)
                ->setSubject($subject)
                ->setEmailFormat('both')
                ->deliver($html);

            Log::info('Email sent via SMTP to ' . $to);

            return true;
        } catch (Exception $e) {
            Log::error('SMTP email error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Clear cached SMTP settings (useful for testing or when settings change)
     *
     * @return void
     */
    public function clearSettingsCache(): void
    {
        $this->smtpSettings = null;
    }
}
