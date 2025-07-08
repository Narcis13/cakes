<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * WorkflowSchedules Model
 *
 * @property \App\Model\Table\WorkflowsTable&\Cake\ORM\Association\BelongsTo $Workflows
 * @property \App\Model\Table\WorkflowExecutionsTable&\Cake\ORM\Association\BelongsTo $LastExecutions
 *
 * @method \App\Model\Entity\WorkflowSchedule newEmptyEntity()
 * @method \App\Model\Entity\WorkflowSchedule newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\WorkflowSchedule> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\WorkflowSchedule get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\WorkflowSchedule findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\WorkflowSchedule patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\WorkflowSchedule> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\WorkflowSchedule|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\WorkflowSchedule saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\WorkflowSchedule>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\WorkflowSchedule>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\WorkflowSchedule>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\WorkflowSchedule> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\WorkflowSchedule>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\WorkflowSchedule>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\WorkflowSchedule>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\WorkflowSchedule> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class WorkflowSchedulesTable extends Table
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

        $this->setTable('workflow_schedules');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Workflows', [
            'foreignKey' => 'workflow_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('LastExecutions', [
            'foreignKey' => 'last_execution_id',
            'className' => 'WorkflowExecutions',
        ]);
        
        $this->belongsTo('CreatedByUser', [
            'foreignKey' => 'created_by',
            'className' => 'Users',
            'propertyName' => 'creator',
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
            ->scalar('name')
            ->maxLength('name', 200)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->scalar('cron_expression')
            ->maxLength('cron_expression', 100)
            ->allowEmptyString('cron_expression')
            ->add('cron_expression', 'requiredForCron', [
                'rule' => function ($value, $context) {
                    if (isset($context['data']['schedule_type']) && $context['data']['schedule_type'] === 'cron') {
                        return !empty($value);
                    }
                    return true;
                },
                'message' => 'Cron expression is required for cron schedules',
            ]);

        $validator
            ->scalar('schedule_type')
            ->notEmptyString('schedule_type')
            ->inList('schedule_type', ['cron', 'interval', 'once'], 'Invalid schedule type');

        $validator
            ->integer('interval_minutes')
            ->allowEmptyString('interval_minutes')
            ->add('interval_minutes', 'requiredForInterval', [
                'rule' => function ($value, $context) {
                    if (isset($context['data']['schedule_type']) && $context['data']['schedule_type'] === 'interval') {
                        return !empty($value) && $value > 0;
                    }
                    return true;
                },
                'message' => 'Interval minutes is required and must be positive for interval schedules',
            ]);

        $validator
            ->dateTime('run_at')
            ->allowEmptyDateTime('run_at')
            ->add('run_at', 'requiredForOnce', [
                'rule' => function ($value, $context) {
                    if (isset($context['data']['schedule_type']) && $context['data']['schedule_type'] === 'once') {
                        return !empty($value);
                    }
                    return true;
                },
                'message' => 'Run at date is required for one-time schedules',
            ]);

        $validator
            ->scalar('input_data_json')
            ->allowEmptyString('input_data_json')
            ->add('input_data_json', 'validJson', [
                'rule' => function ($value, $context) {
                    if (empty($value)) {
                        return true;
                    }
                    json_decode($value);

                    return json_last_error() === JSON_ERROR_NONE;
                },
                'message' => 'Please provide valid JSON for the input data',
            ]);

        $validator
            ->scalar('timezone')
            ->maxLength('timezone', 50)
            ->notEmptyString('timezone');

        $validator
            ->boolean('is_active')
            ->notEmptyString('is_active');

        $validator
            ->dateTime('last_run_at')
            ->allowEmptyDateTime('last_run_at');

        $validator
            ->integer('last_execution_id')
            ->allowEmptyString('last_execution_id');

        $validator
            ->dateTime('next_run_at')
            ->allowEmptyDateTime('next_run_at');

        $validator
            ->integer('run_count')
            ->notEmptyString('run_count');

        $validator
            ->integer('max_runs')
            ->allowEmptyString('max_runs');

        $validator
            ->integer('created_by')
            ->requirePresence('created_by', 'create')
            ->notEmptyString('created_by');

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
        $rules->add($rules->existsIn(['workflow_id'], 'Workflows'), ['errorField' => 'workflow_id']);
        $rules->add($rules->existsIn(['last_execution_id'], 'LastExecutions'), ['errorField' => 'last_execution_id']);
        $rules->add($rules->existsIn(['created_by'], 'Users'), ['errorField' => 'created_by']);

        return $rules;
    }

    /**
     * Find active schedules
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findActive(SelectQuery $query): SelectQuery
    {
        return $query->where(['WorkflowSchedules.is_active' => true]);
    }

    /**
     * Find due schedules
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findDue(SelectQuery $query): SelectQuery
    {
        $now = new \DateTime();
        
        return $query
            ->where([
                'WorkflowSchedules.is_active' => true,
                'OR' => [
                    ['WorkflowSchedules.next_run_at IS' => null],
                    ['WorkflowSchedules.next_run_at <=' => $now],
                ],
            ])
            ->where(function ($exp) {
                return $exp->or([
                    'WorkflowSchedules.max_runs IS' => null,
                    'WorkflowSchedules.run_count <' => $exp->identifier('WorkflowSchedules.max_runs'),
                ]);
            });
    }

    /**
     * Find schedules by type
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param array $options Options with 'type' key
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findByType(SelectQuery $query, array $options): SelectQuery
    {
        if (empty($options['type'])) {
            throw new \InvalidArgumentException('type is required');
        }

        return $query->where(['WorkflowSchedules.schedule_type' => $options['type']]);
    }

    /**
     * Find schedules for a workflow
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param array $options Options with 'workflow_id' key
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findForWorkflow(SelectQuery $query, array $options): SelectQuery
    {
        if (empty($options['workflow_id'])) {
            throw new \InvalidArgumentException('workflow_id is required');
        }

        return $query->where(['WorkflowSchedules.workflow_id' => $options['workflow_id']]);
    }

    /**
     * Create schedule
     *
     * @param int $workflowId Workflow ID
     * @param array $scheduleData Schedule data
     * @return \App\Model\Entity\WorkflowSchedule
     */
    public function createSchedule(int $workflowId, array $scheduleData): \App\Model\Entity\WorkflowSchedule
    {
        $schedule = $this->newEntity(array_merge($scheduleData, [
            'workflow_id' => $workflowId,
            'run_count' => 0,
            'is_active' => true,
        ]));

        // Calculate initial next run time
        $schedule->next_run_at = $schedule->calculateNextRunTime();

        if (!$this->save($schedule)) {
            throw new \RuntimeException('Failed to create schedule');
        }

        return $schedule;
    }

    /**
     * Process due schedules
     *
     * @return array<\App\Model\Entity\WorkflowSchedule>
     */
    public function processDueSchedules(): array
    {
        $processed = [];
        $dueSchedules = $this->find('due')
            ->contain(['Workflows'])
            ->all();

        foreach ($dueSchedules as $schedule) {
            try {
                // Here you would trigger the workflow execution
                // For now, we'll just mark it as processed
                $schedule->markAsExecuted(0); // TODO: Use actual execution ID
                
                if ($this->save($schedule)) {
                    $processed[] = $schedule;
                }
            } catch (\Exception $e) {
                // Log error
                continue;
            }
        }

        return $processed;
    }
}
