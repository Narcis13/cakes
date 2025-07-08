<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * WorkflowNode Entity
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string|null $category
 * @property string|null $description
 * @property string $metadata_json
 * @property string $handler_class
 * @property string|null $icon
 * @property bool $is_builtin
 * @property bool $is_active
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 * @property-read array $metadata
 * @property-read array $ai_hints
 * @property-read array|null $human_interaction
 */
class WorkflowNode extends Entity
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
        'type' => true,
        'category' => true,
        'description' => true,
        'metadata_json' => true,
        'handler_class' => true,
        'icon' => true,
        'is_builtin' => true,
        'is_active' => true,
        'created' => true,
        'modified' => true,
    ];

    /**
     * List of computed properties
     *
     * @var array<string>
     */
    protected array $_virtual = ['metadata', 'ai_hints', 'human_interaction'];

    /**
     * Get the parsed metadata
     *
     * @return array
     */
    protected function _getMetadata(): array
    {
        if (!empty($this->metadata_json)) {
            return json_decode($this->metadata_json, true) ?? [];
        }

        return [];
    }

    /**
     * Get AI hints from metadata
     *
     * @return array
     */
    protected function _getAiHints(): array
    {
        $metadata = $this->metadata;

        return $metadata['ai_hints'] ?? [];
    }

    /**
     * Get human interaction config from metadata
     *
     * @return array|null
     */
    protected function _getHumanInteraction(): ?array
    {
        $metadata = $this->metadata;

        return $metadata['humanInteraction'] ?? null;
    }

    /**
     * Set the metadata from array
     *
     * @param array $metadata Node metadata array
     * @return void
     */
    public function setMetadata(array $metadata): void
    {
        $this->metadata_json = json_encode($metadata);
    }

    /**
     * Check if this node requires human interaction
     *
     * @return bool
     */
    public function isHumanNode(): bool
    {
        return $this->type === 'human';
    }

    /**
     * Get the handler class instance
     *
     * @return object|null
     */
    public function getHandlerInstance(): ?object
    {
        if (class_exists($this->handler_class)) {
            return new $this->handler_class();
        }

        return null;
    }
}
