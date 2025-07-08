<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * WorkflowExecutions Model
 *
 * @property \App\Model\Table\WorkflowsTable&\Cake\ORM\Association\BelongsTo $Workflows
 * @method \App\Model\Entity\WorkflowExecution newEmptyEntity()
 * @method \App\Model\Entity\WorkflowExecution newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\WorkflowExecution> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\WorkflowExecution get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\WorkflowExecution findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\WorkflowExecution patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\WorkflowExecution> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\WorkflowExecution|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\WorkflowExecution saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\WorkflowExecution>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\WorkflowExecution>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\WorkflowExecution>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\WorkflowExecution> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\WorkflowExecution>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\WorkflowExecution>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\WorkflowExecution>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\WorkflowExecution> deleteManyOrFail(iterable $entities, array $options = [])
 */
class WorkflowExecutionsTable extends Table
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

        $this->setTable('workflow_executions');
        $this->setDisplayField('status');
        $this->setPrimaryKey('id');

        $this->belongsTo('Workflows', [
            'foreignKey' => 'workflow_id',
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
            ->integer('workflow_id')
            ->notEmptyString('workflow_id');

        $validator
            ->scalar('status')
            ->notEmptyString('status');

        $validator
            ->scalar('current_node')
            ->maxLength('current_node', 100)
            ->allowEmptyString('current_node');

        $validator
            ->scalar('current_position')
            ->allowEmptyString('current_position');

        $validator
            ->scalar('state_json')
            ->requirePresence('state_json', 'create')
            ->notEmptyString('state_json');

        $validator
            ->scalar('input_data')
            ->allowEmptyString('input_data');

        $validator
            ->scalar('output_data')
            ->allowEmptyString('output_data');

        $validator
            ->scalar('error_message')
            ->allowEmptyString('error_message');

        $validator
            ->integer('started_by')
            ->requirePresence('started_by', 'create')
            ->notEmptyString('started_by');

        $validator
            ->dateTime('started_at')
            ->requirePresence('started_at', 'create')
            ->notEmptyDateTime('started_at');

        $validator
            ->dateTime('paused_at')
            ->allowEmptyDateTime('paused_at');

        $validator
            ->dateTime('completed_at')
            ->allowEmptyDateTime('completed_at');

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
        $rules->add($rules->existsIn(['workflow_id'], 'Workflows'), ['errorField' => 'workflow_id']);

        return $rules;
    }
}
