<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\Patient;
use App\Service\PatientAuthService;
use App\Service\PatientEmailService;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\Event\EventInterface;
use Cake\Http\Response;
use Cake\I18n\DateTime;
use Cake\Log\Log;
use Exception;

/**
 * Patients Controller
 *
 * Handles all patient portal functionality including authentication,
 * registration, password reset, and appointment management.
 *
 * @property \App\Model\Table\PatientsTable $Patients
 * @property \App\Model\Table\AppointmentsTable $Appointments
 */
class PatientsController extends AppController
{
    /**
     * @var \App\Service\PatientAuthService
     */
    private PatientAuthService $authService;

    /**
     * Initialize method
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->authService = new PatientAuthService();

        // Public actions (no auth required)
        $this->Authentication->allowUnauthenticated([
            'login',
            'register',
            'logout',
            'forgotPassword',
            'resetPassword',
            'verifyEmail',
        ]);

        // Disable FormProtection for auth forms (tokens expire when page stays open)
        // These actions have their own security: rate limiting, CSRF protection via middleware
        if ($this->components()->has('FormProtection')) {
            $this->FormProtection->setConfig('unlockedActions', [
                'login',
                'register',
                'forgotPassword',
                'resetPassword',
            ]);
        }
    }

    /**
     * Before filter callback
     *
     * @param \Cake\Event\EventInterface $event The beforeFilter event.
     * @return \Cake\Http\Response|null|void
     */
    public function beforeFilter(EventInterface $event): ?Response
    {
        parent::beforeFilter($event);

        // Use portal layout for authenticated pages
        $action = $this->request->getParam('action');
        $publicActions = ['login', 'register', 'forgotPassword', 'resetPassword', 'verifyEmail'];

        if (!in_array($action, $publicActions, true)) {
            $this->viewBuilder()->setLayout('portal');
        }

        return null;
    }

    /**
     * Get the currently authenticated patient
     *
     * @return \App\Model\Entity\Patient|null
     */
    private function getAuthenticatedPatient(): ?Patient
    {
        $identity = $this->Authentication->getIdentity();
        if (!$identity) {
            return null;
        }

        /** @var \App\Model\Entity\Patient|null $patient */
        $patient = $identity->getOriginalData();

        return $patient;
    }

    /**
     * Register action - Patient registration
     *
     * @return \Cake\Http\Response|null|void
     */
    public function register(): ?Response
    {
        // Redirect if already logged in
        if ($this->Authentication->getIdentity()) {
            return $this->redirect(['action' => 'portal']);
        }

        $patient = $this->Patients->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            // Validate password confirmation
            if (($data['password'] ?? '') !== ($data['password_confirm'] ?? '')) {
                $this->Flash->error('Parolele nu se potrivesc.');
                $this->set(compact('patient'));

                return null;
            }

            // Check if email exists but is not verified - allow re-registration
            $email = (string)($data['email'] ?? '');
            $existingPatient = $this->Patients->find()
                ->where(['email' => $email])
                ->first();

            if ($existingPatient) {
                if ($existingPatient->email_verified_at !== null) {
                    // Email is already verified - can't re-register
                    $this->Flash->error('Această adresă de email este deja înregistrată.');
                    $this->set(compact('patient'));

                    return null;
                }
                // Use existing unverified record
                $patient = $existingPatient;
            }

            $patient = $this->Patients->patchEntity($patient, $data);

            // Set security fields directly (not via mass assignment)
            $patient->verification_token = $this->authService->generateVerificationToken();
            $patient->is_active = true;
            $patient->failed_login_attempts = 0;

            if ($this->Patients->save($patient)) {
                // Send verification email via Resend
                try {
                    $emailService = new PatientEmailService();
                    $emailService->sendVerification($patient, $patient->verification_token);

                    $this->Flash->success('Contul a fost creat. Verificați email-ul pentru activare.');
                } catch (Exception $e) {
                    Log::error('Patient verification email error: ' . $e->getMessage());
                    $this->Flash->warning(
                        'Contul a fost creat, dar emailul de verificare nu a putut fi trimis. '
                        . 'Contactați-ne pentru asistență.',
                    );
                }

                return $this->redirect(['action' => 'login']);
            }

            $this->Flash->error('Înregistrarea nu a putut fi finalizată. Verificați datele introduse.');
        }

        $this->set(compact('patient'));

        return null;
    }

    /**
     * Login action - Patient authentication
     *
     * @return \Cake\Http\Response|null|void
     */
    public function login(): ?Response
    {
        // Redirect if already logged in
        if ($this->Authentication->getIdentity()) {
            return $this->redirect(['action' => 'portal']);
        }

        if ($this->request->is('post')) {
            $email = (string)$this->request->getData('email');
            $ipAddress = $this->request->clientIp();
            $userAgent = $this->request->getHeaderLine('User-Agent');

            // Check rate limiting
            $loginCheck = $this->authService->isLoginAllowed($email, $ipAddress);
            if (!$loginCheck['allowed']) {
                $this->Flash->error($loginCheck['reason']);

                return null;
            }

            // Check if email is verified before attempting authentication
            if ($email && !$this->authService->isEmailVerified($email)) {
                $patient = $this->authService->findByEmail($email);
                if ($patient) {
                    $this->Flash->error(
                        'Contul nu a fost verificat. Verificați email-ul pentru link-ul de activare.',
                    );

                    return null;
                }
            }

            // Attempt authentication
            $result = $this->Authentication->getResult();

            if ($result && $result->isValid()) {
                /** @var \App\Model\Entity\Patient $patient */
                $patient = $this->Authentication->getIdentity()->getOriginalData();

                // Record successful login and clear failed attempts
                $this->authService->recordLoginAttempt($email, $ipAddress, $userAgent, true);
                $this->authService->clearAttemptsOnSuccess($patient, $ipAddress);

                // Redirect to intended URL or portal
                $redirect = $this->request->getQuery('redirect', ['action' => 'portal']);

                return $this->redirect($redirect);
            }

            // Record failed attempt
            if ($email) {
                $this->authService->recordLoginAttempt($email, $ipAddress, $userAgent, false);
            }

            $this->Flash->error('Email sau parolă incorectă.');
        }

        return null;
    }

    /**
     * Logout action - End patient session
     *
     * @return \Cake\Http\Response|null|void
     */
    public function logout(): ?Response
    {
        $this->Authentication->logout();
        $this->Flash->success('V-ați deconectat cu succes.');

        return $this->redirect(['action' => 'login']);
    }

    /**
     * Verify email action - Validate verification token
     *
     * @param string|null $token Verification token
     * @return \Cake\Http\Response|null|void
     */
    public function verifyEmail(?string $token = null): ?Response
    {
        if (!$token) {
            $this->Flash->error('Token de verificare invalid.');

            return $this->redirect(['action' => 'login']);
        }

        $patient = $this->authService->verifyEmail($token);

        if ($patient) {
            // Send welcome email via Resend
            try {
                $emailService = new PatientEmailService();
                $emailService->sendWelcome($patient);
            } catch (Exception $e) {
                Log::error('Patient welcome email error: ' . $e->getMessage());
            }

            $this->Flash->success('Email verificat cu succes. Vă puteți autentifica.');
        } else {
            $this->Flash->error('Token de verificare invalid sau expirat.');
        }

        return $this->redirect(['action' => 'login']);
    }

    /**
     * Forgot password action - Request password reset
     *
     * @return \Cake\Http\Response|null|void
     */
    public function forgotPassword(): ?Response
    {
        if ($this->request->is('post')) {
            $email = (string)$this->request->getData('email');

            // Always show success message to prevent email enumeration
            $this->Flash->success(
                'Dacă adresa de email este înregistrată, veți primi un link de resetare.',
            );

            $patient = $this->authService->findByEmail($email);

            if ($patient) {
                // Generate reset token
                $token = $this->authService->generatePasswordResetToken($patient);

                // Send reset email via Resend
                try {
                    $emailService = new PatientEmailService();
                    $emailService->sendPasswordReset($patient, $token);
                } catch (Exception $e) {
                    Log::error('Password reset email error: ' . $e->getMessage());
                }
            }

            return $this->redirect(['action' => 'login']);
        }

        return null;
    }

    /**
     * Reset password action - Set new password with valid token
     *
     * @param string|null $token Password reset token
     * @return \Cake\Http\Response|null|void
     */
    public function resetPassword(?string $token = null): ?Response
    {
        if (!$token) {
            $this->Flash->error('Token de resetare invalid.');

            return $this->redirect(['action' => 'forgotPassword']);
        }

        // Validate token
        $patient = $this->authService->validatePasswordResetToken($token);

        if (!$patient) {
            $this->Flash->error('Token de resetare invalid sau expirat.');

            return $this->redirect(['action' => 'forgotPassword']);
        }

        if ($this->request->is('post')) {
            $password = (string)$this->request->getData('password');
            $passwordConfirm = (string)$this->request->getData('password_confirm');

            // Validate password
            if (strlen($password) < 8) {
                $this->Flash->error('Parola trebuie să aibă minim 8 caractere.');
                $this->set('token', $token);

                return null;
            }

            if ($password !== $passwordConfirm) {
                $this->Flash->error('Parolele nu se potrivesc.');
                $this->set('token', $token);

                return null;
            }

            // Reset password
            if ($this->authService->resetPassword($token, $password)) {
                $this->Flash->success('Parola a fost schimbată cu succes. Vă puteți autentifica.');

                return $this->redirect(['action' => 'login']);
            }

            $this->Flash->error('Nu am putut reseta parola. Încercați din nou.');
        }

        $this->set('token', $token);

        return null;
    }

    /**
     * Portal action - Patient dashboard
     *
     * @return \Cake\Http\Response|null|void
     */
    public function portal(): ?Response
    {
        $patient = $this->getAuthenticatedPatient();

        if (!$patient) {
            return $this->redirect(['action' => 'login']);
        }

        // Fetch appointment statistics
        $appointments = $this->fetchTable('Appointments');

        // Upcoming appointments
        $upcomingAppointments = $appointments->find()
            ->where([
                'patient_id' => $patient->id,
                'appointment_date >=' => DateTime::now()->format('Y-m-d'),
                'status IN' => ['pending', 'confirmed'],
            ])
            ->contain(['Doctors', 'Services'])
            ->orderAsc('appointment_date')
            ->orderAsc('appointment_time')
            ->limit(5)
            ->all();

        // Count statistics
        $appointmentStats = [
            'total' => $appointments->find()
                ->where(['patient_id' => $patient->id])
                ->count(),
            'upcoming' => $appointments->find()
                ->where([
                    'patient_id' => $patient->id,
                    'appointment_date >=' => DateTime::now()->format('Y-m-d'),
                    'status IN' => ['pending', 'confirmed'],
                ])
                ->count(),
            'completed' => $appointments->find()
                ->where([
                    'patient_id' => $patient->id,
                    'status' => 'completed',
                ])
                ->count(),
        ];

        $this->set(compact('patient', 'upcomingAppointments', 'appointmentStats'));

        return null;
    }

    /**
     * Appointments action - List all patient appointments
     *
     * @return \Cake\Http\Response|null|void
     */
    public function appointments(): ?Response
    {
        $patient = $this->getAuthenticatedPatient();

        if (!$patient) {
            return $this->redirect(['action' => 'login']);
        }

        $appointments = $this->fetchTable('Appointments')->find()
            ->where(['patient_id' => $patient->id])
            ->contain(['Doctors', 'Services'])
            ->orderDesc('appointment_date')
            ->orderDesc('appointment_time')
            ->all();

        // Separate into upcoming and past
        $now = DateTime::now()->format('Y-m-d');

        $upcomingAppointments = $appointments->filter(function ($apt) use ($now) {
            return $apt->appointment_date->format('Y-m-d') >= $now
                && in_array($apt->status, ['pending', 'confirmed'], true);
        });

        $pastAppointments = $appointments->filter(function ($apt) use ($now) {
            return $apt->appointment_date->format('Y-m-d') < $now
                || in_array($apt->status, ['completed', 'cancelled', 'no-show'], true);
        });

        $this->set(compact('patient', 'upcomingAppointments', 'pastAppointments'));

        return null;
    }

    /**
     * Cancel appointment action
     *
     * @param string|null $id Appointment ID
     * @return \Cake\Http\Response|null|void
     */
    public function cancelAppointment(?string $id = null): ?Response
    {
        $this->request->allowMethod(['post']);

        $patient = $this->getAuthenticatedPatient();

        if (!$patient) {
            return $this->redirect(['action' => 'login']);
        }

        if (!$id) {
            $this->Flash->error('Programare invalidă.');

            return $this->redirect(['action' => 'appointments']);
        }

        $appointments = $this->fetchTable('Appointments');

        // Find appointment belonging to this patient
        $appointment = $appointments->find()
            ->where([
                'id' => $id,
                'patient_id' => $patient->id,
            ])
            ->first();

        if (!$appointment) {
            $this->Flash->error('Programarea nu a fost găsită.');

            return $this->redirect(['action' => 'appointments']);
        }

        // Check if appointment can be cancelled
        if (!in_array($appointment->status, ['pending', 'confirmed'], true)) {
            $this->Flash->error('Această programare nu poate fi anulată.');

            return $this->redirect(['action' => 'appointments']);
        }

        // Check if appointment is in the past
        $appointmentDateTime = new DateTime(
            $appointment->appointment_date->format('Y-m-d') . ' ' .
            $appointment->appointment_time->format('H:i:s'),
        );

        if ($appointmentDateTime < DateTime::now()) {
            $this->Flash->error('Nu puteți anula o programare din trecut.');

            return $this->redirect(['action' => 'appointments']);
        }

        // Cancel appointment
        $appointment->status = 'cancelled';

        if ($appointments->save($appointment)) {
            $this->Flash->success('Programarea a fost anulată.');
        } else {
            $this->Flash->error('Nu am putut anula programarea.');
        }

        return $this->redirect(['action' => 'appointments']);
    }

    /**
     * Profile action - View and update patient profile
     *
     * @return \Cake\Http\Response|null|void
     */
    public function profile(): ?Response
    {
        $patient = $this->getAuthenticatedPatient();

        if (!$patient) {
            return $this->redirect(['action' => 'login']);
        }

        // Reload patient to get fresh data
        $patient = $this->Patients->get($patient->id);

        if ($this->request->is(['post', 'put'])) {
            $data = $this->request->getData();

            // Handle password change
            if (!empty($data['new_password'])) {
                // Verify current password
                $currentPassword = (string)($data['current_password'] ?? '');
                if (!$this->verifyCurrentPassword($patient, $currentPassword)) {
                    $this->Flash->error('Parola curentă este incorectă.');
                    $this->set(compact('patient'));

                    return null;
                }

                // Validate new password
                if (strlen($data['new_password']) < 8) {
                    $this->Flash->error('Noua parolă trebuie să aibă minim 8 caractere.');
                    $this->set(compact('patient'));

                    return null;
                }

                if ($data['new_password'] !== ($data['new_password_confirm'] ?? '')) {
                    $this->Flash->error('Parolele noi nu se potrivesc.');
                    $this->set(compact('patient'));

                    return null;
                }

                $data['password'] = $data['new_password'];
            }

            // Remove password fields from data if not changing password
            unset($data['current_password'], $data['new_password'], $data['new_password_confirm']);

            // Only allow updating certain fields
            $allowedFields = ['full_name', 'phone'];
            if (!empty($data['password'])) {
                $allowedFields[] = 'password';
            }

            $patient = $this->Patients->patchEntity($patient, $data, [
                'fields' => $allowedFields,
            ]);

            if ($this->Patients->save($patient)) {
                $this->Flash->success('Profilul a fost actualizat.');

                return $this->redirect(['action' => 'profile']);
            }

            $this->Flash->error('Nu am putut actualiza profilul.');
        }

        $this->set(compact('patient'));

        return null;
    }

    /**
     * Verify the current password for a patient
     *
     * @param \App\Model\Entity\Patient $patient The patient entity
     * @param string $password The password to verify
     * @return bool
     */
    private function verifyCurrentPassword(Patient $patient, string $password): bool
    {
        $hasher = new DefaultPasswordHasher();

        return $hasher->check($password, $patient->password);
    }
}
