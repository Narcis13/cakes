<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * NewsCategories Model
 *
 * @property \App\Model\Table\NewsTable&\Cake\ORM\Association\HasMany $News
 * @method \App\Model\Entity\NewsCategory newEmptyEntity()
 * @method \App\Model\Entity\NewsCategory newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\NewsCategory> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\NewsCategory get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\NewsCategory findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\NewsCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\NewsCategory> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\NewsCategory|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\NewsCategory saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\NewsCategory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\NewsCategory>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\NewsCategory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\NewsCategory> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\NewsCategory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\NewsCategory>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\NewsCategory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\NewsCategory> deleteManyOrFail(iterable $entities, array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class NewsCategoriesTable extends Table
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

        $this->setTable('news_categories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('News', [
            'foreignKey' => 'category_id',
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
            ->notEmptyString('name');

        $validator
            ->scalar('slug')
            ->maxLength('slug', 100)
            ->requirePresence('slug', 'create')
            ->notEmptyString('slug')
            ->add('slug', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

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
        $rules->add($rules->isUnique(['slug']), ['errorField' => 'slug']);

        return $rules;
    }
}
