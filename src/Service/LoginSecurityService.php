<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Table\LoginAttemptsTable;
use App\Model\Table\UsersTable;
use Cake\I18n\DateTime;
use Cake\ORM\TableRegistry;

/**
 * LoginSecurityService
 *
 * Service for managing login security including rate limiting,
 * account lockout, and login attempt tracking.
 */
class LoginSecurityService
{
    /**
     * Maximum failed login attempts per email before lockout
     */
    public const MAX_EMAIL_ATTEMPTS = 3;

    /**
     * Maximum failed login attempts per IP before lockout
     */
    public const MAX_IP_ATTEMPTS = 10;

    /**
     * Lockout duration in seconds (1 hour)
     */
    public const LOCKOUT_DURATION = 3600;

    /**
     * @var \App\Model\Table\LoginAttemptsTable
     */
    private LoginAttemptsTable $loginAttemptsTable;

    /**
     * @var \App\Model\Table\UsersTable
     */
    private UsersTable $usersTable;

    /**
     * Constructor
     */
    public function __construct()
    {
        /** @var \App\Model\Table\LoginAttemptsTable $loginAttemptsTable */
        $loginAttemptsTable = TableRegistry::getTableLocator()->get('LoginAttempts');
        $this->loginAttemptsTable = $loginAttemptsTable;

        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = TableRegistry::getTableLocator()->get('Users');
        $this->usersTable = $usersTable;
    }

    /**
     * Check if login is allowed for the given email and IP address.
     *
     * @param string $email The email address attempting to login
     * @param string $ipAddress The IP address of the login attempt
     * @return array{allowed: bool, reason: string|null, remaining_time: int|null}
     */
    public function isLoginAllowed(string $email, string $ipAddress): array
    {
        // Check if the user account is locked
        $user = $this->usersTable->find()
            ->where(['email' => $email])
            ->first();

        if ($user && $user->locked_until !== null) {
            $lockedUntil = $user->locked_until;
            $now = DateTime::now();

            if ($lockedUntil > $now) {
                $remainingSeconds = $lockedUntil->getTimestamp() - $now->getTimestamp();

                return [
                    'allowed' => false,
                    'reason' => 'Account temporarily locked. Try again later.',
                    'remaining_time' => $remainingSeconds,
                ];
            }

            // Lockout has expired, clear it
            $this->clearUserLockout($email);
        }

        // Check email-based rate limiting
        $emailAttempts = $this->getFailedAttemptCount($email, 'email');
        if ($emailAttempts >= self::MAX_EMAIL_ATTEMPTS) {
            $remainingTime = $this->getLockoutRemainingTime($email, 'email');
            if ($remainingTime > 0) {
                return [
                    'allowed' => false,
                    'reason' => 'Account temporarily locked. Try again later.',
                    'remaining_time' => $remainingTime,
                ];
            }
        }

        // Check IP-based rate limiting
        $ipAttempts = $this->getFailedAttemptCount($ipAddress, 'ip');
        if ($ipAttempts >= self::MAX_IP_ATTEMPTS) {
            $remainingTime = $this->getLockoutRemainingTime($ipAddress, 'ip');
            if ($remainingTime > 0) {
                return [
                    'allowed' => false,
                    'reason' => 'Too many login attempts. Try again later.',
                    'remaining_time' => $remainingTime,
                ];
            }
        }

        return [
            'allowed' => true,
            'reason' => null,
            'remaining_time' => null,
        ];
    }

    /**
     * Record a login attempt.
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

        // Update user's failed login count if login failed
        if (!$success) {
            $this->incrementUserFailedAttempts($email);
        }
    }

    /**
     * Clear login attempts on successful login.
     *
     * @param string $email The email address
     * @param string $ipAddress The IP address
     * @return void
     */
    public function clearAttemptsOnSuccess(string $email, string $ipAddress): void
    {
        // Clear user's failed login counter and update last login
        $user = $this->usersTable->find()
            ->where(['email' => $email])
            ->first();

        if ($user) {
            $user->failed_login_attempts = 0;
            $user->locked_until = null;
            $user->last_login_at = DateTime::now();
            $user->last_login_ip = $ipAddress;
            $this->usersTable->save($user);
        }

        // Delete old failed attempts for this email
        $cutoffTime = DateTime::now()->modify('-' . self::LOCKOUT_DURATION . ' seconds');
        $this->loginAttemptsTable->deleteAll([
            'email' => $email,
            'success' => false,
            'attempted_at <' => $cutoffTime,
        ]);
    }

    /**
     * Get the remaining lockout time in seconds.
     *
     * @param string $identifier The email or IP address
     * @param string $type Either 'email' or 'ip'
     * @return int Remaining seconds, or 0 if not locked
     */
    public function getLockoutRemainingTime(string $identifier, string $type): int
    {
        $field = $type === 'email' ? 'email' : 'ip_address';
        $cutoffTime = DateTime::now()->modify('-' . self::LOCKOUT_DURATION . ' seconds');

        $lastAttempt = $this->loginAttemptsTable->find()
            ->where([
                $field => $identifier,
                'success' => false,
                'attempted_at >=' => $cutoffTime,
            ])
            ->orderByDesc('attempted_at')
            ->first();

        if (!$lastAttempt) {
            return 0;
        }

        $lockoutExpires = $lastAttempt->attempted_at->modify('+' . self::LOCKOUT_DURATION . ' seconds');
        $now = DateTime::now();

        if ($lockoutExpires > $now) {
            return $lockoutExpires->getTimestamp() - $now->getTimestamp();
        }

        return 0;
    }

    /**
     * Get the count of failed login attempts within the lockout window.
     *
     * @param string $identifier The email or IP address
     * @param string $type Either 'email' or 'ip'
     * @return int The count of failed attempts
     */
    private function getFailedAttemptCount(string $identifier, string $type): int
    {
        $field = $type === 'email' ? 'email' : 'ip_address';
        $cutoffTime = DateTime::now()->modify('-' . self::LOCKOUT_DURATION . ' seconds');

        return $this->loginAttemptsTable->find()
            ->where([
                $field => $identifier,
                'success' => false,
                'attempted_at >=' => $cutoffTime,
            ])
            ->count();
    }

    /**
     * Increment the user's failed login attempt counter.
     *
     * @param string $email The email address
     * @return void
     */
    private function incrementUserFailedAttempts(string $email): void
    {
        $user = $this->usersTable->find()
            ->where(['email' => $email])
            ->first();

        if ($user) {
            $user->failed_login_attempts = ($user->failed_login_attempts ?? 0) + 1;

            // Lock account if exceeded max attempts
            if ($user->failed_login_attempts >= self::MAX_EMAIL_ATTEMPTS) {
                $user->locked_until = DateTime::now()->modify('+' . self::LOCKOUT_DURATION . ' seconds');
            }

            $this->usersTable->save($user);
        }
    }

    /**
     * Clear the user's lockout status.
     *
     * @param string $email The email address
     * @return void
     */
    private function clearUserLockout(string $email): void
    {
        $user = $this->usersTable->find()
            ->where(['email' => $email])
            ->first();

        if ($user) {
            $user->failed_login_attempts = 0;
            $user->locked_until = null;
            $this->usersTable->save($user);
        }
    }
}
