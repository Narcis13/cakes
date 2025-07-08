<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Text;

/**
 * News Model
 *
 * @property \App\Model\Table\StaffTable&\Cake\ORM\Association\BelongsTo $Authors
 * @property \App\Model\Table\NewsCategoriesTable&\Cake\ORM\Association\BelongsTo $Categories
 *
 * @method \App\Model\Entity\News newEmptyEntity()
 * @method \App\Model\Entity\News newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\News> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\News get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\News findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\News patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\News> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\News|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\News saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\News>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\News>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\News>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\News> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\News>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\News>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\News>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\News> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class NewsTable extends Table
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

        $this->setTable('news');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Staff', ['foreignKey' => 'author_id']);
        $this->belongsTo('NewsCategories', ['foreignKey' => 'category_id']);
    }

    public function beforeSave($event, $entity, $options)
    {
        if ($entity->isNew() && empty($entity->slug)) {
            $entity->slug = Text::slug(strtolower($entity->title));
        }
        return true;
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
            ->allowEmptyString('slug')
            ->add('slug', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('content')
            ->requirePresence('content', 'create')
            ->notEmptyString('content');

        $validator
            ->scalar('excerpt')
            ->allowEmptyString('excerpt');

        $validator
            ->integer('author_id')
            ->allowEmptyString('author_id');

        $validator
            ->integer('category_id')
            ->allowEmptyString('category_id');

        $validator
            ->scalar('featured_image')
            ->maxLength('featured_image', 255)
            ->allowEmptyFile('featured_image');

        $validator
            ->boolean('is_published')
            ->notEmptyString('is_published');

        $validator
            ->dateTime('publish_date')
            ->allowEmptyDateTime('publish_date');

        $validator
            ->integer('views_count')
            ->notEmptyString('views_count');

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
        $rules->add($rules->existsIn(['author_id'], 'Staff'), ['errorField' => 'author_id']);
        $rules->add($rules->existsIn(['category_id'], 'NewsCategories'), ['errorField' => 'category_id']);

        return $rules;
    }
}
