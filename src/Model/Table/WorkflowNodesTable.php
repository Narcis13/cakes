<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * WorkflowNodes Model
 *
 * @method \App\Model\Entity\WorkflowNode newEmptyEntity()
 * @method \App\Model\Entity\WorkflowNode newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\WorkflowNode> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\WorkflowNode get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\WorkflowNode findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\WorkflowNode patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\WorkflowNode> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\WorkflowNode|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\WorkflowNode saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\WorkflowNode>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\WorkflowNode>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\WorkflowNode>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\WorkflowNode> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\WorkflowNode>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\WorkflowNode>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\WorkflowNode>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\WorkflowNode> deleteManyOrFail(iterable $entities, array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class WorkflowNodesTable extends Table
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

        $this->setTable('workflow_nodes');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->scalar('name')
            ->maxLength('name', 100)
            ->requirePresence('name', 'create')
            ->notEmptyString('name')
            ->add('name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('type')
            ->requirePresence('type', 'create')
            ->notEmptyString('type');

        $validator
            ->scalar('category')
            ->maxLength('category', 50)
            ->allowEmptyString('category');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->scalar('metadata_json')
            ->requirePresence('metadata_json', 'create')
            ->notEmptyString('metadata_json');

        $validator
            ->scalar('handler_class')
            ->maxLength('handler_class', 255)
            ->requirePresence('handler_class', 'create')
            ->notEmptyString('handler_class');

        $validator
            ->scalar('icon')
            ->maxLength('icon', 50)
            ->allowEmptyString('icon');

        $validator
            ->boolean('is_builtin')
            ->notEmptyString('is_builtin');

        $validator
            ->boolean('is_active')
            ->notEmptyString('is_active');

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
        $rules->add($rules->isUnique(['name']), ['errorField' => 'name']);

        return $rules;
    }
}
