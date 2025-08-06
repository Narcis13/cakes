<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\WorkflowExecutionLog;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use InvalidArgumentException;
use RuntimeException;

/**
 * WorkflowExecutionLogs Model
 *
 * @property \App\Model\Table\WorkflowExecutionsTable&\Cake\ORM\Association\BelongsTo $Executions
 * @method \App\Model\Entity\WorkflowExecutionLog newEmptyEntity()
 * @method \App\Model\Entity\WorkflowExecutionLog newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\WorkflowExecutionLog> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\WorkflowExecutionLog get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\WorkflowExecutionLog findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\WorkflowExecutionLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\WorkflowExecutionLog> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\WorkflowExecutionLog|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\WorkflowExecutionLog saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\WorkflowExecutionLog>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\WorkflowExecutionLog>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\WorkflowExecutionLog>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\WorkflowExecutionLog> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\WorkflowExecutionLog>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\WorkflowExecutionLog>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\WorkflowExecutionLog>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\WorkflowExecutionLog> deleteManyOrFail(iterable $entities, array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class WorkflowExecutionLogsTable extends Table
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

        $this->setTable('workflow_execution_logs');
        $this->setDisplayField('node_name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Executions', [
            'foreignKey' => 'execution_id',
            'className' => 'WorkflowExecutions',
            'joinType' => 'INNER',
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
            ->scalar('node_type')
            ->maxLength('node_type', 50)
            ->allowEmptyString('node_type');

        $validator
            ->scalar('edge_taken')
            ->maxLength('edge_taken', 100)
            ->allowEmptyString('edge_taken');

        $validator
            ->scalar('level')
            ->notEmptyString('level');

        $validator
            ->scalar('message')
            ->allowEmptyString('message');

        $validator
            ->scalar('data_json')
            ->allowEmptyString('data_json')
            ->add('data_json', 'validJson', [
                'rule' => function ($value, $context) {
                    if (empty($value)) {
                        return true;
                    }
                    json_decode($value);

                    return json_last_error() === JSON_ERROR_NONE;
                },
                'message' => 'Please provide valid JSON for the data',
            ]);

        $validator
            ->scalar('state_snapshot')
            ->allowEmptyString('state_snapshot')
            ->add('state_snapshot', 'validJson', [
                'rule' => function ($value, $context) {
                    if (empty($value)) {
                        return true;
                    }
                    json_decode($value);

                    return json_last_error() === JSON_ERROR_NONE;
                },
                'message' => 'Please provide valid JSON for the state snapshot',
            ]);

        $validator
            ->integer('execution_time')
            ->allowEmptyString('execution_time');

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

        return $rules;
    }

    /**
     * Find logs by level
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param array $options Options with 'level' key
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findByLevel(SelectQuery $query, array $options): SelectQuery
    {
        if (empty($options['level'])) {
            throw new InvalidArgumentException('level is required');
        }

        return $query->where(['WorkflowExecutionLogs.level' => $options['level']]);
    }

    /**
     * Find logs for a specific node
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param array $options Options with 'node_name' key
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findByNode(SelectQuery $query, array $options): SelectQuery
    {
        if (empty($options['node_name'])) {
            throw new InvalidArgumentException('node_name is required');
        }

        return $query->where(['WorkflowExecutionLogs.node_name' => $options['node_name']]);
    }

    /**
     * Find error logs
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findErrors(SelectQuery $query): SelectQuery
    {
        return $query->where(['WorkflowExecutionLogs.level' => 'error']);
    }

    /**
     * Log a workflow event
     *
     * @param int $executionId Execution ID
     * @param string $nodeName Node name
     * @param string $level Log level
     * @param string $message Log message
     * @param array $options Additional options
     * @return \App\Model\Entity\WorkflowExecutionLog
     */
    public function log(
        int $executionId,
        string $nodeName,
        string $level,
        string $message,
        array $options = [],
    ): WorkflowExecutionLog {
        $log = $this->newEntity([
            'execution_id' => $executionId,
            'node_name' => $nodeName,
            'level' => $level,
            'message' => $message,
            'node_type' => $options['node_type'] ?? null,
            'edge_taken' => $options['edge_taken'] ?? null,
            'data_json' => isset($options['data']) ? json_encode($options['data']) : null,
            'state_snapshot' => isset($options['state']) ? json_encode($options['state']) : null,
            'execution_time' => $options['execution_time'] ?? null,
        ]);

        if (!$this->save($log)) {
            throw new RuntimeException('Failed to save workflow execution log');
        }

        return $log;
    }

    /**
     * Log an error
     *
     * @param int $executionId Execution ID
     * @param string $nodeName Node name
     * @param string $message Error message
     * @param array $data Additional data
     * @return \App\Model\Entity\WorkflowExecutionLog
     */
    public function logError(int $executionId, string $nodeName, string $message, array $data = []): WorkflowExecutionLog
    {
        return $this->log($executionId, $nodeName, 'error', $message, ['data' => $data]);
    }

    /**
     * Log a warning
     *
     * @param int $executionId Execution ID
     * @param string $nodeName Node name
     * @param string $message Warning message
     * @param array $data Additional data
     * @return \App\Model\Entity\WorkflowExecutionLog
     */
    public function logWarning(int $executionId, string $nodeName, string $message, array $data = []): WorkflowExecutionLog
    {
        return $this->log($executionId, $nodeName, 'warning', $message, ['data' => $data]);
    }

    /**
     * Log info
     *
     * @param int $executionId Execution ID
     * @param string $nodeName Node name
     * @param string $message Info message
     * @param array $data Additional data
     * @return \App\Model\Entity\WorkflowExecutionLog
     */
    public function logInfo(int $executionId, string $nodeName, string $message, array $data = []): WorkflowExecutionLog
    {
        return $this->log($executionId, $nodeName, 'info', $message, ['data' => $data]);
    }

    /**
     * Log debug
     *
     * @param int $executionId Execution ID
     * @param string $nodeName Node name
     * @param string $message Debug message
     * @param array $data Additional data
     * @return \App\Model\Entity\WorkflowExecutionLog
     */
    public function logDebug(int $executionId, string $nodeName, string $message, array $data = []): WorkflowExecutionLog
    {
        return $this->log($executionId, $nodeName, 'debug', $message, ['data' => $data]);
    }
}
