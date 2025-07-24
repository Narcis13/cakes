<?php
declare(strict_types=1);

namespace App\Mailer;

use Cake\Mailer\Mailer;
use Cake\Core\Configure;

class AppointmentMailer extends Mailer
{
    public static function getConfig(): array
    {
        return [
            'transport' => 'default',
            'from' => [Configure::read('Email.default.from')],
        ];
    }

    public function confirmation($appointment, $token = null)
    {
        $hospitalConfig = Configure::read('Hospital');
        
        $this
            ->setTo($appointment->patient_email)
            ->setSubject('Confirmare programare - ' . $hospitalConfig['name'])
            ->setEmailFormat('both')
            ->setViewVars([
                'appointment' => $appointment,
                'token' => $token,
                'hospital' => $hospitalConfig,
                'confirmationUrl' => $token ? 
                    \Cake\Routing\Router::url([
                        'controller' => 'Appointments',
                        'action' => 'confirm',
                        $token
                    ], true) : null
            ])
            ->viewBuilder()->setTemplate('appointment_confirmation');

        return $this;
    }

    public function reminder($appointment, $hoursUntil = 24)
    {
        $hospitalConfig = Configure::read('Hospital');
        
        $this
            ->setTo($appointment->patient_email)
            ->setSubject('Reamintire programare - ' . $hospitalConfig['name'])
            ->setEmailFormat('both')
            ->setViewVars([
                'appointment' => $appointment,
                'hospital' => $hospitalConfig,
                'hoursUntil' => $hoursUntil
            ])
            ->viewBuilder()->setTemplate('appointment_reminder');

        return $this;
    }

    public function cancellation($appointment, $reason = null)
    {
        $hospitalConfig = Configure::read('Hospital');
        
        $this
            ->setTo($appointment->patient_email)
            ->setSubject('Anulare programare - ' . $hospitalConfig['name'])
            ->setEmailFormat('both')
            ->setViewVars([
                'appointment' => $appointment,
                'hospital' => $hospitalConfig,
                'reason' => $reason
            ])
            ->viewBuilder()->setTemplate('appointment_cancellation');

        return $this;
    }

    public function rescheduled($appointment, $oldDateTime = null)
    {
        $hospitalConfig = Configure::read('Hospital');
        
        $this
            ->setTo($appointment->patient_email)
            ->setSubject('Reprogramare - ' . $hospitalConfig['name'])
            ->setEmailFormat('both')
            ->setViewVars([
                'appointment' => $appointment,
                'hospital' => $hospitalConfig,
                'oldDateTime' => $oldDateTime
            ])
            ->viewBuilder()->setTemplate('appointment_rescheduled');

        return $this;
    }

    public function adminNotification($appointment)
    {
        $hospitalConfig = Configure::read('Hospital');
        
        $this
            ->setTo($hospitalConfig['email'])
            ->setSubject('Programare nouă - ' . $hospitalConfig['name'])
            ->setEmailFormat('both')
            ->setViewVars([
                'appointment' => $appointment,
                'hospital' => $hospitalConfig,
                'adminUrl' => \Cake\Routing\Router::url([
                    'controller' => 'Appointments',
                    'action' => 'view',
                    $appointment->id,
                    'prefix' => 'Admin'
                ], true)
            ])
            ->viewBuilder()->setTemplate('appointment_admin_notification');

        return $this;
    }

    public function confirmed($appointment)
    {
        $hospitalConfig = Configure::read('Hospital');
        
        $this
            ->setTo($appointment->patient_email)
            ->setSubject('Programare confirmată - ' . $hospitalConfig['name'])
            ->setEmailFormat('both')
            ->setViewVars([
                'appointment' => $appointment,
                'hospital' => $hospitalConfig
            ])
            ->viewBuilder()->setTemplate('appointment_confirmed');

        return $this;
    }
}