<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PageComponents Model
 *
 * @property \App\Model\Table\PagesTable&\Cake\ORM\Association\BelongsTo $Pages
 *
 * @method \App\Model\Entity\PageComponent newEmptyEntity()
 * @method \App\Model\Entity\PageComponent newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\PageComponent> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PageComponent get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\PageComponent findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\PageComponent patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\PageComponent> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\PageComponent|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\PageComponent saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\PageComponent>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PageComponent>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PageComponent>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PageComponent> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PageComponent>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PageComponent>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PageComponent>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PageComponent> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PageComponentsTable extends Table
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

        $this->setTable('page_components');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Pages', [
            'foreignKey' => 'page_id',
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
            ->integer('page_id')
            ->notEmptyString('page_id');

        $validator
            ->scalar('type')
            ->maxLength('type', 50)
            ->requirePresence('type', 'create')
            ->notEmptyString('type')
            ->add('type', 'inList', [
                'rule' => ['inList', ['html', 'image', 'link']],
                'message' => 'Type must be html, image, or link'
            ]);

        $validator
            ->scalar('content')
            ->allowEmptyString('content');

        $validator
            ->scalar('title')
            ->maxLength('title', 200)
            ->allowEmptyString('title');

        $validator
            ->scalar('url')
            ->maxLength('url', 500)
            ->allowEmptyString('url');

        $validator
            ->scalar('alt_text')
            ->maxLength('alt_text', 200)
            ->allowEmptyString('alt_text');

        $validator
            ->scalar('css_class')
            ->maxLength('css_class', 100)
            ->allowEmptyString('css_class');

        $validator
            ->scalar('image_type')
            ->maxLength('image_type', 20)
            ->allowEmptyString('image_type')
            ->add('image_type', 'inList', [
                'rule' => ['inList', ['url', 'upload']],
                'message' => 'Image type must be url or upload'
            ]);

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
        $rules->add($rules->existsIn(['page_id'], 'Pages'), ['errorField' => 'page_id']);

        return $rules;
    }
}
