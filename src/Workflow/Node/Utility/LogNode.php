<?php
declare(strict_types=1);

namespace App\Workflow\Node\Utility;

use App\Workflow\Engine\ExecutionContext;
use App\Workflow\Node\NodeInterface;

/**
 * Log node
 *
 * Logs a message to the execution log
 */
class LogNode implements NodeInterface
{
    /**
     * @inheritDoc
     */
    public function getMetadata(): array
    {
        return [
            'name' => 'log',
            'description' => 'Log a message',
            'type' => 'utility',
            'ai_hints' => [
                'purpose' => 'Log messages for debugging',
                'when_to_use' => 'When you need to log information during execution',
                'expected_edges' => ['done'],
                'example_usage' => '{ "log": { "message": "Processing started", "level": "info" } }',
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function execute(ExecutionContext $context): array
    {
        $message = $context->getConfigValue('message', 'Log message');
        $level = $context->getConfigValue('level', 'info');
        $data = $context->getConfigValue('data', []);
        $runtime = $context->getRuntime();

        // Validate level
        $validLevels = ['debug', 'info', 'warning', 'error'];
        if (!in_array($level, $validLevels)) {
            $level = 'info';
        }

        // Log the message
        $runtime->log('log', $level, $message, $data);

        return [
            'done' => fn() => [],
        ];
    }
}
