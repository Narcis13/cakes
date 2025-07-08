<?php
declare(strict_types=1);

namespace App\Workflow\Node;

use App\Workflow\Engine\ExecutionContext;

/**
 * Interface for all workflow nodes
 *
 * Nodes are the atomic units of work in a workflow.
 * They receive the current state and return edges with lazy-evaluated data payloads.
 */
interface NodeInterface
{
    /**
     * Get node metadata
     *
     * @return array{
     *   name: string,
     *   description: string,
     *   type?: string,
     *   ai_hints: array{
     *     purpose: string,
     *     when_to_use: string,
     *     expected_edges: string[],
     *     example_usage?: string
     *   },
     *   humanInteraction?: array{
     *     formSchema?: array,
     *     uiHints?: array,
     *     timeout?: int
     *   }
     * }
     */
    public function getMetadata(): array;

    /**
     * Execute the node
     *
     * @param \App\Workflow\Engine\ExecutionContext $context The execution context
     * @return array<string, callable> Map of edge names to data thunks
     * @throws \Exception When node execution fails
     */
    public function execute(ExecutionContext $context): array;
}
