<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\Appointment;
use App\Service\Email\AbstractEmailService;
use Cake\Core\Configure;
use Cake\Routing\Router;

/**
 * Appointment Email Service
 *
 * Handles sending appointment-related emails.
 */
class AppointmentEmailService extends AbstractEmailService
{
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
            'token' => null,
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
