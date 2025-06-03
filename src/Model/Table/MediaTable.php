<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Media Model
 *
 * @method \App\Model\Entity\Media newEmptyEntity()
 * @method \App\Model\Entity\Media newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Media> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Media get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Media findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Media patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Media> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Media|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Media saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Media>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Media>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Media>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Media> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Media>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Media>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Media>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Media> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MediaTable extends Table
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

        $this->setTable('media');
        $this->setDisplayField('filename');
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
            ->scalar('filename')
            ->maxLength('filename', 255)
            ->requirePresence('filename', 'create')
            ->notEmptyString('filename');

        $validator
            ->scalar('original_name')
            ->maxLength('original_name', 255)
            ->requirePresence('original_name', 'create')
            ->notEmptyString('original_name');

        $validator
            ->scalar('mime_type')
            ->maxLength('mime_type', 100)
            ->requirePresence('mime_type', 'create')
            ->notEmptyString('mime_type');

        $validator
            ->integer('size')
            ->requirePresence('size', 'create')
            ->notEmptyString('size');

        $validator
            ->scalar('alt_text')
            ->maxLength('alt_text', 255)
            ->allowEmptyString('alt_text');

        return $validator;
    }
}
