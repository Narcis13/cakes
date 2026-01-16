<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\Appointment;
use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\Routing\Router;
use Cake\View\View;
use Exception;
use Resend;
use Resend\Client;

/**
 * Appointment Email Service
 *
 * Handles sending appointment emails via Resend API.
 */
class AppointmentEmailService
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
    private ?Client $resendClient = null;

    /**
     * Initialize the Resend client
     *
     * @return \Resend\Client
     * @throws \Exception If API key is not configured
     */
    private function getClient(): Client
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

            Log::info('Appointment email sent via Resend to ' . $to . ', ID: ' . ($result->id ?? 'unknown'));

            return true;
        } catch (Exception $e) {
            Log::error('Resend appointment email error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Send confirmation email to patient (appointment is already confirmed)
     *
     * @param \App\Model\Entity\Appointment $appointment The appointment entity
     * @return bool Success status
     */
    public function sendConfirmation(Appointment $appointment): bool
    {
        $hospitalConfig = Configure::read('Hospital');

        $content = $this->renderTemplate('appointment_confirmation', [
            'appointment' => $appointment,
            'hospital' => $hospitalConfig,
            'token' => null, // No token needed - appointment is already confirmed
            'confirmationUrl' => null,
        ]);

        return $this->send(
            $appointment->patient_email,
            'Programare confirmată - ' . $hospitalConfig['name'],
            $content['html'],
            $content['text'],
        );
    }

    /**
     * Send admin notification email for new appointment
     *
     * @param \App\Model\Entity\Appointment $appointment The appointment entity (with associations loaded)
     * @return bool Success status
     */
    public function sendAdminNotification(Appointment $appointment): bool
    {
        $hospitalConfig = Configure::read('Hospital');
        $adminEmail = Configure::read('Hospital.email');

        $adminUrl = Router::url([
            'prefix' => 'Admin',
            'controller' => 'Appointments',
            'action' => 'view',
            $appointment->id,
        ], true);

        $content = $this->renderTemplate('appointment_admin_notification', [
            'appointment' => $appointment,
            'hospital' => $hospitalConfig,
            'adminUrl' => $adminUrl,
        ]);

        return $this->send(
            $adminEmail,
            'Programare nouă - ' . $appointment->patient_name,
            $content['html'],
            $content['text'],
        );
    }

    /**
     * Send cancellation email to patient
     *
     * @param \App\Model\Entity\Appointment $appointment The appointment entity (with associations loaded)
     * @param string|null $reason The cancellation reason
     * @return bool Success status
     */
    public function sendCancellation(Appointment $appointment, ?string $reason = null): bool
    {
        $hospitalConfig = Configure::read('Hospital');

        $content = $this->renderTemplate('appointment_cancellation', [
            'appointment' => $appointment,
            'hospital' => $hospitalConfig,
            'reason' => $reason,
        ]);

        return $this->send(
            $appointment->patient_email,
            'Anulare programare - ' . $hospitalConfig['name'],
            $content['html'],
            $content['text'],
        );
    }
}
