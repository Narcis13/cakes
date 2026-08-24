<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Table\SiteSettingsTable;
use Cake\ORM\TableRegistry;

/**
 * PortalMaintenanceService
 *
 * Reads and writes the maintenance mode configuration of the patient portal
 * (patient login, dashboard and online appointment booking).
 *
 * The values live in the `site_settings` table so they can be toggled from the
 * admin panel without a deploy.
 */
class PortalMaintenanceService
{
    /**
     * Setting key holding the on/off flag ("1" or "0")
     */
    public const KEY_ENABLED = 'PORTAL_MAINTENANCE_MODE';

    /**
     * Setting key holding the title shown on the maintenance page
     */
    public const KEY_TITLE = 'PORTAL_MAINTENANCE_TITLE';

    /**
     * Setting key holding the message shown on the maintenance page
     */
    public const KEY_MESSAGE = 'PORTAL_MAINTENANCE_MESSAGE';

    /**
     * Setting key holding the estimated time the portal comes back online
     */
    public const KEY_ETA = 'PORTAL_MAINTENANCE_ETA';

    /**
     * Default title shown when no custom one was configured
     */
    public const DEFAULT_TITLE = 'Portalul de programări este în mentenanță';

    /**
     * Default message shown when no custom one was configured
     */
    public const DEFAULT_MESSAGE = 'În acest moment efectuăm lucrări de mentenanță la portalul de '
        . 'programări online. Vă rugăm să reveniți puțin mai târziu. Ne cerem scuze pentru '
        . 'neplăcerile create.';

    /**
     * @var \App\Model\Table\SiteSettingsTable
     */
    private SiteSettingsTable $siteSettings;

    /**
     * Per-request cache of the maintenance settings.
     *
     * @var array<string, string>|null
     */
    private static ?array $cache = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        /** @var \App\Model\Table\SiteSettingsTable $siteSettings */
        $siteSettings = TableRegistry::getTableLocator()->get('SiteSettings');
        $this->siteSettings = $siteSettings;
    }

    /**
     * Is the patient portal currently in maintenance mode?
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->getSettings()[self::KEY_ENABLED] === '1';
    }

    /**
     * Title displayed on the maintenance page.
     *
     * @return string
     */
    public function getTitle(): string
    {
        $title = trim($this->getSettings()[self::KEY_TITLE]);

        return $title !== '' ? $title : self::DEFAULT_TITLE;
    }

    /**
     * Message displayed on the maintenance page.
     *
     * @return string
     */
    public function getMessage(): string
    {
        $message = trim($this->getSettings()[self::KEY_MESSAGE]);

        return $message !== '' ? $message : self::DEFAULT_MESSAGE;
    }

    /**
     * Estimated return time, free text. Empty when not configured.
     *
     * @return string
     */
    public function getEta(): string
    {
        $eta = trim($this->getSettings()[self::KEY_ETA]);

        // "-" is the placeholder stored when the admin leaves the field empty.
        return $eta !== '-' ? $eta : '';
    }

    /**
     * All maintenance settings, keyed by setting name.
     *
     * @return array<string, string>
     */
    public function getSettings(): array
    {
        if (self::$cache === null) {
            self::$cache = [
                self::KEY_ENABLED => (string)$this->siteSettings->getValue(self::KEY_ENABLED, '0'),
                self::KEY_TITLE => (string)$this->siteSettings->getValue(self::KEY_TITLE, self::DEFAULT_TITLE),
                self::KEY_MESSAGE => (string)$this->siteSettings->getValue(self::KEY_MESSAGE, self::DEFAULT_MESSAGE),
                self::KEY_ETA => (string)$this->siteSettings->getValue(self::KEY_ETA, ''),
            ];
        }

        return self::$cache;
    }

    /**
     * Persist the maintenance settings.
     *
     * Empty title/message fall back to the defaults because site_settings does
     * not accept empty values.
     *
     * @param bool $enabled Whether the portal is in maintenance mode.
     * @param string $title Title shown on the maintenance page.
     * @param string $message Message shown on the maintenance page.
     * @param string $eta Estimated return time (free text, optional).
     * @return bool True when every setting was stored.
     */
    public function save(bool $enabled, string $title, string $message, string $eta = ''): bool
    {
        $title = trim($title);
        $message = trim($message);
        $eta = trim($eta);

        $values = [
            self::KEY_ENABLED => [
                $enabled ? '1' : '0',
                'Mod mentenanță portal pacienți (1 = activ, 0 = inactiv)',
            ],
            self::KEY_TITLE => [
                $title !== '' ? $title : self::DEFAULT_TITLE,
                'Titlul afișat pe pagina de mentenanță a portalului',
            ],
            self::KEY_MESSAGE => [
                $message !== '' ? $message : self::DEFAULT_MESSAGE,
                'Mesajul afișat pe pagina de mentenanță a portalului',
            ],
            self::KEY_ETA => [
                $eta !== '' ? $eta : '-',
                'Estimare revenire portal din mentenanță',
            ],
        ];

        $success = true;
        foreach ($values as $key => [$value, $description]) {
            if (!$this->siteSettings->setValue($key, $value, $description)) {
                $success = false;
            }
        }

        $this->clearCache();

        return $success;
    }

    /**
     * Turn maintenance mode on or off, keeping the configured texts.
     *
     * @param bool $enabled New state.
     * @return bool
     */
    public function setEnabled(bool $enabled): bool
    {
        $result = $this->siteSettings->setValue(
            self::KEY_ENABLED,
            $enabled ? '1' : '0',
            'Mod mentenanță portal pacienți (1 = activ, 0 = inactiv)',
        );

        $this->clearCache();

        return $result;
    }

    /**
     * Forget the per-request cache.
     *
     * @return void
     */
    public function clearCache(): void
    {
        self::$cache = null;
    }
}
