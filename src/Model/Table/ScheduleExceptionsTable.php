<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\I18n\Date;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use InvalidArgumentException;

/**
 * ScheduleExceptions Model
 *
 * @property \App\Model\Table\StaffTable&\Cake\ORM\Association\BelongsTo $Staff
 * @method \App\Model\Entity\ScheduleException newEmptyEntity()
 * @method \App\Model\Entity\ScheduleException newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\ScheduleException> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ScheduleException get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\ScheduleException findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\ScheduleException patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\ScheduleException> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ScheduleException|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\ScheduleException saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\ScheduleException>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ScheduleException>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ScheduleException>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ScheduleException> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ScheduleException>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ScheduleException>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ScheduleException>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ScheduleException> deleteManyOrFail(iterable $entities, array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ScheduleExceptionsTable extends Table
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

        $this->setTable('schedule_exceptions');
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
            ->requirePresence('staff_id', 'create')
            ->notEmptyString('staff_id');

        $validator
            ->date('exception_date')
            ->requirePresence('exception_date', 'create')
            ->notEmptyDate('exception_date')
            ->add('exception_date', 'future', [
                'rule' => function ($value) {
                    return $value >= Date::now();
                },
                'message' => __('Exception date must be today or in the future'),
            ]);

        $validator
            ->boolean('is_working')
            ->notEmptyString('is_working', null, 'create');

        $validator
            ->time('start_time')
            ->allowEmptyTime('start_time')
            ->add('start_time', 'requiredIfWorking', [
                'rule' => function ($value, $context) {
                    if (!empty($context['data']['is_working']) && $context['data']['is_working']) {
                        return !empty($value);
                    }

                    return true;
                },
                'message' => __('Start time is required when working'),
            ]);

        $validator
            ->time('end_time')
            ->allowEmptyTime('end_time')
            ->add('end_time', 'requiredIfWorking', [
                'rule' => function ($value, $context) {
                    if (!empty($context['data']['is_working']) && $context['data']['is_working']) {
                        return !empty($value);
                    }

                    return true;
                },
                'message' => __('End time is required when working'),
            ])
            ->add('end_time', 'validTimeRange', [
                'rule' => function ($value, $context) {
                    if (!isset($context['data']['start_time']) || empty($value)) {
                        return true;
                    }

                    return strtotime($value) > strtotime($context['data']['start_time']);
                },
                'message' => __('End time must be after start time'),
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
        $rules->add($rules->existsIn('staff_id', 'Staff'), ['errorField' => 'staff_id']);

        return $rules;
    }

    /**
     * Find exceptions for a specific date range
     *
     * @param \Cake\ORM\Query\SelectQuery $query The query
     * @param array $options Options with 'start_date' and 'end_date' keys
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findInDateRange(SelectQuery $query, array $options): SelectQuery
    {
        $conditions = [];

        if (!empty($options['start_date'])) {
            $conditions['exception_date >='] = $options['start_date'];
        }

        if (!empty($options['end_date'])) {
            $conditions['exception_date <='] = $options['end_date'];
        }

        return $query->where($conditions);
    }

    /**
     * Find exceptions for a specific staff member
     *
     * @param \Cake\ORM\Query\SelectQuery $query The query
     * @param array $options Options with 'staff_id' key
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findByStaff(SelectQuery $query, array $options): SelectQuery
    {
        if (empty($options['staff_id'])) {
            throw new InvalidArgumentException('staff_id is required');
        }

        return $query
            ->where(['ScheduleExceptions.staff_id' => $options['staff_id']])
            ->orderBy(['ScheduleExceptions.exception_date' => 'ASC']);
    }

    /**
     * Check if a staff member has an exception on a specific date
     *
     * @param int $staffId Staff ID
     * @param \Cake\I18n\Date $date Date to check
     * @return \App\Model\Entity\ScheduleException|null
     */
    public function getException(int $staffId, Date $date): ?object
    {
        return $this->find()
            ->where([
                'staff_id' => $staffId,
                'exception_date' => $date->format('Y-m-d'),
            ])
            ->first();
    }
}
