<?php
declare(strict_types=1);

namespace App\Model\Behavior;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\Http\ServerRequest;
use Cake\ORM\Behavior;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use DateTimeInterface;

/**
 * Auditable Behavior
 *
 * Automatically logs create, update, and delete operations on attached models.
 * Creates audit trail entries in the audit_logs table.
 *
 * Usage:
 * ```php
 * // In your Table class initialize():
 * $this->addBehavior('Auditable', [
 *     'excludeFields' => ['password', 'modified'], // Fields to exclude from logging
 *     'includeFields' => null, // If set, only these fields are logged
 * ]);
 * ```
 */
class AuditableBehavior extends Behavior
{
    /**
     * Default configuration
     *
     * @var array<string, mixed>
     */
    protected array $_defaultConfig = [
        'excludeFields' => ['password', 'modified', 'created'],
        'includeFields' => null,
    ];

    /**
     * Original entity values before save (for update tracking)
     *
     * @var array<string, mixed>|null
     */
    protected ?array $_originalValues = null;

    /**
     * Request object for getting IP and user agent
     *
     * @var \Cake\Http\ServerRequest|null
     */
    protected ?ServerRequest $_request = null;

    /**
     * Current user ID
     *
     * @var int|null
     */
    protected ?int $_userId = null;

    /**
     * Set the request object for IP and user agent tracking
     *
     * @param \Cake\Http\ServerRequest $request Request object
     * @return void
     */
    public function setRequest(ServerRequest $request): void
    {
        $this->_request = $request;
    }

    /**
     * Set the current user ID
     *
     * @param int|null $userId User ID
     * @return void
     */
    public function setUserId(?int $userId): void
    {
        $this->_userId = $userId;
    }

    /**
     * Before save callback - capture original values for updates
     *
     * @param \Cake\Event\EventInterface $event Event
     * @param \Cake\Datasource\EntityInterface $entity Entity
     * @param \ArrayObject $options Options
     * @return void
     */
    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        if (!$entity->isNew()) {
            // Store original values before update
            $this->_originalValues = $this->_getFilteredValues($entity->getOriginal());
        }
    }

    /**
     * After save callback - log create or update
     *
     * @param \Cake\Event\EventInterface $event Event
     * @param \Cake\Datasource\EntityInterface $entity Entity
     * @param \ArrayObject $options Options
     * @return void
     */
    public function afterSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        $table = $event->getSubject();
        if (!$table instanceof Table) {
            return;
        }

        $modelName = $table->getAlias();
        $recordId = $entity->get('id');

        if ($entity->isNew()) {
            // Create action
            $newValues = $this->_getFilteredValues($entity->toArray());
            $this->_logAudit('create', $modelName, $recordId, null, $newValues);
        } else {
            // Update action - only log if there are actual changes
            $newValues = $this->_getFilteredValues($entity->toArray());
            $dirtyFields = $entity->getDirty();
            $filteredDirtyFields = $this->_filterFields($dirtyFields);

            if (!empty($filteredDirtyFields)) {
                // Only include changed fields in the log
                $changedOldValues = [];
                $changedNewValues = [];

                foreach ($filteredDirtyFields as $field) {
                    if (isset($this->_originalValues[$field])) {
                        $changedOldValues[$field] = $this->_originalValues[$field];
                    }
                    if (isset($newValues[$field])) {
                        $changedNewValues[$field] = $newValues[$field];
                    }
                }

                if (!empty($changedOldValues) || !empty($changedNewValues)) {
                    $this->_logAudit('update', $modelName, $recordId, $changedOldValues, $changedNewValues);
                }
            }
        }

        // Clear stored values
        $this->_originalValues = null;
    }

    /**
     * After delete callback - log delete
     *
     * @param \Cake\Event\EventInterface $event Event
     * @param \Cake\Datasource\EntityInterface $entity Entity
     * @param \ArrayObject $options Options
     * @return void
     */
    public function afterDelete(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        $table = $event->getSubject();
        if (!$table instanceof Table) {
            return;
        }

        $modelName = $table->getAlias();
        $recordId = $entity->get('id');
        $oldValues = $this->_getFilteredValues($entity->toArray());

        $this->_logAudit('delete', $modelName, $recordId, $oldValues, null);
    }

    /**
     * Filter entity values based on configuration
     *
     * @param array<string, mixed> $values Entity values
     * @return array<string, mixed>
     */
    protected function _getFilteredValues(array $values): array
    {
        $excludeFields = $this->getConfig('excludeFields', []);
        $includeFields = $this->getConfig('includeFields');

        $filtered = [];
        foreach ($values as $field => $value) {
            // Skip associations (arrays of entities)
            if (is_array($value) || $value instanceof EntityInterface) {
                continue;
            }

            // Apply include/exclude logic
            if ($includeFields !== null && !in_array($field, $includeFields, true)) {
                continue;
            }

            if (in_array($field, $excludeFields, true)) {
                continue;
            }

            // Convert DateTime objects to strings for JSON serialization
            if ($value instanceof DateTimeInterface) {
                $value = $value->format('Y-m-d H:i:s');
            }

            $filtered[$field] = $value;
        }

        return $filtered;
    }

    /**
     * Filter field names based on configuration
     *
     * @param list<string> $fields Field names
     * @return list<string>
     */
    protected function _filterFields(array $fields): array
    {
        $excludeFields = $this->getConfig('excludeFields', []);
        $includeFields = $this->getConfig('includeFields');

        $filtered = [];
        foreach ($fields as $field) {
            if ($includeFields !== null && !in_array($field, $includeFields, true)) {
                continue;
            }

            if (in_array($field, $excludeFields, true)) {
                continue;
            }

            $filtered[] = $field;
        }

        return $filtered;
    }

    /**
     * Log an audit entry
     *
     * @param string $action Action type (create, update, delete)
     * @param string $model Model name
     * @param int|null $recordId Record ID
     * @param array<string, mixed>|null $oldValues Old values
     * @param array<string, mixed>|null $newValues New values
     * @return void
     */
    protected function _logAudit(
        string $action,
        string $model,
        ?int $recordId,
        ?array $oldValues,
        ?array $newValues,
    ): void {
        $auditLogs = TableRegistry::getTableLocator()->get('AuditLogs');

        $ipAddress = null;
        $userAgent = null;

        if ($this->_request !== null) {
            $ipAddress = $this->_request->clientIp();
            $userAgent = $this->_request->getHeaderLine('User-Agent');
        }

        $auditLogs->log(
            $action,
            $model,
            $recordId,
            $oldValues,
            $newValues,
            $this->_userId,
            $ipAddress,
            $userAgent,
        );
    }
}
