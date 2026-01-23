<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\Patient;
use App\Service\Email\AbstractEmailService;
use Cake\Core\Configure;
use Cake\Routing\Router;

/**
 * Patient Email Service
 *
 * Handles sending patient-related emails.
 */
class PatientEmailService extends AbstractEmailService
{
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
            $content['text'],
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
            $content['text'],
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
            $content['text'],
        );
    }
}
