<?php
declare(strict_types=1);

namespace App\Mailer;

use App\Model\Entity\Patient;
use Cake\Core\Configure;
use Cake\Mailer\Mailer;
use Cake\Routing\Router;

/**
 * PatientMailer
 *
 * Handles all patient-related email communications including
 * verification, password reset, and welcome messages.
 */
class PatientMailer extends Mailer
{
    /**
     * Get sender email configuration
     *
     * @return array<string, string>
     */
    protected function getSenderFromSettings(): array
    {
        return ['noreply@smupitesti.online' => 'Spitalul Militar Pitesti'];
    }

    /**
     * Send verification email to patient
     *
     * @param \App\Model\Entity\Patient $patient The patient entity
     * @param string $token The verification token
     * @return $this
     */
    public function verification(Patient $patient, string $token)
    {
        $hospitalConfig = Configure::read('Hospital');

        $verifyUrl = Router::url([
            'controller' => 'Patients',
            'action' => 'verifyEmail',
            $token,
        ], true);

        $this
            ->setFrom($this->getSenderFromSettings())
            ->setTo($patient->email)
            ->setSubject('Verificare cont - ' . $hospitalConfig['name'])
            ->setEmailFormat('both')
            ->setViewVars([
                'patient' => $patient,
                'verifyUrl' => $verifyUrl,
                'hospital' => $hospitalConfig,
            ])
            ->viewBuilder()->setTemplate('patient_verification');

        return $this;
    }

    /**
     * Send password reset email to patient
     *
     * @param \App\Model\Entity\Patient $patient The patient entity
     * @param string $token The password reset token
     * @return $this
     */
    public function passwordReset(Patient $patient, string $token)
    {
        $hospitalConfig = Configure::read('Hospital');

        $resetUrl = Router::url([
            'controller' => 'Patients',
            'action' => 'resetPassword',
            $token,
        ], true);

        $this
            ->setFrom($this->getSenderFromSettings())
            ->setTo($patient->email)
            ->setSubject('Resetare parolă - ' . $hospitalConfig['name'])
            ->setEmailFormat('both')
            ->setViewVars([
                'patient' => $patient,
                'resetUrl' => $resetUrl,
                'hospital' => $hospitalConfig,
            ])
            ->viewBuilder()->setTemplate('patient_password_reset');

        return $this;
    }

    /**
     * Send welcome email to patient after successful verification
     *
     * @param \App\Model\Entity\Patient $patient The patient entity
     * @return $this
     */
    public function welcome(Patient $patient)
    {
        $hospitalConfig = Configure::read('Hospital');

        $portalUrl = Router::url([
            'controller' => 'Patients',
            'action' => 'portal',
        ], true);

        $this
            ->setFrom($this->getSenderFromSettings())
            ->setTo($patient->email)
            ->setSubject('Bine ați venit - ' . $hospitalConfig['name'])
            ->setEmailFormat('both')
            ->setViewVars([
                'patient' => $patient,
                'portalUrl' => $portalUrl,
                'hospital' => $hospitalConfig,
            ])
            ->viewBuilder()->setTemplate('patient_welcome');

        return $this;
    }
}
