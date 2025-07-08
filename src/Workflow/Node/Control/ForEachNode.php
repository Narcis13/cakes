<?php
declare(strict_types=1);

namespace App\Workflow\Node\Control;

use App\Workflow\Engine\ExecutionContext;
use App\Workflow\Node\NodeInterface;

/**
 * For-each loop controller node
 *
 * Iterates over array items
 */
class ForEachNode implements NodeInterface
{
    /**
     * @inheritDoc
     */
    public function getMetadata(): array
    {
        return [
            'name' => 'forEach',
            'description' => 'Iterates over array items',
            'type' => 'control',
            'ai_hints' => [
                'purpose' => 'Iterate over collections',
                'when_to_use' => 'When you need to process each item in an array',
                'expected_edges' => ['next_iteration', 'exit_loop'],
                'example_usage' => '{ "forEach": { "items": "state.documents", "as": "currentDoc" } }',
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function execute(ExecutionContext $context): array
    {
        $itemsPath = $context->getConfigValue('items', '');
        $asVar = $context->getConfigValue('as', 'current');
        $state = $context->getState();

        // Get items array
        $items = $state->get($itemsPath, []);
        if (!is_array($items)) {
            return ['exit_loop' => fn() => []];
        }

        // Get current index
        $currentIndex = $state->get('_loopIndex', 0);

        if ($currentIndex < count($items)) {
            return [
                'next_iteration' => fn() => [
                    $asVar => $items[$currentIndex],
                    '_loopIndex' => $currentIndex + 1,
                ],
            ];
        } else {
            return [
                'exit_loop' => fn() => [
                    '_loopIndex' => 0,
                ],
            ];
        }
    }
}
