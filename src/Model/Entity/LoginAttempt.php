<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * LoginAttempt Entity
 *
 * @property int $id
 * @property string $email
 * @property string $ip_address
 * @property string|null $user_agent
 * @property bool $success
 * @property \Cake\I18n\DateTime $attempted_at
 */
class LoginAttempt extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'email' => true,
        'ip_address' => true,
        'user_agent' => true,
        'success' => true,
        'attempted_at' => false,
    ];
}
