<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SiteSettings Model
 *
 * @method \App\Model\Entity\SiteSetting newEmptyEntity()
 * @method \App\Model\Entity\SiteSetting newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\SiteSetting> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SiteSetting get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\SiteSetting findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\SiteSetting patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\SiteSetting> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\SiteSetting|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\SiteSetting saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\SiteSetting>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\SiteSetting>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\SiteSetting>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\SiteSetting> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\SiteSetting>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\SiteSetting>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\SiteSetting>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\SiteSetting> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SiteSettingsTable extends Table
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

        $this->setTable('site_settings');
        $this->setDisplayField('key_name');
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
            ->scalar('key_name')
            ->maxLength('key_name', 255)
            ->requirePresence('key_name', 'create')
            ->notEmptyString('key_name')
            ->add('key_name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('value')
            ->requirePresence('value', 'create')
            ->notEmptyString('value');

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->requirePresence('description', 'create')
            ->notEmptyString('description');

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
        $rules->add($rules->isUnique(['key_name']), ['errorField' => 'key_name']);

        return $rules;
    }

    /**
     * Get setting value by key
     *
     * @param string $key The setting key
     * @param mixed $default Default value if not found
     * @return mixed
     */
    public function getValue(string $key, $default = null)
    {
        $setting = $this->find()->where(['key_name' => $key])->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set setting value by key
     *
     * @param string $key The setting key
     * @param string $value The setting value
     * @param string|null $description Optional description
     * @return bool
     */
    public function setValue(string $key, string $value, ?string $description = null): bool
    {
        $setting = $this->find()->where(['key_name' => $key])->first();
        
        if (!$setting) {
            $setting = $this->newEntity([
                'key_name' => $key,
                'value' => $value,
                'description' => $description
            ]);
        } else {
            $setting = $this->patchEntity($setting, ['value' => $value]);
            if ($description !== null) {
                $setting->description = $description;
            }
        }

        return (bool) $this->save($setting);
    }
}
