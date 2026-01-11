<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;
use HTMLPurifier;
use HTMLPurifier_Config;

/**
 * Purifier helper
 *
 * Provides HTML sanitization using HTML Purifier to prevent XSS attacks
 * while allowing safe HTML tags in user-generated content.
 */
class PurifierHelper extends Helper
{
    /**
     * HTML Purifier instance
     *
     * @var \HTMLPurifier|null
     */
    protected ?HTMLPurifier $purifier = null;

    /**
     * Initialize the helper
     *
     * @param array<string, mixed> $config The configuration settings provided to this helper
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->initializePurifier();
    }

    /**
     * Initialize HTML Purifier with configuration
     *
     * @return void
     */
    protected function initializePurifier(): void
    {
        $configPath = CONFIG . 'html_purifier.php';
        $purifierConfig = file_exists($configPath) ? include $configPath : [];

        $config = HTMLPurifier_Config::createDefault();

        // Set cache directory
        $cacheDir = CACHE . 'htmlpurifier';
        if (!file_exists($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }
        $config->set('Cache.SerializerPath', $cacheDir);

        // Apply custom configuration
        foreach ($purifierConfig as $key => $value) {
            $config->set($key, $value);
        }

        $this->purifier = new HTMLPurifier($config);
    }

    /**
     * Clean HTML content to prevent XSS attacks
     *
     * @param string|null $html The HTML content to sanitize
     * @return string The sanitized HTML content
     */
    public function clean(?string $html): string
    {
        if ($html === null || $html === '') {
            return '';
        }

        return $this->purifier->purify($html);
    }
}
