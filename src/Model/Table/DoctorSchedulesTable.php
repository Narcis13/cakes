<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use InvalidArgumentException;

/**
 * DoctorSchedules Model
 *
 * @property \App\Model\Table\StaffTable&\Cake\ORM\Association\BelongsTo $Staff
 * @property \App\Model\Table\ServicesTable&\Cake\ORM\Association\BelongsTo $Services
 * @method \App\Model\Entity\DoctorSchedule newEmptyEntity()
 * @method \App\Model\Entity\DoctorSchedule newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\DoctorSchedule> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DoctorSchedule get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\DoctorSchedule findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\DoctorSchedule patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\DoctorSchedule> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\DoctorSchedule|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\DoctorSchedule saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\DoctorSchedule>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\DoctorSchedule>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\DoctorSchedule>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\DoctorSchedule> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\DoctorSchedule>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\DoctorSchedule>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\DoctorSchedule>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\DoctorSchedule> deleteManyOrFail(iterable $entities, array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DoctorSchedulesTable extends Table
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

        $this->setTable('doctor_schedules');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Staff', [
            'foreignKey' => 'staff_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Services', [
            'foreignKey' => 'service_id',
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
            ->integer('day_of_week')
            ->requirePresence('day_of_week', 'create')
            ->notEmptyString('day_of_week')
            ->range('day_of_week', [1, 7], __('Day of week must be between 1 (Monday) and 7 (Sunday)'));

        $validator
            ->time('start_time')
            ->requirePresence('start_time', 'create')
            ->notEmptyTime('start_time')
            ->add('start_time', 'reasonableTime', [
                'rule' => function ($value) {
                    $hour = (int)date('H', strtotime($value));
                    // Working hours should be between 5 AM and 10 PM
                    return $hour >= 5 && $hour <= 22;
                },
                'message' => __('Start time must be between 5:00 AM and 10:00 PM'),
            ]);

        $validator
            ->time('end_time')
            ->requirePresence('end_time', 'create')
            ->notEmptyTime('end_time')
            ->add('end_time', 'validTimeRange', [
                'rule' => function ($value, $context) {
                    if (!isset($context['data']['start_time'])) {
                        return true;
                    }

                    return strtotime($value) > strtotime($context['data']['start_time']);
                },
                'message' => __('End time must be after start time'),
            ])
            ->add('end_time', 'reasonableHours', [
                'rule' => function ($value, $context) {
                    if (!isset($context['data']['start_time'])) {
                        return true;
                    }
                    $startTime = strtotime($context['data']['start_time']);
                    $endTime = strtotime($value);
                    $duration = ($endTime - $startTime) / 3600; // Convert to hours

                    // Check if working hours are between 15 minutes and 12 hours
                    return $duration >= 0.25 && $duration <= 12;
                },
                'message' => __('Working hours must be between 15 minutes and 12 hours'),
            ]);

        $validator
            ->integer('service_id')
            ->requirePresence('service_id', 'create')
            ->notEmptyString('service_id');

        $validator
            ->integer('max_appointments')
            ->notEmptyString('max_appointments', null, 'create')
            ->greaterThan('max_appointments', 0, __('Maximum appointments must be greater than 0'));

        $validator
            ->integer('slot_duration')
            ->allowEmptyString('slot_duration')
            ->greaterThan('slot_duration', 0, __('Slot duration must be greater than 0 minutes'))
            ->add('slot_duration', 'minimumDuration', [
                'rule' => function ($value) {
                    if ($value === null || $value === '') {
                        return true; // Allow empty, will use service default
                    }

                    return $value >= 5; // Minimum 5 minutes
                },
                'message' => __('Slot duration must be at least 5 minutes'),
            ]);

        $validator
            ->integer('buffer_minutes')
            ->notEmptyString('buffer_minutes', null, 'create')
            ->greaterThanOrEqual('buffer_minutes', 0, __('Buffer minutes cannot be negative'));

        $validator
            ->boolean('is_active')
            ->notEmptyString('is_active', null, 'create');

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
        $rules->add($rules->existsIn('service_id', 'Services'), ['errorField' => 'service_id']);

        // Prevent overlapping schedules
        $rules->add(function ($entity, $options) {
            $conditions = [
                'staff_id' => $entity->staff_id,
                'day_of_week' => $entity->day_of_week,
                'is_active' => true,
                'OR' => [
                    // New schedule starts during existing schedule
                    [
                        'start_time <=' => $entity->start_time,
                        'end_time >' => $entity->start_time,
                    ],
                    // New schedule ends during existing schedule
                    [
                        'start_time <' => $entity->end_time,
                        'end_time >=' => $entity->end_time,
                    ],
                    // New schedule completely overlaps existing schedule
                    [
                        'start_time >=' => $entity->start_time,
                        'end_time <=' => $entity->end_time,
                    ],
                ],
            ];

            if (!$entity->isNew()) {
                $conditions['id !='] = $entity->id;
            }

            return !$this->exists($conditions);
        }, 'noOverlap', [
            'errorField' => 'start_time',
            'message' => __('This time slot overlaps with an existing schedule'),
        ]);

        // Check service duration fits within time slot
        $rules->add(function ($entity, $options) {
            if (!$entity->has('service_id') || !$entity->has('start_time') || !$entity->has('end_time')) {
                return true;
            }

            // Get service duration
            $service = $this->Services->get($entity->service_id);
            $serviceDuration = $entity->slot_duration ?? $service->duration_minutes ?? 30;

            // Calculate time slot duration in minutes
            $startTime = strtotime($entity->start_time);
            $endTime = strtotime($entity->end_time);
            $slotDuration = ($endTime - $startTime) / 60;

            // Check if at least one appointment can fit
            // Consider buffer time in validation if needed

            return $slotDuration >= $serviceDuration;
        }, 'serviceFits', [
            'errorField' => 'end_time',
            'message' => __('The time slot is too short for the selected service duration'),
        ]);

        // Ensure doctor can only have one schedule per service per time slot
        $rules->add(function ($entity, $options) {
            $conditions = [
                'staff_id' => $entity->staff_id,
                'service_id' => $entity->service_id,
                'day_of_week' => $entity->day_of_week,
                'start_time' => $entity->start_time,
                'end_time' => $entity->end_time,
                'is_active' => true,
            ];

            if (!$entity->isNew()) {
                $conditions['id !='] = $entity->id;
            }

            return !$this->exists($conditions);
        }, 'uniqueServiceSchedule', [
            'errorField' => 'service_id',
            'message' => __('A schedule already exists for this doctor, service, and time slot'),
        ]);

        return $rules;
    }

    /**
     * Find schedules for a specific staff member
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
            ->where(['DoctorSchedules.staff_id' => $options['staff_id']])
            ->contain(['Services'])
            ->orderBy(['DoctorSchedules.day_of_week' => 'ASC', 'DoctorSchedules.start_time' => 'ASC']);
    }

    /**
     * Find active schedules for a specific day of week
     *
     * @param \Cake\ORM\Query\SelectQuery $query The query
     * @param array $options Options with 'day_of_week' key
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findActiveByDay(SelectQuery $query, array $options): SelectQuery
    {
        if (empty($options['day_of_week'])) {
            throw new InvalidArgumentException('day_of_week is required');
        }

        return $query
            ->where([
                'DoctorSchedules.day_of_week' => $options['day_of_week'],
                'DoctorSchedules.is_active' => true,
            ])
            ->contain(['Staff', 'Services']);
    }
}
