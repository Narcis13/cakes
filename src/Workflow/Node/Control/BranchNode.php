<?php
declare(strict_types=1);

namespace App\Workflow\Node\Control;

use App\Workflow\Engine\ExecutionContext;
use App\Workflow\Node\NodeInterface;

/**
 * Branch control node
 *
 * Evaluates conditions and returns appropriate edge
 */
class BranchNode implements NodeInterface
{
    /**
     * @inheritDoc
     */
    public function getMetadata(): array
    {
        return [
            'name' => 'branch',
            'description' => 'Conditional branching based on state',
            'type' => 'control',
            'ai_hints' => [
                'purpose' => 'Make decisions based on conditions',
                'when_to_use' => 'When you need to choose between multiple paths',
                'expected_edges' => ['dynamic based on conditions'],
                'example_usage' => '{ "branch": { "conditions": { "premium": "state.userType == \'premium\'", "regular": "state.userType == \'regular\'" } } }',
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function execute(ExecutionContext $context): array
    {
        $conditions = $context->getConfigValue('conditions', []);
        $defaultEdge = $context->getConfigValue('default', 'default');
        $state = $context->getState();

        // Evaluate each condition
        foreach ($conditions as $edge => $condition) {
            if ($state->evaluate($condition)) {
                return [
                    $edge => fn() => [],
                ];
            }
        }

        // Return default edge if no conditions match
        return [
            $defaultEdge => fn() => [],
        ];
    }
}
