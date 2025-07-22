<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Specializations Model
 *
 * @property \App\Model\Table\StaffTable&\Cake\ORM\Association\HasMany $Staff
 *
 * @method \App\Model\Entity\Specialization newEmptyEntity()
 * @method \App\Model\Entity\Specialization newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Specialization> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Specialization get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Specialization findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Specialization patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Specialization> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Specialization|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Specialization saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Specialization>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Specialization>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Specialization>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Specialization> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Specialization>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Specialization>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Specialization>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Specialization> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SpecializationsTable extends Table
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

        $this->setTable('specializations');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Staff', [
            'foreignKey' => 'specialization_id',
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
            ->maxLength('name', 100)
            ->requirePresence('name', 'create')
            ->notEmptyString('name')
            ->add('name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

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
