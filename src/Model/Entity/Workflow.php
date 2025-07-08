<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Workflow Entity
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $definition_json
 * @property int $version
 * @property string $status
 * @property string|null $category
 * @property string|null $icon
 * @property int $created_by
 * @property bool $is_template
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 *
 * @property \App\Model\Entity\User $creator
 * @property \App\Model\Entity\WorkflowExecution[] $workflow_executions
 * @property \App\Model\Entity\WorkflowPermission[] $workflow_permissions
 * @property \App\Model\Entity\WorkflowSchedule[] $workflow_schedules
 * @property-read array $definition
 * @property-read bool $is_active
 * @property-read bool $is_draft
 * @property-read int $execution_count
 */
class Workflow extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'name' => true,
        'description' => true,
        'definition_json' => true,
        'version' => true,
        'status' => true,
        'category' => true,
        'icon' => true,
        'created_by' => true,
        'is_template' => true,
        'created' => true,
        'modified' => true,
        'workflow_executions' => true,
        'workflow_permissions' => true,
        'workflow_schedules' => true,
    ];

    /**
     * List of computed properties
     *
     * @var array<string>
     */
    protected array $_virtual = ['definition', 'is_active', 'is_draft'];

    /**
     * Get the parsed workflow definition
     *
     * @return array|null
     */
    protected function _getDefinition(): ?array
    {
        if (!empty($this->definition_json)) {
            return json_decode($this->definition_json, true);
        }

        return null;
    }

    /**
     * Check if workflow is active
     *
     * @return bool
     */
    protected function _getIsActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if workflow is draft
     *
     * @return bool
     */
    protected function _getIsDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Set the workflow definition from array
     *
     * @param array $definition Workflow definition array
     * @return void
     */
    public function setDefinition(array $definition): void
    {
        $this->definition_json = json_encode($definition);
    }

    /**
     * Clone workflow as a new version
     *
     * @return \App\Model\Entity\Workflow
     */
    public function cloneAsNewVersion(): Workflow
    {
        $new = new Workflow([
            'name' => $this->name,
            'description' => $this->description,
            'definition_json' => $this->definition_json,
            'version' => $this->version + 1,
            'status' => 'draft',
            'category' => $this->category,
            'icon' => $this->icon,
            'is_template' => false,
        ]);

        return $new;
    }
}
