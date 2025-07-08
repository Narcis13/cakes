<?php
declare(strict_types=1);

namespace App\Workflow\Node\Utility;

use App\Workflow\Engine\ExecutionContext;
use App\Workflow\Node\NodeInterface;

/**
 * Wait node
 *
 * Pauses execution for a specified duration (simulated)
 */
class WaitNode implements NodeInterface
{
    /**
     * @inheritDoc
     */
    public function getMetadata(): array
    {
        return [
            'name' => 'wait',
            'description' => 'Wait for a specified duration',
            'type' => 'utility',
            'ai_hints' => [
                'purpose' => 'Delay execution',
                'when_to_use' => 'When you need to wait between actions',
                'expected_edges' => ['done'],
                'example_usage' => '{ "wait": { "seconds": 30 } }',
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function execute(ExecutionContext $context): array
    {
        $seconds = $context->getConfigValue('seconds', 1);
        $runtime = $context->getRuntime();

        // Log the wait
        $runtime->log('wait', 'info', sprintf('Waiting for %d seconds', $seconds));

        // In a real implementation, this would schedule a resume
        // For now, we'll just return immediately
        // sleep($seconds); // Don't actually sleep in web context!

        return [
            'done' => fn() => [
                'waited' => $seconds,
                'waitedAt' => time(),
            ],
        ];
    }
}
