<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Files Model
 *
 * @method \App\Model\Entity\File newEmptyEntity()
 * @method \App\Model\Entity\File newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\File> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\File get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\File findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\File patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\File> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\File|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\File saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\File>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\File>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\File>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\File> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\File>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\File>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\File>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\File> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class FilesTable extends Table
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

        $this->setTable('files');
        $this->setDisplayField('filename');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        
        $this->belongsTo('Users', [
            'foreignKey' => 'uploaded_by'
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
            ->scalar('file_path')
            ->maxLength('file_path', 500)
            ->requirePresence('file_path', 'create')
            ->notEmptyString('file_path');

        $validator
            ->scalar('file_url')
            ->maxLength('file_url', 500)
            ->requirePresence('file_url', 'create')
            ->notEmptyString('file_url');

        $validator
            ->scalar('mime_type')
            ->maxLength('mime_type', 100)
            ->requirePresence('mime_type', 'create')
            ->notEmptyString('mime_type');

        $validator
            ->integer('file_size')
            ->requirePresence('file_size', 'create')
            ->notEmptyString('file_size');

        $validator
            ->scalar('file_type')
            ->maxLength('file_type', 50)
            ->requirePresence('file_type', 'create')
            ->notEmptyString('file_type');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->scalar('category')
            ->maxLength('category', 100)
            ->allowEmptyString('category');

        $validator
            ->boolean('is_public')
            ->notEmptyString('is_public');

        $validator
            ->integer('download_count')
            ->notEmptyString('download_count');

        $validator
            ->integer('uploaded_by')
            ->allowEmptyString('uploaded_by');

        return $validator;
    }
}
