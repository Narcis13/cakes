<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\I18n\DateTime;
use Cake\ORM\Entity;

/**
 * Patient Entity
 *
 * @property int $id
 * @property string $full_name
 * @property string $email
 * @property string $phone
 * @property string $password
 * @property \Cake\I18n\DateTime|null $email_verified_at
 * @property string|null $verification_token
 * @property string|null $password_reset_token
 * @property \Cake\I18n\DateTime|null $password_reset_expires
 * @property int $failed_login_attempts
 * @property \Cake\I18n\DateTime|null $locked_until
 * @property \Cake\I18n\DateTime|null $last_login_at
 * @property string|null $last_login_ip
 * @property bool $is_active
 * @property bool $orizont_extins_programare
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 *
 * @property \App\Model\Entity\Appointment[] $appointments
 *
 * @property-read bool $is_email_verified
 * @property-read bool $is_locked
 */
class Patient extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'full_name' => true,
        'email' => true,
        'phone' => true,
        'password' => true,
        // Security fields NOT accessible via mass assignment
        'email_verified_at' => false,
        'verification_token' => false,
        'password_reset_token' => false,
        'password_reset_expires' => false,
        'failed_login_attempts' => false,
        'locked_until' => false,
        'last_login_at' => false,
        'last_login_ip' => false,
        'is_active' => false,
        'orizont_extins_programare' => false,
        'created' => false,
        'modified' => false,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var list<string>
     */
    protected array $_hidden = [
        'password',
        'verification_token',
        'password_reset_token',
    ];

    /**
     * Hash password before saving
     *
     * @param string $password Plain text password
     * @return string Hashed password
     */
    protected function _setPassword(string $password): string
    {
        $hasher = new DefaultPasswordHasher();

        return $hasher->hash($password);
    }

    /**
     * Check if email is verified
     *
     * @return bool
     */
    protected function _getIsEmailVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    /**
     * Check if account is currently locked
     *
     * @return bool
     */
    protected function _getIsLocked(): bool
    {
        if ($this->locked_until === null) {
            return false;
        }

        return $this->locked_until > DateTime::now();
    }
}
