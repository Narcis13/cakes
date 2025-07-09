<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\I18n\Date;
use Cake\I18n\Time;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Security;

/**
 * Appointments Model
 *
 * @property \App\Model\Table\ServicesTable&\Cake\ORM\Association\BelongsTo $Services
 * @property \App\Model\Table\StaffTable&\Cake\ORM\Association\BelongsTo $Doctors
 * @method \App\Model\Entity\Appointment newEmptyEntity()
 * @method \App\Model\Entity\Appointment newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Appointment> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Appointment get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Appointment findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Appointment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Appointment> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Appointment|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Appointment saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Appointment>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Appointment>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Appointment>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Appointment> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Appointment>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Appointment>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Appointment>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Appointment> deleteManyOrFail(iterable $entities, array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AppointmentsTable extends Table
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

        $this->setTable('appointments');
        $this->setDisplayField('patient_name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Services', [
            'foreignKey' => 'service_id',
        ]);
        $this->belongsTo('Doctors', [
            'foreignKey' => 'doctor_id',
            'className' => 'Staff',
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
            ->scalar('patient_name')
            ->maxLength('patient_name', 100)
            ->requirePresence('patient_name', 'create')
            ->notEmptyString('patient_name');

        $validator
            ->scalar('patient_phone')
            ->maxLength('patient_phone', 20)
            ->requirePresence('patient_phone', 'create')
            ->notEmptyString('patient_phone');

        $validator
            ->email('patient_email')
            ->maxLength('patient_email', 100)
            ->requirePresence('patient_email', 'create')
            ->notEmptyString('patient_email');

        $validator
            ->integer('service_id')
            ->requirePresence('service_id', 'create')
            ->notEmptyString('service_id');

        $validator
            ->integer('doctor_id')
            ->requirePresence('doctor_id', 'create')
            ->notEmptyString('doctor_id');

        $validator
            ->date('appointment_date')
            ->requirePresence('appointment_date', 'create')
            ->notEmptyDate('appointment_date')
            ->add('appointment_date', 'futureDate', [
                'rule' => function ($value) {
                    return $value >= Date::now();
                },
                'message' => __('Appointment date must be today or in the future')
            ]);

        $validator
            ->time('appointment_time')
            ->requirePresence('appointment_time', 'create')
            ->notEmptyTime('appointment_time');

        $validator
            ->time('end_time')
            ->requirePresence('end_time', 'create')
            ->notEmptyTime('end_time');

        $validator
            ->scalar('status')
            ->maxLength('status', 20)
            ->notEmptyString('status');

        $validator
            ->scalar('notes')
            ->allowEmptyString('notes');

        $validator
            ->scalar('patient_phone')
            ->add('patient_phone', 'validPhone', [
                'rule' => ['custom', '/^[\\d\\s\\-\\+\\(\\)]+$/'],
                'message' => __('Please enter a valid phone number')
            ]);

        $validator
            ->scalar('confirmation_token')
            ->maxLength('confirmation_token', 64)
            ->allowEmptyString('confirmation_token');

        $validator
            ->dateTime('confirmed_at')
            ->allowEmptyDateTime('confirmed_at');

        $validator
            ->dateTime('cancelled_at')
            ->allowEmptyDateTime('cancelled_at');

        $validator
            ->scalar('cancellation_reason')
            ->maxLength('cancellation_reason', 255)
            ->allowEmptyString('cancellation_reason');

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
        $rules->add($rules->existsIn(['service_id'], 'Services'), ['errorField' => 'service_id']);
        $rules->add($rules->existsIn(['doctor_id'], 'Doctors'), ['errorField' => 'doctor_id']);

        return $rules;
    }
    
    /**
     * Custom validation: Check if date is not a weekend
     *
     * @param mixed $value The date value
     * @param array $context The validation context
     * @return bool
     */
    public function isNotWeekend($value, array $context): bool
    {
        if (empty($value)) {
            return true;
        }
        
        $date = new \DateTime($value);
        $dayOfWeek = (int)$date->format('N'); // 1 = Monday, 7 = Sunday
        return $dayOfWeek < 6; // Returns true if Monday-Friday
    }
    
    /**
     * Custom validation: Check if date is not a hospital holiday
     *
     * @param mixed $value The date value
     * @param array $context The validation context
     * @return bool
     */
    public function isNotHoliday($value, array $context): bool
    {
        if (empty($value)) {
            return true;
        }
        
        $date = new \DateTime($value);
        $dateString = $date->format('Y-m-d');
        
        // Check if date is a holiday using HospitalHolidaysTable
        $HospitalHolidays = $this->getTableLocator()->get('HospitalHolidays');
        return !$HospitalHolidays->isHoliday($dateString);
    }
    
    /**
     * Custom validation: Check if date is in the future
     *
     * @param mixed $value The date value
     * @param array $context The validation context
     * @return bool
     */
    public function isFutureDate($value, array $context): bool
    {
        if (empty($value)) {
            return true;
        }
        
        $date = new \DateTime($value);
        $today = new \DateTime('today');
        return $date > $today;
    }
    
    /**
     * Check if a doctor is available on a specific date
     *
     * @param int $doctorId The doctor ID
     * @param string $date The date in Y-m-d format
     * @return bool
     */
    public function isDoctorAvailable(int $doctorId, string $date): bool
    {
        // Check staff unavailabilities
        $StaffUnavailabilities = $this->getTableLocator()->get('StaffUnavailabilities');
        $unavailable = $StaffUnavailabilities->find()
            ->where([
                'staff_id' => $doctorId,
                'date_from <=' => $date,
                'date_to >=' => $date
            ])
            ->count();
            
        return $unavailable === 0;
    }

    /**
     * Generate a confirmation token for an appointment
     *
     * @return string
     */
    public function generateConfirmationToken(): string
    {
        return Security::randomString(64);
    }

    /**
     * Find appointments by confirmation token
     *
     * @param string $token Confirmation token
     * @return \App\Model\Entity\Appointment|null
     */
    public function findByToken(string $token): ?object
    {
        return $this->find()
            ->where(['confirmation_token' => $token])
            ->first();
    }

    /**
     * Get appointments for a doctor on a specific date
     *
     * @param int $doctorId Doctor ID
     * @param \Cake\I18n\Date $date Date
     * @return \Cake\ORM\Query
     */
    public function findByDoctorAndDate(int $doctorId, Date $date)
    {
        return $this->find()
            ->where([
                'doctor_id' => $doctorId,
                'appointment_date' => $date->format('Y-m-d'),
                'status NOT IN' => ['cancelled', 'no-show']
            ])
            ->orderBy(['appointment_time' => 'ASC']);
    }

    /**
     * Check if a time slot is available for a doctor
     *
     * @param int $doctorId Doctor ID
     * @param \Cake\I18n\Date $date Date
     * @param \Cake\I18n\Time $startTime Start time
     * @param \Cake\I18n\Time $endTime End time
     * @return bool
     */
    public function isTimeSlotAvailable(int $doctorId, Date $date, Time $startTime, Time $endTime): bool
    {
        $conflicts = $this->find()
            ->where([
                'doctor_id' => $doctorId,
                'appointment_date' => $date->format('Y-m-d'),
                'status NOT IN' => ['cancelled', 'no-show'],
                'OR' => [
                    // New appointment starts during existing appointment
                    [
                        'appointment_time <=' => $startTime->format('H:i:s'),
                        'end_time >' => $startTime->format('H:i:s')
                    ],
                    // New appointment ends during existing appointment
                    [
                        'appointment_time <' => $endTime->format('H:i:s'),
                        'end_time >=' => $endTime->format('H:i:s')
                    ],
                    // New appointment completely overlaps existing appointment
                    [
                        'appointment_time >=' => $startTime->format('H:i:s'),
                        'end_time <=' => $endTime->format('H:i:s')
                    ]
                ]
            ])
            ->count();

        return $conflicts === 0;
    }
}
