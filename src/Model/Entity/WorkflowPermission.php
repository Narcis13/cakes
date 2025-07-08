<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * WorkflowPermission Entity
 *
 * @property int $id
 * @property int $workflow_id
 * @property int|null $user_id
 * @property string|null $role
 * @property bool $can_execute
 * @property bool $can_edit
 * @property bool $can_delete
 * @property bool $can_view_logs
 * @property bool $can_manage_permissions
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 *
 * @property \App\Model\Entity\Workflow $workflow
 * @property \App\Model\Entity\User $user
 * @property-read bool $is_role_based
 * @property-read bool $is_user_based
 * @property-read bool $has_full_access
 * @property-read string $permission_type
 */
class WorkflowPermission extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'workflow_id' => true,
        'user_id' => true,
        'role' => true,
        'can_execute' => true,
        'can_edit' => true,
        'can_delete' => true,
        'can_view_logs' => true,
        'can_manage_permissions' => true,
        'created' => true,
        'modified' => true,
        'workflow' => true,
        'user' => true,
    ];

    /**
     * List of computed properties
     *
     * @var array<string>
     */
    protected array $_virtual = ['is_role_based', 'is_user_based', 'has_full_access', 'permission_type'];

    /**
     * Check if permission is role-based
     *
     * @return bool
     */
    protected function _getIsRoleBased(): bool
    {
        return !empty($this->role) && empty($this->user_id);
    }

    /**
     * Check if permission is user-based
     *
     * @return bool
     */
    protected function _getIsUserBased(): bool
    {
        return !empty($this->user_id);
    }

    /**
     * Check if user has full access
     *
     * @return bool
     */
    protected function _getHasFullAccess(): bool
    {
        return $this->can_execute &&
               $this->can_edit &&
               $this->can_delete &&
               $this->can_view_logs &&
               $this->can_manage_permissions;
    }

    /**
     * Get permission type
     *
     * @return string
     */
    protected function _getPermissionType(): string
    {
        if ($this->is_user_based) {
            return 'user';
        } elseif ($this->is_role_based) {
            return 'role';
        }

        return 'unknown';
    }

    /**
     * Grant all permissions
     *
     * @return void
     */
    public function grantAll(): void
    {
        $this->can_execute = true;
        $this->can_edit = true;
        $this->can_delete = true;
        $this->can_view_logs = true;
        $this->can_manage_permissions = true;
    }

    /**
     * Revoke all permissions
     *
     * @return void
     */
    public function revokeAll(): void
    {
        $this->can_execute = false;
        $this->can_edit = false;
        $this->can_delete = false;
        $this->can_view_logs = false;
        $this->can_manage_permissions = false;
    }

    /**
     * Check if user has specific permission
     *
     * @param string $permission Permission to check
     * @return bool
     */
    public function hasPermission(string $permission): bool
    {
        return match($permission) {
            'execute' => $this->can_execute,
            'edit' => $this->can_edit,
            'delete' => $this->can_delete,
            'view_logs' => $this->can_view_logs,
            'manage_permissions' => $this->can_manage_permissions,
            default => false
        };
    }

    /**
     * Get list of granted permissions
     *
     * @return array<string>
     */
    public function getGrantedPermissions(): array
    {
        $permissions = [];
        
        if ($this->can_execute) {
            $permissions[] = 'execute';
        }
        if ($this->can_edit) {
            $permissions[] = 'edit';
        }
        if ($this->can_delete) {
            $permissions[] = 'delete';
        }
        if ($this->can_view_logs) {
            $permissions[] = 'view_logs';
        }
        if ($this->can_manage_permissions) {
            $permissions[] = 'manage_permissions';
        }
        
        return $permissions;
    }
}
