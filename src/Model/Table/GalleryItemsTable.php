<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GalleryItems Model
 *
 * @method \App\Model\Entity\GalleryItem newEmptyEntity()
 * @method \App\Model\Entity\GalleryItem newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\GalleryItem> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GalleryItem get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\GalleryItem findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\GalleryItem patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\GalleryItem> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\GalleryItem|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\GalleryItem saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\GalleryItem>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\GalleryItem>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\GalleryItem>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\GalleryItem> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\GalleryItem>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\GalleryItem>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\GalleryItem>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\GalleryItem> deleteManyOrFail(iterable $entities, array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GalleryItemsTable extends Table
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

        $this->setTable('gallery_items');
        $this->setDisplayField('title');
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
            ->scalar('image_url')
            ->maxLength('image_url', 500)
            ->requirePresence('image_url', 'create')
            ->notEmptyString('image_url');

        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->allowEmptyString('title');

        $validator
            ->scalar('alt_text')
            ->maxLength('alt_text', 255)
            ->allowEmptyString('alt_text');

        $validator
            ->integer('sort_order')
            ->notEmptyString('sort_order');

        $validator
            ->boolean('is_active')
            ->notEmptyString('is_active');

        return $validator;
    }

    /**
     * Custom finder for active gallery items in sort order.
     *
     * @param \Cake\ORM\Query\SelectQuery $query The query to modify.
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findActive(SelectQuery $query): SelectQuery
    {
        return $query
            ->where(['is_active' => true])
            ->order(['sort_order' => 'ASC']);
    }
}
