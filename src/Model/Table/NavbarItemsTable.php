<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Behavior\TreeBehavior;

/**
 * NavbarItems Model
 *
 * @property \App\Model\Table\NavbarItemsTable&\Cake\ORM\Association\BelongsTo $ParentNavbarItems
 * @property \App\Model\Table\NavbarItemsTable&\Cake\ORM\Association\HasMany $ChildNavbarItems
 *
 * @method \App\Model\Entity\NavbarItem newEmptyEntity()
 * @method \App\Model\Entity\NavbarItem newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\NavbarItem> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\NavbarItem get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\NavbarItem findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\NavbarItem patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\NavbarItem> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\NavbarItem|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\NavbarItem saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\NavbarItem>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\NavbarItem>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\NavbarItem>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\NavbarItem> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\NavbarItem>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\NavbarItem>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\NavbarItem>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\NavbarItem> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class NavbarItemsTable extends Table
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

        $this->setTable('navbar_items');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Tree');

        $this->belongsTo('ParentNavbarItems', [
            'className' => 'NavbarItems',
            'foreignKey' => 'parent_id',
        ]);
        $this->hasMany('ChildNavbarItems', [
            'className' => 'NavbarItems',
            'foreignKey' => 'parent_id',
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
            ->integer('parent_id')
            ->allowEmptyString('parent_id');

        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('url')
            ->maxLength('url', 500)
            ->allowEmptyString('url');

        $validator
            ->scalar('target')
            ->maxLength('target', 50)
            ->allowEmptyString('target');

        $validator
            ->scalar('icon')
            ->maxLength('icon', 100)
            ->allowEmptyString('icon');

        $validator
            ->integer('sort_order')
            ->notEmptyString('sort_order');

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
        $rules->add($rules->existsIn(['parent_id'], 'ParentNavbarItems'), ['errorField' => 'parent_id']);

        return $rules;
    }
}
