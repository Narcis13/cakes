<?php
declare(strict_types=1);

namespace App\Workflow\Node\Utility;

use App\Workflow\Engine\ExecutionContext;
use App\Workflow\Node\NodeInterface;
use Exception;

/**
 * Set flag node
 *
 * Sets a flag or value in the state
 */
class SetFlagNode implements NodeInterface
{
    /**
     * @inheritDoc
     */
    public function getMetadata(): array
    {
        return [
            'name' => 'setFlag',
            'description' => 'Set a flag or value in state',
            'type' => 'utility',
            'ai_hints' => [
                'purpose' => 'Update state values',
                'when_to_use' => 'When you need to set or update a state variable',
                'expected_edges' => ['done'],
                'example_usage' => '{ "setFlag": { "flag": "hasMore", "value": false } }',
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function execute(ExecutionContext $context): array
    {
        $flag = $context->getConfigValue('flag', '');
        $value = $context->getConfigValue('value', true);

        if (empty($flag)) {
            throw new Exception('Flag name is required');
        }

        return [
            'done' => fn() => [
                $flag => $value,
            ],
        ];
    }
}
