<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ContactMessages Model
 *
 * @method \App\Model\Entity\ContactMessage newEmptyEntity()
 * @method \App\Model\Entity\ContactMessage newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\ContactMessage> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ContactMessage get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\ContactMessage findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\ContactMessage patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\ContactMessage> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ContactMessage|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\ContactMessage saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\ContactMessage>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ContactMessage>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ContactMessage>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ContactMessage> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ContactMessage>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ContactMessage>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ContactMessage>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ContactMessage> deleteManyOrFail(iterable $entities, array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ContactMessagesTable extends Table
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

        $this->setTable('contact_messages');
        $this->setDisplayField('nume_prenume');
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
            ->scalar('nume_prenume')
            ->maxLength('nume_prenume', 255)
            ->requirePresence('nume_prenume', 'create')
            ->notEmptyString('nume_prenume');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->scalar('mesaj')
            ->requirePresence('mesaj', 'create')
            ->notEmptyString('mesaj');

        return $validator;
    }
}
