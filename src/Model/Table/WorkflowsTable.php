<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Workflows Model
 *
 * @property \App\Model\Table\WorkflowExecutionsTable&\Cake\ORM\Association\HasMany $WorkflowExecutions
 * @property \App\Model\Table\WorkflowPermissionsTable&\Cake\ORM\Association\HasMany $WorkflowPermissions
 * @property \App\Model\Table\WorkflowSchedulesTable&\Cake\ORM\Association\HasMany $WorkflowSchedules
 * @method \App\Model\Entity\Workflow newEmptyEntity()
 * @method \App\Model\Entity\Workflow newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Workflow> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Workflow get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Workflow findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Workflow patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Workflow> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Workflow|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Workflow saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Workflow>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Workflow>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Workflow>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Workflow> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Workflow>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Workflow>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Workflow>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Workflow> deleteManyOrFail(iterable $entities, array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class WorkflowsTable extends Table
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

        $this->setTable('workflows');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('CreatedByUser', [
            'className' => 'Users',
            'foreignKey' => 'created_by',
            'propertyName' => 'creator',
        ]);
        $this->hasMany('WorkflowExecutions', [
            'foreignKey' => 'workflow_id',
            'dependent' => true,
        ]);
        $this->hasMany('WorkflowPermissions', [
            'foreignKey' => 'workflow_id',
            'dependent' => true,
        ]);
        $this->hasMany('WorkflowSchedules', [
            'foreignKey' => 'workflow_id',
            'dependent' => true,
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
            ->scalar('name')
            ->maxLength('name', 200)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->scalar('definition_json')
            ->requirePresence('definition_json', 'create')
            ->notEmptyString('definition_json')
            ->add('definition_json', 'validJson', [
                'rule' => function ($value, $context) {
                    json_decode($value);

                    return json_last_error() === JSON_ERROR_NONE;
                },
                'message' => 'Please provide valid JSON for the workflow definition',
            ]);

        $validator
            ->integer('version')
            ->notEmptyString('version');

        $validator
            ->scalar('status')
            ->notEmptyString('status');

        $validator
            ->scalar('category')
            ->maxLength('category', 100)
            ->allowEmptyString('category');

        $validator
            ->scalar('icon')
            ->maxLength('icon', 50)
            ->allowEmptyString('icon');

        $validator
            ->integer('created_by')
            ->requirePresence('created_by', 'create')
            ->notEmptyString('created_by');

        $validator
            ->boolean('is_template')
            ->notEmptyString('is_template');

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
        $rules->add($rules->existsIn(['created_by'], 'Users'), ['errorField' => 'created_by']);

        return $rules;
    }

    /**
     * Find active workflows
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findActive(SelectQuery $query): SelectQuery
    {
        return $query->where(['Workflows.status' => 'active']);
    }

    /**
     * Find template workflows
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findTemplates(SelectQuery $query): SelectQuery
    {
        return $query->where(['Workflows.is_template' => true]);
    }
}
