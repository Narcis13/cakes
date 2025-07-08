<?php
declare(strict_types=1);

namespace App\Workflow\Node\Control;

use App\Workflow\Engine\ExecutionContext;
use App\Workflow\Node\NodeInterface;

/**
 * While loop controller node
 *
 * Evaluates a condition and returns next_iteration or exit_loop edges
 */
class WhileConditionNode implements NodeInterface
{
    /**
     * @inheritDoc
     */
    public function getMetadata(): array
    {
        return [
            'name' => 'whileCondition',
            'description' => 'Loops while condition is true',
            'type' => 'control',
            'ai_hints' => [
                'purpose' => 'Loop control based on condition',
                'when_to_use' => 'When you need to repeat actions while a condition is true',
                'expected_edges' => ['next_iteration', 'exit_loop'],
                'example_usage' => '{ "whileCondition": { "condition": "state.attempts < 3" } }',
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function execute(ExecutionContext $context): array
    {
        $condition = $context->getConfigValue('condition', 'false');
        $state = $context->getState();

        // Evaluate the condition
        $result = $state->evaluate($condition);

        if ($result) {
            return [
                'next_iteration' => fn() => [],
            ];
        } else {
            return [
                'exit_loop' => fn() => [],
            ];
        }
    }
}
