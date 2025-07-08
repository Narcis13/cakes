<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Workflow $workflow
 */
?>
<div class="workflows view content">
    <div class="row">
        <div class="col-md-8">
            <h3><?= h($workflow->name) ?></h3>
            <?php if ($workflow->description): ?>
                <p class="text-muted"><?= h($workflow->description) ?></p>
            <?php endif; ?>
        </div>
        <div class="col-md-4 text-end">
            <div class="btn-group" role="group">
                <?= $this->Html->link(__('Execute'), ['action' => 'execute', $workflow->id], ['class' => 'btn btn-success']) ?>
                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $workflow->id], ['class' => 'btn btn-primary']) ?>
                <?= $this->Html->link(__('Clone'), ['action' => 'clone', $workflow->id], ['class' => 'btn btn-secondary']) ?>
                <?= $this->Form->postLink(
                    $workflow->is_active ? __('Deactivate') : __('Activate'),
                    ['action' => 'toggleStatus', $workflow->id],
                    ['class' => 'btn btn-warning', 'confirm' => __('Are you sure you want to {0} this workflow?', $workflow->is_active ? 'deactivate' : 'activate')]
                ) ?>
                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $workflow->id], ['confirm' => __('Are you sure you want to delete # {0}?', $workflow->id), 'class' => 'btn btn-danger']) ?>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><?= __('Workflow Details') ?></h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th><?= __('Status') ?></th>
                            <td>
                                <?php
                                $statusClass = match($workflow->status) {
                                    'active' => 'success',
                                    'draft' => 'secondary',
                                    'archived' => 'dark',
                                    default => 'secondary'
                                };
                                ?>
                                <span class="badge bg-<?= $statusClass ?>"><?= h($workflow->status) ?></span>
                            </td>
                        </tr>
                        <tr>
                            <th><?= __('Version') ?></th>
                            <td><?= $this->Number->format($workflow->version) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Category') ?></th>
                            <td>
                                <?php if ($workflow->category): ?>
                                    <span class="badge bg-info"><?= h($workflow->category) ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?= __('Icon') ?></th>
                            <td>
                                <?php if ($workflow->icon): ?>
                                    <i class="fas <?= h($workflow->icon) ?>"></i> <?= h($workflow->icon) ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?= __('Template') ?></th>
                            <td>
                                <?php if ($workflow->is_template): ?>
                                    <span class="badge bg-primary"><?= __('Yes') ?></span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?= __('No') ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?= __('Created By') ?></th>
                            <td>
                                <?php if (isset($workflow->creator)): ?>
                                    <?= h($workflow->creator->name ?? $workflow->creator->email) ?>
                                <?php else: ?>
                                    <?= __('User #{0}', $workflow->created_by) ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?= __('Created') ?></th>
                            <td><?= h($workflow->created->format('Y-m-d H:i:s')) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Modified') ?></th>
                            <td><?= h($workflow->modified->format('Y-m-d H:i:s')) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><?= __('Execution Statistics') ?></h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($executionStats)): ?>
                        <table class="table table-borderless">
                            <tr>
                                <th><?= __('Total Executions') ?></th>
                                <td><?= $this->Number->format($executionStats['total']) ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Successful') ?></th>
                                <td>
                                    <span class="text-success">
                                        <?= $this->Number->format($executionStats['successful']) ?>
                                        <?php if ($executionStats['total'] > 0): ?>
                                            (<?= $this->Number->toPercentage(($executionStats['successful'] / $executionStats['total']) * 100, 1) ?>)
                                        <?php endif; ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th><?= __('Failed') ?></th>
                                <td>
                                    <span class="text-danger">
                                        <?= $this->Number->format($executionStats['failed']) ?>
                                        <?php if ($executionStats['total'] > 0): ?>
                                            (<?= $this->Number->toPercentage(($executionStats['failed'] / $executionStats['total']) * 100, 1) ?>)
                                        <?php endif; ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th><?= __('Running') ?></th>
                                <td>
                                    <span class="text-warning">
                                        <?= $this->Number->format($executionStats['running']) ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th><?= __('Average Duration') ?></th>
                                <td>
                                    <?php if ($executionStats['avg_duration']): ?>
                                        <?= h($executionStats['avg_duration']) ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th><?= __('Last Execution') ?></th>
                                <td>
                                    <?php if ($executionStats['last_execution']): ?>
                                        <?= h($executionStats['last_execution']->format('Y-m-d H:i:s')) ?>
                                    <?php else: ?>
                                        <span class="text-muted"><?= __('Never') ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    <?php else: ?>
                        <p class="text-muted mb-0"><?= __('No execution data available yet.') ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><?= __('Workflow Definition') ?></h5>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleDefinitionView()">
                        <i class="fas fa-exchange-alt"></i> <?= __('Toggle View') ?>
                    </button>
                </div>
                <div class="card-body">
                    <div id="definition-json" style="display: block;">
                        <pre><code class="language-json"><?= h(json_encode($workflow->definition, JSON_PRETTY_PRINT)) ?></code></pre>
                    </div>
                    <div id="definition-visual" style="display: none;">
                        <div class="workflow-summary">
                            <?php if ($workflow->definition): ?>
                                <h6><?= __('Workflow Elements') ?></h6>
                                <ul class="list-group">
                                    <?php foreach ($workflow->definition['elements'] ?? [] as $element): ?>
                                        <li class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong><?= h($element['node'] ?? 'Unknown') ?></strong>
                                                    <?php if (!empty($element['label'])): ?>
                                                        <span class="text-muted">- <?= h($element['label']) ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <?php if (!empty($element['type'])): ?>
                                                        <span class="badge bg-secondary"><?= h($element['type']) ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <?php if (!empty($element['edges'])): ?>
                                                <small class="text-muted">
                                                    <?= __('Edges: {0}', implode(', ', array_keys($element['edges']))) ?>
                                                </small>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($recentExecutions)): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><?= __('Recent Executions') ?></h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><?= __('ID') ?></th>
                                    <th><?= __('Started') ?></th>
                                    <th><?= __('Status') ?></th>
                                    <th><?= __('Duration') ?></th>
                                    <th><?= __('Executed By') ?></th>
                                    <th class="actions"><?= __('Actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentExecutions as $execution): ?>
                                <tr>
                                    <td><?= h($execution->id) ?></td>
                                    <td><?= h($execution->started_at->format('Y-m-d H:i:s')) ?></td>
                                    <td>
                                        <?php
                                        $statusClass = match($execution->status) {
                                            'completed' => 'success',
                                            'failed' => 'danger',
                                            'running' => 'warning',
                                            'cancelled' => 'secondary',
                                            default => 'secondary'
                                        };
                                        ?>
                                        <span class="badge bg-<?= $statusClass ?>"><?= h($execution->status) ?></span>
                                    </td>
                                    <td>
                                        <?php if ($execution->completed_at): ?>
                                            <?= h($execution->duration) ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (isset($execution->user)): ?>
                                            <?= h($execution->user->name ?? $execution->user->email) ?>
                                        <?php else: ?>
                                            <?= __('System') ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="actions">
                                        <?= $this->Html->link(__('View Logs'), ['controller' => 'WorkflowExecutions', 'action' => 'view', $execution->id], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <?= $this->Html->link(__('View All Executions'), ['controller' => 'WorkflowExecutions', 'action' => 'index', '?' => ['workflow_id' => $workflow->id]], ['class' => 'btn btn-outline-primary']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
function toggleDefinitionView() {
    const jsonView = document.getElementById('definition-json');
    const visualView = document.getElementById('definition-visual');
    
    if (jsonView.style.display === 'block') {
        jsonView.style.display = 'none';
        visualView.style.display = 'block';
    } else {
        jsonView.style.display = 'block';
        visualView.style.display = 'none';
    }
}

// Syntax highlighting for JSON
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Prism !== 'undefined') {
        Prism.highlightAll();
    }
});
</script>