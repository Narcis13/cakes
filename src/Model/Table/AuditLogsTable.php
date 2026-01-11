<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\AuditLog;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AuditLogs Model
 *
 * Provides an audit trail for admin actions on sensitive data.
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @method \App\Model\Entity\AuditLog newEmptyEntity()
 * @method \App\Model\Entity\AuditLog newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\AuditLog> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AuditLog get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\AuditLog findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\AuditLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\AuditLog> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\AuditLog|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\AuditLog saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\AuditLog> saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\AuditLog> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\AuditLog> deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\AuditLog> deleteManyOrFail(iterable $entities, array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AuditLogsTable extends Table
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

        $this->setTable('audit_logs');
        $this->setDisplayField('action');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created' => 'new',
                ],
            ],
        ]);

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
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
            ->integer('user_id')
            ->allowEmptyString('user_id');

        $validator
            ->scalar('action')
            ->maxLength('action', 50)
            ->requirePresence('action', 'create')
            ->notEmptyString('action')
            ->inList('action', ['create', 'update', 'delete'], 'Invalid action type');

        $validator
            ->scalar('model')
            ->maxLength('model', 100)
            ->requirePresence('model', 'create')
            ->notEmptyString('model');

        $validator
            ->integer('record_id')
            ->allowEmptyString('record_id');

        $validator
            ->scalar('old_values')
            ->allowEmptyString('old_values');

        $validator
            ->scalar('new_values')
            ->allowEmptyString('new_values');

        $validator
            ->scalar('ip_address')
            ->maxLength('ip_address', 45)
            ->allowEmptyString('ip_address');

        $validator
            ->scalar('user_agent')
            ->allowEmptyString('user_agent');

        return $validator;
    }

    /**
     * Log an audit entry
     *
     * @param string $action The action (create, update, delete)
     * @param string $model The model name
     * @param int|null $recordId The record ID
     * @param array<string, mixed>|null $oldValues Previous values
     * @param array<string, mixed>|null $newValues New values
     * @param int|null $userId The user performing the action
     * @param string|null $ipAddress Client IP address
     * @param string|null $userAgent Client user agent
     * @return \App\Model\Entity\AuditLog|false
     */
    public function log(
        string $action,
        string $model,
        ?int $recordId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?int $userId = null,
        ?string $ipAddress = null,
        ?string $userAgent = null,
    ): AuditLog|false {
        $entry = $this->newEntity([
            'action' => $action,
            'model' => $model,
            'record_id' => $recordId,
            'old_values' => $oldValues !== null ? json_encode($oldValues) : null,
            'new_values' => $newValues !== null ? json_encode($newValues) : null,
            'user_id' => $userId,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);

        return $this->save($entry);
    }

    /**
     * Find logs for a specific model and record
     *
     * @param string $model Model name
     * @param int $recordId Record ID
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findForRecord(string $model, int $recordId): SelectQuery
    {
        return $this->find()
            ->where([
                'model' => $model,
                'record_id' => $recordId,
            ])
            ->orderBy(['created' => 'DESC'])
            ->contain(['Users']);
    }

    /**
     * Find logs by user
     *
     * @param int $userId User ID
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findByUser(int $userId): SelectQuery
    {
        return $this->find()
            ->where(['user_id' => $userId])
            ->orderBy(['created' => 'DESC']);
    }

    /**
     * Find recent logs
     *
     * @param int $limit Number of logs to retrieve
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findRecent(int $limit = 50): SelectQuery
    {
        return $this->find()
            ->orderBy(['created' => 'DESC'])
            ->limit($limit)
            ->contain(['Users']);
    }
}
