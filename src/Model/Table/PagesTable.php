<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Pages Model
 *
 * @method \App\Model\Entity\Page newEmptyEntity()
 * @method \App\Model\Entity\Page newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Page> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Page get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Page findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Page patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Page> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Page|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Page saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Page>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Page>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Page>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Page> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Page>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Page>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Page>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Page> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PagesTable extends Table
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

        $this->setTable('pages');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        
        $this->hasMany('PageComponents', [
            'foreignKey' => 'page_id',
            'sort' => ['PageComponents.sort_order' => 'ASC']
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
            ->scalar('title')
            ->maxLength('title', 200)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('slug')
            ->maxLength('slug', 200)
            ->requirePresence('slug', 'create')
            ->notEmptyString('slug')
            ->add('slug', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('content')
            ->requirePresence('content', 'create')
            ->notEmptyString('content');

        $validator
            ->scalar('meta_description')
            ->maxLength('meta_description', 160)
            ->allowEmptyString('meta_description');

        $validator
            ->scalar('template')
            ->maxLength('template', 50)
            ->allowEmptyString('template');

        $validator
            ->boolean('is_published')
            ->notEmptyString('is_published');

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
