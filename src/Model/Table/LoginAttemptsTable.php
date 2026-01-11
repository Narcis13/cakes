<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LoginAttempts Model
 *
 * @method \App\Model\Entity\LoginAttempt newEmptyEntity()
 * @method \App\Model\Entity\LoginAttempt newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\LoginAttempt> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LoginAttempt get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\LoginAttempt findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\LoginAttempt patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\LoginAttempt> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\LoginAttempt|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\LoginAttempt saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\LoginAttempt> saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\LoginAttempt> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\LoginAttempt> deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\LoginAttempt> deleteManyOrFail(iterable $entities, array $options = [])
 */
class LoginAttemptsTable extends Table
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

        $this->setTable('login_attempts');
        $this->setDisplayField('email');
        $this->setPrimaryKey('id');
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
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->scalar('ip_address')
            ->maxLength('ip_address', 45)
            ->requirePresence('ip_address', 'create')
            ->notEmptyString('ip_address');

        $validator
            ->scalar('user_agent')
            ->allowEmptyString('user_agent');

        $validator
            ->boolean('success')
            ->notEmptyString('success');

        $validator
            ->dateTime('attempted_at')
            ->requirePresence('attempted_at', 'create')
            ->notEmptyDateTime('attempted_at');

        return $validator;
    }
}
