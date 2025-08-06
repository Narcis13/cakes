<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\WorkflowPermission;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use InvalidArgumentException;
use RuntimeException;

/**
 * WorkflowPermissions Model
 *
 * @property \App\Model\Table\WorkflowsTable&\Cake\ORM\Association\BelongsTo $Workflows
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @method \App\Model\Entity\WorkflowPermission newEmptyEntity()
 * @method \App\Model\Entity\WorkflowPermission newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\WorkflowPermission> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\WorkflowPermission get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\WorkflowPermission findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\WorkflowPermission patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\WorkflowPermission> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\WorkflowPermission|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\WorkflowPermission saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\WorkflowPermission>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\WorkflowPermission>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\WorkflowPermission>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\WorkflowPermission> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\WorkflowPermission>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\WorkflowPermission>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\WorkflowPermission>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\WorkflowPermission> deleteManyOrFail(iterable $entities, array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class WorkflowPermissionsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('workflow_permissions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Workflows', [
            'foreignKey' => 'workflow_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('workflow_id')
            ->notEmptyString('workflow_id');

        $validator
            ->integer('user_id')
            ->allowEmptyString('user_id');

        $validator
            ->scalar('role')
            ->maxLength('role', 50)
            ->allowEmptyString('role')
            ->add('role', 'eitherUserOrRole', [
                'rule' => function ($value, $context) {
                    // Either user_id or role must be set, but not both
                    $hasUserId = !empty($context['data']['user_id']);
                    $hasRole = !empty($value);

                    return ($hasUserId && !$hasRole) || (!$hasUserId && $hasRole);
                },
                'message' => 'Permission must be either user-based or role-based, not both',
            ]);

        $validator
            ->boolean('can_execute')
            ->notEmptyString('can_execute');

        $validator
            ->boolean('can_edit')
            ->notEmptyString('can_edit');

        $validator
            ->boolean('can_delete')
            ->notEmptyString('can_delete');

        $validator
            ->boolean('can_view_logs')
            ->notEmptyString('can_view_logs');

        $validator
            ->boolean('can_manage_permissions')
            ->notEmptyString('can_manage_permissions');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['workflow_id', 'user_id'], ['allowMultipleNulls' => true]), ['errorField' => 'workflow_id']);
        $rules->add($rules->isUnique(['workflow_id', 'role'], ['allowMultipleNulls' => true]), ['errorField' => 'workflow_id']);
        $rules->add($rules->existsIn(['workflow_id'], 'Workflows'), ['errorField' => 'workflow_id']);
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }

    /**
     * Find permissions for a user
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param array $options Options with 'user_id' key
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findForUser(SelectQuery $query, array $options): SelectQuery
    {
        if (empty($options['user_id'])) {
            throw new InvalidArgumentException('user_id is required');
        }

        return $query->where(['WorkflowPermissions.user_id' => $options['user_id']]);
    }

    /**
     * Find permissions for a role
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param array $options Options with 'role' key
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findForRole(SelectQuery $query, array $options): SelectQuery
    {
        if (empty($options['role'])) {
            throw new InvalidArgumentException('role is required');
        }

        return $query->where(['WorkflowPermissions.role' => $options['role']]);
    }

    /**
     * Find permissions for a workflow
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param array $options Options with 'workflow_id' key
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findForWorkflow(SelectQuery $query, array $options): SelectQuery
    {
        if (empty($options['workflow_id'])) {
            throw new InvalidArgumentException('workflow_id is required');
        }

        return $query
            ->where(['WorkflowPermissions.workflow_id' => $options['workflow_id']])
            ->contain(['Users']);
    }

    /**
     * Check if user has permission for workflow
     *
     * @param int $workflowId Workflow ID
     * @param int $userId User ID
     * @param string $permission Permission to check
     * @param array|null $userRoles User's roles
     * @return bool
     */
    public function hasPermission(int $workflowId, int $userId, string $permission, ?array $userRoles = null): bool
    {
        // Check direct user permission
        $userPermission = $this->find()
            ->where([
                'workflow_id' => $workflowId,
                'user_id' => $userId,
            ])
            ->first();

        if ($userPermission && $userPermission->hasPermission($permission)) {
            return true;
        }

        // Check role-based permissions
        if (!empty($userRoles)) {
            $rolePermission = $this->find()
                ->where([
                    'workflow_id' => $workflowId,
                    'role IN' => $userRoles,
                ])
                ->first();

            if ($rolePermission && $rolePermission->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Grant permission
     *
     * @param int $workflowId Workflow ID
     * @param array $permissions Permission data
     * @return \App\Model\Entity\WorkflowPermission
     */
    public function grantPermission(int $workflowId, array $permissions): WorkflowPermission
    {
        $permission = $this->newEntity(array_merge($permissions, [
            'workflow_id' => $workflowId,
        ]));

        if (!$this->save($permission)) {
            throw new RuntimeException('Failed to grant permission');
        }

        return $permission;
    }

    /**
     * Grant full access
     *
     * @param int $workflowId Workflow ID
     * @param int|null $userId User ID
     * @param string|null $role Role
     * @return \App\Model\Entity\WorkflowPermission
     */
    public function grantFullAccess(int $workflowId, ?int $userId = null, ?string $role = null): WorkflowPermission
    {
        $permission = $this->newEntity([
            'workflow_id' => $workflowId,
            'user_id' => $userId,
            'role' => $role,
            'can_execute' => true,
            'can_edit' => true,
            'can_delete' => true,
            'can_view_logs' => true,
            'can_manage_permissions' => true,
        ]);

        if (!$this->save($permission)) {
            throw new RuntimeException('Failed to grant full access');
        }

        return $permission;
    }
}
