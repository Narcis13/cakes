<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * StaffUnavailabilities Model
 *
 * @property \App\Model\Table\StaffTable&\Cake\ORM\Association\BelongsTo $Staffs
 *
 * @method \App\Model\Entity\StaffUnavailability newEmptyEntity()
 * @method \App\Model\Entity\StaffUnavailability newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\StaffUnavailability> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\StaffUnavailability get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\StaffUnavailability findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\StaffUnavailability patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\StaffUnavailability> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\StaffUnavailability|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\StaffUnavailability saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\StaffUnavailability>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\StaffUnavailability>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\StaffUnavailability>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\StaffUnavailability> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\StaffUnavailability>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\StaffUnavailability>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\StaffUnavailability>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\StaffUnavailability> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class StaffUnavailabilitiesTable extends Table
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

        $this->setTable('staff_unavailabilities');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Staff', [
            'foreignKey' => 'staff_id',
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
            ->integer('staff_id')
            ->notEmptyString('staff_id');

        $validator
            ->date('date_from')
            ->requirePresence('date_from', 'create')
            ->notEmptyDate('date_from');

        $validator
            ->date('date_to')
            ->requirePresence('date_to', 'create')
            ->notEmptyDate('date_to')
            ->add('date_to', 'custom', [
                'rule' => function ($value, $context) {
                    if (!empty($context['data']['date_from']) && !empty($value)) {
                        return $value >= $context['data']['date_from'];
                    }
                    return true;
                },
                'message' => 'End date must be after or equal to start date'
            ]);

        $validator
            ->scalar('reason')
            ->maxLength('reason', 255)
            ->allowEmptyString('reason');

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
        $rules->add($rules->existsIn(['staff_id'], 'Staff'), ['errorField' => 'staff_id']);

        return $rules;
    }
}
