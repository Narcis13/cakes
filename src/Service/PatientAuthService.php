<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\Patient;
use App\Model\Table\LoginAttemptsTable;
use App\Model\Table\PatientsTable;
use Cake\I18n\DateTime;
use Cake\ORM\TableRegistry;
use Cake\Utility\Security;

/**
 * PatientAuthService
 *
 * Service for managing patient authentication including rate limiting,
 * account lockout, token management, and login attempt tracking.
 */
class PatientAuthService
{
    /**
     * Maximum failed login attempts before lockout
     */
    public const MAX_FAILED_ATTEMPTS = 3;

    /**
     * Lockout duration in seconds (1 hour)
     */
    public const LOCKOUT_DURATION = 3600;

    /**
     * Verification token expiry in seconds (24 hours)
     */
    public const VERIFICATION_TOKEN_EXPIRY = 86400;

    /**
     * Password reset token expiry in seconds (1 hour)
     */
    public const PASSWORD_RESET_EXPIRY = 3600;

    /**
     * @var \App\Model\Table\PatientsTable
     */
    private PatientsTable $patientsTable;

    /**
     * @var \App\Model\Table\LoginAttemptsTable
     */
    private LoginAttemptsTable $loginAttemptsTable;

    /**
     * Constructor
     */
    public function __construct()
    {
        /** @var \App\Model\Table\PatientsTable $patientsTable */
        $patientsTable = TableRegistry::getTableLocator()->get('Patients');
        $this->patientsTable = $patientsTable;

        /** @var \App\Model\Table\LoginAttemptsTable $loginAttemptsTable */
        $loginAttemptsTable = TableRegistry::getTableLocator()->get('LoginAttempts');
        $this->loginAttemptsTable = $loginAttemptsTable;
    }

    /**
     * Check if login is allowed for the given email and IP address.
     *
     * @param string $email The email address attempting to login
     * @param string $ipAddress The IP address of the login attempt
     * @return array{allowed: bool, reason: string|null}
     */
    public function isLoginAllowed(string $email, string $ipAddress): array
    {
        // Check if the patient account is locked
        $patient = $this->patientsTable->findByEmail($email)->first();

        if ($patient && $patient->is_locked) {
            $minutesLeft = (int)ceil(($patient->locked_until->getTimestamp() - time()) / 60);

            return [
                'allowed' => false,
                'reason' => "Contul este blocat. Încercați din nou în {$minutesLeft} minute.",
            ];
        }

        return [
            'allowed' => true,
            'reason' => null,
        ];
    }

    /**
     * Generate a secure verification token.
     *
     * @return string The generated token
     */
    public function generateVerificationToken(): string
    {
        return Security::randomString(64);
    }

    /**
     * Generate a password reset token for a patient.
     *
     * @param \App\Model\Entity\Patient $patient The patient entity
     * @return string The generated token
     */
    public function generatePasswordResetToken(Patient $patient): string
    {
        $token = Security::randomString(64);
        $patient->password_reset_token = $token;
        $patient->password_reset_expires = DateTime::now()->modify('+' . self::PASSWORD_RESET_EXPIRY . ' seconds');
        $this->patientsTable->save($patient);

        return $token;
    }

    /**
     * Verify a patient's email using the verification token.
     *
     * @param string $token The verification token
     * @return \App\Model\Entity\Patient|null The verified patient or null if invalid
     */
    public function verifyEmail(string $token): ?Patient
    {
        /** @var \App\Model\Entity\Patient|null $patient */
        $patient = $this->patientsTable->find()
            ->where(['verification_token' => $token])
            ->first();

        if (!$patient) {
            return null;
        }

        $patient->email_verified_at = DateTime::now();
        $patient->verification_token = null;
        $this->patientsTable->save($patient);

        return $patient;
    }

    /**
     * Validate a password reset token.
     *
     * @param string $token The password reset token
     * @return \App\Model\Entity\Patient|null The patient if valid, null otherwise
     */
    public function validatePasswordResetToken(string $token): ?Patient
    {
        /** @var \App\Model\Entity\Patient|null $patient */
        $patient = $this->patientsTable->find()
            ->where([
                'password_reset_token' => $token,
                'password_reset_expires >=' => DateTime::now(),
            ])
            ->first();

        return $patient;
    }

    /**
     * Reset a patient's password using a valid token.
     *
     * @param string $token The password reset token
     * @param string $newPassword The new password
     * @return bool True if successful, false otherwise
     */
    public function resetPassword(string $token, string $newPassword): bool
    {
        $patient = $this->validatePasswordResetToken($token);
        if (!$patient) {
            return false;
        }

        $patient->password = $newPassword;
        $patient->password_reset_token = null;
        $patient->password_reset_expires = null;
        $patient->failed_login_attempts = 0;
        $patient->locked_until = null;

        return (bool)$this->patientsTable->save($patient);
    }

    /**
     * Record a failed login attempt for a patient.
     *
     * @param string $email The email address
     * @return void
     */
    public function recordFailedAttempt(string $email): void
    {
        /** @var \App\Model\Entity\Patient|null $patient */
        $patient = $this->patientsTable->findByEmail($email)->first();
        if (!$patient) {
            return;
        }

        $patient->failed_login_attempts = ($patient->failed_login_attempts ?? 0) + 1;

        if ($patient->failed_login_attempts >= self::MAX_FAILED_ATTEMPTS) {
            $patient->locked_until = DateTime::now()->modify('+' . self::LOCKOUT_DURATION . ' seconds');
        }

        $this->patientsTable->save($patient);
    }

    /**
     * Record a login attempt in the login_attempts table.
     *
     * @param string $email The email address
     * @param string $ipAddress The IP address
     * @param string|null $userAgent The user agent string
     * @param bool $success Whether the login was successful
     * @return void
     */
    public function recordLoginAttempt(
        string $email,
        string $ipAddress,
        ?string $userAgent,
        bool $success,
    ): void {
        $attempt = $this->loginAttemptsTable->newEntity([
            'email' => $email,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'success' => $success,
            'attempted_at' => DateTime::now(),
        ]);

        $this->loginAttemptsTable->save($attempt);

        if (!$success) {
            $this->recordFailedAttempt($email);
        }
    }

    /**
     * Clear failed login attempts for a patient.
     *
     * @param \App\Model\Entity\Patient $patient The patient entity
     * @return void
     */
    public function clearFailedAttempts(Patient $patient): void
    {
        $patient->failed_login_attempts = 0;
        $patient->locked_until = null;
        $this->patientsTable->save($patient);
    }

    /**
     * Update the last login information for a patient.
     *
     * @param \App\Model\Entity\Patient $patient The patient entity
     * @param string $ipAddress The IP address
     * @return void
     */
    public function updateLastLogin(Patient $patient, string $ipAddress): void
    {
        $patient->last_login_at = DateTime::now();
        $patient->last_login_ip = $ipAddress;
        $this->patientsTable->save($patient);
    }

    /**
     * Clear login attempts on successful login.
     *
     * @param \App\Model\Entity\Patient $patient The patient entity
     * @param string $ipAddress The IP address
     * @return void
     */
    public function clearAttemptsOnSuccess(Patient $patient, string $ipAddress): void
    {
        $this->clearFailedAttempts($patient);
        $this->updateLastLogin($patient, $ipAddress);

        // Delete old failed attempts for this email
        $cutoffTime = DateTime::now()->modify('-' . self::LOCKOUT_DURATION . ' seconds');
        $this->loginAttemptsTable->deleteAll([
            'email' => $patient->email,
            'success' => false,
            'attempted_at <' => $cutoffTime,
        ]);
    }

    /**
     * Find a patient by email address.
     *
     * @param string $email The email address
     * @return \App\Model\Entity\Patient|null The patient or null if not found
     */
    public function findByEmail(string $email): ?Patient
    {
        /** @var \App\Model\Entity\Patient|null $patient */
        $patient = $this->patientsTable->findByEmail($email)->first();

        return $patient;
    }

    /**
     * Check if a patient's email is verified.
     *
     * @param string $email The email address
     * @return bool True if verified, false otherwise
     */
    public function isEmailVerified(string $email): bool
    {
        $patient = $this->findByEmail($email);

        return $patient !== null && $patient->is_email_verified;
    }
}
