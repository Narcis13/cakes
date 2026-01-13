<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\Patient;
use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\Routing\Router;
use Cake\View\View;
use Exception;
use Resend;

/**
 * Patient Email Service
 *
 * Handles sending patient emails via Resend API.
 */
class PatientEmailService
{
    /**
     * Sender email address
     */
    private const SENDER_EMAIL = 'noreply@smupitesti.online';

    /**
     * Sender name
     */
    private const SENDER_NAME = 'Spitalul Militar Pitesti';

    /**
     * @var \Resend\Client|null
     */
    private $resendClient = null;

    /**
     * Initialize the Resend client
     *
     * @return \Resend\Client
     * @throws \Exception If API key is not configured
     */
    private function getClient()
    {
        if ($this->resendClient === null) {
            $apiKey = Configure::read('ApiKeys.resend');

            if (!$apiKey || $apiKey === 'your-resend-api-key-here') {
                throw new Exception('Resend API key not configured');
            }

            $this->resendClient = Resend::client($apiKey);
        }

        return $this->resendClient;
    }

    /**
     * Render an email template
     *
     * @param string $template Template name (without path)
     * @param array<string, mixed> $viewVars Variables for the template
     * @return array{html: string, text: string}
     */
    private function renderTemplate(string $template, array $viewVars): array
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
     * Send an email via Resend
     *
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $html HTML content
     * @param string $text Plain text content
     * @return bool Success status
     */
    private function send(string $to, string $subject, string $html, string $text): bool
    {
        try {
            $client = $this->getClient();

            $result = $client->emails->send([
                'from' => self::SENDER_NAME . ' <' . self::SENDER_EMAIL . '>',
                'to' => [$to],
                'subject' => $subject,
                'html' => $html,
                'text' => $text,
            ]);

            Log::info('Email sent via Resend to ' . $to . ', ID: ' . ($result->id ?? 'unknown'));

            return true;
        } catch (Exception $e) {
            Log::error('Resend email error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Send verification email to patient
     *
     * @param \App\Model\Entity\Patient $patient The patient entity
     * @param string $token The verification token
     * @return bool Success status
     */
    public function sendVerification(Patient $patient, string $token): bool
    {
        $hospitalConfig = Configure::read('Hospital');

        $verifyUrl = Router::url([
            'controller' => 'Patients',
            'action' => 'verifyEmail',
            $token,
        ], true);

        $content = $this->renderTemplate('patient_verification', [
            'patient' => $patient,
            'verifyUrl' => $verifyUrl,
            'hospital' => $hospitalConfig,
        ]);

        return $this->send(
            $patient->email,
            'Verificare cont - ' . $hospitalConfig['name'],
            $content['html'],
            $content['text']
        );
    }

    /**
     * Send password reset email to patient
     *
     * @param \App\Model\Entity\Patient $patient The patient entity
     * @param string $token The password reset token
     * @return bool Success status
     */
    public function sendPasswordReset(Patient $patient, string $token): bool
    {
        $hospitalConfig = Configure::read('Hospital');

        $resetUrl = Router::url([
            'controller' => 'Patients',
            'action' => 'resetPassword',
            $token,
        ], true);

        $content = $this->renderTemplate('patient_password_reset', [
            'patient' => $patient,
            'resetUrl' => $resetUrl,
            'hospital' => $hospitalConfig,
        ]);

        return $this->send(
            $patient->email,
            'Resetare parolă - ' . $hospitalConfig['name'],
            $content['html'],
            $content['text']
        );
    }

    /**
     * Send welcome email to patient after successful verification
     *
     * @param \App\Model\Entity\Patient $patient The patient entity
     * @return bool Success status
     */
    public function sendWelcome(Patient $patient): bool
    {
        $hospitalConfig = Configure::read('Hospital');

        $portalUrl = Router::url([
            'controller' => 'Patients',
            'action' => 'portal',
        ], true);

        $content = $this->renderTemplate('patient_welcome', [
            'patient' => $patient,
            'portalUrl' => $portalUrl,
            'hospital' => $hospitalConfig,
        ]);

        return $this->send(
            $patient->email,
            'Bine ați venit - ' . $hospitalConfig['name'],
            $content['html'],
            $content['text']
        );
    }
}
