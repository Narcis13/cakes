<?php
declare(strict_types=1);

namespace App\Mailer;

use App\Model\Entity\User;
use App\Service\Email\SmtpTransport;
use Cake\Core\Configure;

/**
 * AdminMailer
 *
 * Handles admin-related email communications including
 * two-factor authentication codes.
 * Uses the dynamic SMTP transport configured in SiteSettings.
 */
class AdminMailer
{
    /**
     * Send two-factor authentication code to admin user
     *
     * @param \App\Model\Entity\User $user The user entity
     * @param string $code The 2FA verification code (plain text)
     * @return bool
     */
    public function sendTwoFactorCode(User $user, string $code): bool
    {
        $hospitalConfig = Configure::read('Hospital');
        $fromEmail = 'noreply@smupitesti.online';
        $fromName = $hospitalConfig['name'] ?? 'Spitalul Militar Pitesti';

        // Render email templates
        $html = $this->renderTemplate('admin_2fa_code', 'html', [
            'user' => $user,
            'code' => $code,
            'hospital' => $hospitalConfig,
        ]);

        $text = $this->renderTemplate('admin_2fa_code', 'text', [
            'user' => $user,
            'code' => $code,
            'hospital' => $hospitalConfig,
        ]);

        $transport = new SmtpTransport();

        return $transport->send(
            $user->email2FA,
            'Cod de verificare - ' . $fromName,
            $html,
            $text,
            $fromEmail,
            $fromName,
        );
    }

    /**
     * Render an email template
     *
     * @param string $template Template name
     * @param string $type Template type (html or text)
     * @param array<string, mixed> $vars View variables
     * @return string Rendered content
     */
    private function renderTemplate(string $template, string $type, array $vars): string
    {
        extract($vars);

        ob_start();
        include ROOT . '/templates/email/' . $type . '/' . $template . '.php';

        return (string)ob_get_clean();
    }
}
