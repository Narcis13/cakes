<?php
declare(strict_types=1);

namespace App\Service\Email;

use Cake\ORM\TableRegistry;
use Exception;

/**
 * Email Transport Factory
 *
 * Creates the appropriate email transport based on site settings.
 */
class EmailTransportFactory
{
    public const PLATFORM_RESEND = 'RESEND';
    public const PLATFORM_SMTP = 'SMTP';

    /**
     * @var \App\Service\Email\EmailTransportInterface|null Cached transport instance
     */
    private static ?EmailTransportInterface $cachedTransport = null;

    /**
     * @var string|null Cached platform setting
     */
    private static ?string $cachedPlatform = null;

    /**
     * Get the configured email transport
     *
     * @return \App\Service\Email\EmailTransportInterface
     * @throws \Exception If platform is not recognized
     */
    public static function getTransport(): EmailTransportInterface
    {
        $platform = self::getCurrentPlatform();

        // Return cached transport if platform hasn't changed
        if (self::$cachedTransport !== null && self::$cachedPlatform === $platform) {
            return self::$cachedTransport;
        }

        self::$cachedPlatform = $platform;
        self::$cachedTransport = self::createTransport($platform);

        return self::$cachedTransport;
    }

    /**
     * Get the current email platform from settings
     *
     * @return string
     */
    public static function getCurrentPlatform(): string
    {
        $siteSettings = TableRegistry::getTableLocator()->get('SiteSettings');

        return (string)$siteSettings->getValue('PLATFORMA_EMAIL', self::PLATFORM_RESEND);
    }

    /**
     * Create transport instance based on platform
     *
     * @param string $platform The platform name
     * @return \App\Service\Email\EmailTransportInterface
     * @throws \Exception If platform is not recognized
     */
    private static function createTransport(string $platform): EmailTransportInterface
    {
        return match (strtoupper($platform)) {
            self::PLATFORM_RESEND => new ResendTransport(),
            self::PLATFORM_SMTP => new SmtpTransport(),
            default => throw new Exception("Unknown email platform: {$platform}"),
        };
    }

    /**
     * Clear the cached transport (useful when settings change)
     *
     * @return void
     */
    public static function clearCache(): void
    {
        self::$cachedTransport = null;
        self::$cachedPlatform = null;
    }

    /**
     * Get available platforms
     *
     * @return array<string, string>
     */
    public static function getAvailablePlatforms(): array
    {
        return [
            self::PLATFORM_RESEND => 'Resend API',
            self::PLATFORM_SMTP => 'SMTP Server',
        ];
    }
}
