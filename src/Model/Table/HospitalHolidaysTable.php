<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * HospitalHolidays Model
 *
 * @method \App\Model\Entity\HospitalHoliday newEmptyEntity()
 * @method \App\Model\Entity\HospitalHoliday newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\HospitalHoliday> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\HospitalHoliday get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\HospitalHoliday findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\HospitalHoliday patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\HospitalHoliday> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\HospitalHoliday|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\HospitalHoliday saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\HospitalHoliday>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\HospitalHoliday>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\HospitalHoliday>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\HospitalHoliday> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\HospitalHoliday>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\HospitalHoliday>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\HospitalHoliday>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\HospitalHoliday> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class HospitalHolidaysTable extends Table
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

        $this->setTable('hospital_holidays');
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
            ->notEmptyString('name');

        $validator
            ->date('date')
            ->requirePresence('date', 'create')
            ->notEmptyDate('date');

        $validator
            ->boolean('is_recurring')
            ->notEmptyString('is_recurring');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

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
        $rules->add($rules->isUnique(['date']), ['errorField' => 'date']);

        return $rules;
    }
    
    /**
     * Find upcoming holidays
     *
     * @param \Cake\ORM\Query\SelectQuery $query
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findUpcoming(SelectQuery $query): SelectQuery
    {
        return $query
            ->where(['date >=' => date('Y-m-d')])
            ->order(['date' => 'ASC']);
    }
    
    /**
     * Get holidays for a specific year
     *
     * @param int $year
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findByYear(int $year): SelectQuery
    {
        return $this->find()
            ->where([
                'YEAR(date)' => $year
            ])
            ->order(['date' => 'ASC']);
    }
    
    /**
     * Check if a date is a holiday
     *
     * @param string $date Date in Y-m-d format
     * @return bool
     */
    public function isHoliday(string $date): bool
    {
        $count = $this->find()
            ->where(['date' => $date])
            ->count();
            
        // Check recurring holidays (same month and day for any year)
        if ($count === 0) {
            $dateObj = new \DateTime($date);
            $month = $dateObj->format('m');
            $day = $dateObj->format('d');
            
            $count = $this->find()
                ->where([
                    'is_recurring' => true,
                    'MONTH(date)' => $month,
                    'DAY(date)' => $day
                ])
                ->count();
        }
        
        return $count > 0;
    }
}
