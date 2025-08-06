<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\WorkflowHumanTask;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use DateTime;
use InvalidArgumentException;
use RuntimeException;

/**
 * WorkflowHumanTasks Model
 *
 * @property \App\Model\Table\WorkflowExecutionsTable&\Cake\ORM\Association\BelongsTo $Executions
 * @method \App\Model\Entity\WorkflowHumanTask newEmptyEntity()
 * @method \App\Model\Entity\WorkflowHumanTask newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\WorkflowHumanTask> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\WorkflowHumanTask get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\WorkflowHumanTask findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\WorkflowHumanTask patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\WorkflowHumanTask> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\WorkflowHumanTask|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\WorkflowHumanTask saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\WorkflowHumanTask>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\WorkflowHumanTask>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\WorkflowHumanTask>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\WorkflowHumanTask> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\WorkflowHumanTask>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\WorkflowHumanTask>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\WorkflowHumanTask>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\WorkflowHumanTask> deleteManyOrFail(iterable $entities, array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class WorkflowHumanTasksTable extends Table
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

        $this->setTable('workflow_human_tasks');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Executions', [
            'foreignKey' => 'execution_id',
            'className' => 'WorkflowExecutions',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('AssignedUsers', [
            'foreignKey' => 'assigned_to',
            'className' => 'Users',
            'propertyName' => 'assigned_user',
        ]);

        $this->belongsTo('CompletedByUsers', [
            'foreignKey' => 'completed_by',
            'className' => 'Users',
            'propertyName' => 'completed_user',
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
            ->integer('execution_id')
            ->notEmptyString('execution_id');

        $validator
            ->scalar('node_name')
            ->maxLength('node_name', 100)
            ->requirePresence('node_name', 'create')
            ->notEmptyString('node_name');

        $validator
            ->scalar('title')
            ->maxLength('title', 200)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->scalar('form_schema_json')
            ->allowEmptyString('form_schema_json')
            ->add('form_schema_json', 'validJson', [
                'rule' => function ($value, $context) {
                    if (empty($value)) {
                        return true;
                    }
                    json_decode($value);

                    return json_last_error() === JSON_ERROR_NONE;
                },
                'message' => 'Please provide valid JSON for the form schema',
            ]);

        $validator
            ->scalar('context_data_json')
            ->allowEmptyString('context_data_json')
            ->add('context_data_json', 'validJson', [
                'rule' => function ($value, $context) {
                    if (empty($value)) {
                        return true;
                    }
                    json_decode($value);

                    return json_last_error() === JSON_ERROR_NONE;
                },
                'message' => 'Please provide valid JSON for the context data',
            ]);

        $validator
            ->integer('assigned_to')
            ->allowEmptyString('assigned_to');

        $validator
            ->scalar('assigned_role')
            ->maxLength('assigned_role', 50)
            ->allowEmptyString('assigned_role');

        $validator
            ->scalar('priority')
            ->notEmptyString('priority');

        $validator
            ->scalar('status')
            ->notEmptyString('status');

        $validator
            ->scalar('response_data_json')
            ->allowEmptyString('response_data_json')
            ->add('response_data_json', 'validJson', [
                'rule' => function ($value, $context) {
                    if (empty($value)) {
                        return true;
                    }
                    json_decode($value);

                    return json_last_error() === JSON_ERROR_NONE;
                },
                'message' => 'Please provide valid JSON for the response data',
            ]);

        $validator
            ->integer('completed_by')
            ->allowEmptyString('completed_by');

        $validator
            ->dateTime('due_at')
            ->allowEmptyDateTime('due_at');

        $validator
            ->dateTime('assigned_at')
            ->allowEmptyDateTime('assigned_at');

        $validator
            ->dateTime('completed_at')
            ->allowEmptyDateTime('completed_at');

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
        $rules->add($rules->existsIn(['execution_id'], 'Executions'), ['errorField' => 'execution_id']);
        $rules->add($rules->existsIn(['assigned_to'], 'Users'), ['errorField' => 'assigned_to']);
        $rules->add($rules->existsIn(['completed_by'], 'Users'), ['errorField' => 'completed_by']);

        return $rules;
    }

    /**
     * Find pending tasks
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findPending(SelectQuery $query): SelectQuery
    {
        return $query->where(['WorkflowHumanTasks.status' => 'pending']);
    }

    /**
     * Find tasks assigned to a user
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param array $options Options with 'user_id' key
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findAssignedTo(SelectQuery $query, array $options): SelectQuery
    {
        if (empty($options['user_id'])) {
            throw new InvalidArgumentException('user_id is required');
        }

        return $query->where(['WorkflowHumanTasks.assigned_to' => $options['user_id']]);
    }

    /**
     * Find tasks for a role
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

        return $query->where(['WorkflowHumanTasks.assigned_role' => $options['role']]);
    }

    /**
     * Find overdue tasks
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findOverdue(SelectQuery $query): SelectQuery
    {
        return $query->where([
            'WorkflowHumanTasks.due_at IS NOT' => null,
            'WorkflowHumanTasks.due_at <' => new DateTime(),
            'WorkflowHumanTasks.status NOT IN' => ['completed', 'cancelled'],
        ]);
    }

    /**
     * Find tasks by priority
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param array $options Options with 'priority' key
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findByPriority(SelectQuery $query, array $options): SelectQuery
    {
        if (empty($options['priority'])) {
            throw new InvalidArgumentException('priority is required');
        }

        return $query->where(['WorkflowHumanTasks.priority' => $options['priority']]);
    }

    /**
     * Find active tasks (pending, assigned, or in_progress)
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findActive(SelectQuery $query): SelectQuery
    {
        return $query->where([
            'WorkflowHumanTasks.status IN' => ['pending', 'assigned', 'in_progress'],
        ]);
    }

    /**
     * Create a task from workflow node
     *
     * @param int $executionId Workflow execution ID
     * @param string $nodeName Node name
     * @param array $taskData Task data
     * @return \App\Model\Entity\WorkflowHumanTask
     */
    public function createFromNode(int $executionId, string $nodeName, array $taskData): WorkflowHumanTask
    {
        $task = $this->newEntity([
            'execution_id' => $executionId,
            'node_name' => $nodeName,
            'title' => $taskData['title'] ?? 'Task from ' . $nodeName,
            'description' => $taskData['description'] ?? null,
            'form_schema_json' => isset($taskData['form_schema']) ? json_encode($taskData['form_schema']) : null,
            'context_data_json' => isset($taskData['context_data']) ? json_encode($taskData['context_data']) : null,
            'assigned_to' => $taskData['assigned_to'] ?? null,
            'assigned_role' => $taskData['assigned_role'] ?? null,
            'priority' => $taskData['priority'] ?? 'medium',
            'due_at' => $taskData['due_at'] ?? null,
        ]);

        if (!$this->save($task)) {
            throw new RuntimeException('Failed to create human task');
        }

        return $task;
    }
}
