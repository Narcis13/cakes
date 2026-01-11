<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AuditLog Entity
 *
 * Represents an audit log entry tracking admin actions on sensitive data.
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $action
 * @property string $model
 * @property int|null $record_id
 * @property array<string, mixed>|null $old_values
 * @property array<string, mixed>|null $new_values
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property \Cake\I18n\DateTime $created
 *
 * @property \App\Model\Entity\User|null $user
 */
class AuditLog extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'user_id' => true,
        'action' => true,
        'model' => true,
        'record_id' => true,
        'old_values' => true,
        'new_values' => true,
        'ip_address' => true,
        'user_agent' => true,
        'created' => false,
    ];

    /**
     * Virtual fields for JSON serialization of old/new values
     *
     * @var list<string>
     */
    protected array $_virtual = ['old_values_decoded', 'new_values_decoded'];

    /**
     * Get decoded old values
     *
     * @return array<string, mixed>|null
     */
    protected function _getOldValuesDecoded(): ?array
    {
        if ($this->old_values === null) {
            return null;
        }

        if (is_array($this->old_values)) {
            return $this->old_values;
        }

        $decoded = json_decode((string)$this->old_values, true);

        return is_array($decoded) ? $decoded : null;
    }

    /**
     * Get decoded new values
     *
     * @return array<string, mixed>|null
     */
    protected function _getNewValuesDecoded(): ?array
    {
        if ($this->new_values === null) {
            return null;
        }

        if (is_array($this->new_values)) {
            return $this->new_values;
        }

        $decoded = json_decode((string)$this->new_values, true);

        return is_array($decoded) ? $decoded : null;
    }
}
