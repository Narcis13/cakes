<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Workflow\Engine\WorkflowEngine;
use App\Workflow\Node\NodeRegistry;
use Cake\Event\EventInterface;
use Exception;

/**
 * Workflows Controller
 *
 * @property \App\Model\Table\WorkflowsTable $Workflows
 */
class WorkflowsController extends AppController
{
    /**
     * beforeFilter callback
     *
     * @param \Cake\Event\EventInterface $event Event
     * @return void
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Workflows->find()
            ->contain(['CreatedByUser']);

        $workflows = $this->paginate($query, [
            'order' => ['Workflows.created' => 'DESC'],
        ]);

        $this->set(compact('workflows'));
    }

    /**
     * View method
     *
     * @param string|null $id Workflow id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null)
    {
        $workflow = $this->Workflows->get($id, contain: [
            'CreatedByUser',
            'WorkflowExecutions' => [
                'limit' => 10,
                'order' => ['WorkflowExecutions.started_at' => 'DESC'],
            ],
            'WorkflowPermissions' => ['Users'],
            'WorkflowSchedules',
        ]);

        // Get execution statistics
        $executionStats = $this->Workflows->WorkflowExecutions->find()
            ->where(['workflow_id' => $id])
            ->select([
                'total' => $this->Workflows->WorkflowExecutions->find()->func()->count('*'),
                'successful' => $this->Workflows->WorkflowExecutions->find()->func()->sum(
                    'CASE WHEN status = \'completed\' THEN 1 ELSE 0 END',
                ),
                'failed' => $this->Workflows->WorkflowExecutions->find()->func()->sum(
                    'CASE WHEN status = \'failed\' THEN 1 ELSE 0 END',
                ),
                'running' => $this->Workflows->WorkflowExecutions->find()->func()->sum(
                    'CASE WHEN status = \'running\' THEN 1 ELSE 0 END',
                ),
            ])
            ->first();

        // Get last execution time and average duration
        $lastExecutionData = $this->Workflows->WorkflowExecutions->find()
            ->where(['workflow_id' => $id])
            ->order(['started_at' => 'DESC'])
            ->select(['started_at'])
            ->first();

        if ($lastExecutionData) {
            $executionStats->last_execution = $lastExecutionData->started_at;
        }

        // Calculate average duration
        $avgDurationData = $this->Workflows->WorkflowExecutions->find()
            ->where([
                'workflow_id' => $id,
                'status' => 'completed',
                'completed_at IS NOT' => null,
            ])
            ->select([
                'avg_seconds' => $this->Workflows->WorkflowExecutions->find()->func()->avg(
                    'TIMESTAMPDIFF(SECOND, started_at, completed_at)',
                ),
            ])
            ->first();

        if ($avgDurationData && $avgDurationData->avg_seconds) {
            $seconds = (int)$avgDurationData->avg_seconds;
            if ($seconds < 60) {
                $executionStats->avg_duration = $seconds . 's';
            } elseif ($seconds < 3600) {
                $executionStats->avg_duration = round($seconds / 60, 1) . 'm';
            } else {
                $executionStats->avg_duration = round($seconds / 3600, 1) . 'h';
            }
        }

        // Get recent executions
        $recentExecutions = $this->Workflows->WorkflowExecutions->find()
            ->where(['workflow_id' => $id])
            ->contain(['Users'])
            ->order(['started_at' => 'DESC'])
            ->limit(10)
            ->all();

        // Add duration to each execution
        foreach ($recentExecutions as $execution) {
            if ($execution->completed_at) {
                $diff = $execution->completed_at->diff($execution->started_at);
                $seconds = $diff->s + ($diff->i * 60) + ($diff->h * 3600) + ($diff->days * 86400);
                if ($seconds < 60) {
                    $execution->duration = $seconds . 's';
                } elseif ($seconds < 3600) {
                    $execution->duration = round($seconds / 60, 1) . 'm';
                } else {
                    $execution->duration = round($seconds / 3600, 1) . 'h';
                }
            }
        }

        $this->set(compact('workflow', 'executionStats', 'recentExecutions'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $workflow = $this->Workflows->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            // Add created_by
            $data['created_by'] = $this->Authentication->getIdentity()->id;

            // Validate JSON if provided directly
            if (!empty($data['definition_json']) && is_string($data['definition_json'])) {
                json_decode($data['definition_json']);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $this->Flash->error(__('Invalid JSON in workflow definition.'));
                    $this->set(compact('workflow'));

                    return;
                }
            }

            $workflow = $this->Workflows->patchEntity($workflow, $data);

            if ($this->Workflows->save($workflow)) {
                $this->Flash->success(__('The workflow has been saved.'));

                return $this->redirect(['action' => 'view', $workflow->id]);
            }
            $this->Flash->error(__('The workflow could not be saved. Please, try again.'));
        }

        // Get available nodes
        $nodeRegistry = new NodeRegistry();
        $availableNodes = $nodeRegistry->getMetadata();

        // Get categories for dropdown
        $categories = [
            'patient' => 'Patient Management',
            'staff' => 'Staff Management',
            'appointment' => 'Appointments',
            'billing' => 'Billing',
            'inventory' => 'Inventory',
            'emergency' => 'Emergency',
            'maintenance' => 'Maintenance',
            'other' => 'Other',
        ];

        $this->set(compact('workflow', 'availableNodes', 'categories'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Workflow id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null)
    {
        $workflow = $this->Workflows->get($id, contain: []);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            // Validate JSON if provided directly
            if (!empty($data['definition_json']) && is_string($data['definition_json'])) {
                json_decode($data['definition_json']);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $this->Flash->error(__('Invalid JSON in workflow definition.'));
                    $this->set(compact('workflow'));

                    return;
                }
            }

            // Increment version if definition changed
            if ($data['definition_json'] !== $workflow->definition_json) {
                $data['version'] = $workflow->version + 1;
            }

            $workflow = $this->Workflows->patchEntity($workflow, $data);

            if ($this->Workflows->save($workflow)) {
                $this->Flash->success(__('The workflow has been saved.'));

                return $this->redirect(['action' => 'view', $workflow->id]);
            }
            $this->Flash->error(__('The workflow could not be saved. Please, try again.'));
        }

        // Get available nodes
        $nodeRegistry = new NodeRegistry();
        $availableNodes = $nodeRegistry->getMetadata();

        // Get categories for dropdown
        $categories = [
            'patient' => 'Patient Management',
            'staff' => 'Staff Management',
            'appointment' => 'Appointments',
            'billing' => 'Billing',
            'inventory' => 'Inventory',
            'emergency' => 'Emergency',
            'maintenance' => 'Maintenance',
            'other' => 'Other',
        ];

        $this->set(compact('workflow', 'availableNodes', 'categories'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Workflow id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $workflow = $this->Workflows->get($id);

        // Check if workflow has executions
        $hasExecutions = $this->Workflows->WorkflowExecutions->exists(['workflow_id' => $id]);

        if ($hasExecutions) {
            // Archive instead of delete
            $workflow->status = 'archived';
            if ($this->Workflows->save($workflow)) {
                $this->Flash->success(__('The workflow has been archived.'));
            } else {
                $this->Flash->error(__('The workflow could not be archived. Please, try again.'));
            }
        } else {
            if ($this->Workflows->delete($workflow)) {
                $this->Flash->success(__('The workflow has been deleted.'));
            } else {
                $this->Flash->error(__('The workflow could not be deleted. Please, try again.'));
            }
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Execute a workflow
     *
     * @param string|null $id Workflow id.
     * @return \Cake\Http\Response|null Redirects to execution view.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function execute(?string $id = null)
    {
        $this->request->allowMethod(['post']);
        $workflow = $this->Workflows->get($id);

        if (!$workflow->is_active) {
            $this->Flash->error(__('Cannot execute inactive workflow.'));

            return $this->redirect(['action' => 'view', $id]);
        }

        try {
            $inputData = $this->request->getData('input_data', []);
            if (is_string($inputData)) {
                $inputData = json_decode($inputData, true) ?? [];
            }

            $engine = new WorkflowEngine();
            $execution = $engine->execute(
                $workflow,
                $inputData,
                $this->Authentication->getIdentity()->id,
            );

            $this->Flash->success(__('Workflow execution started.'));

            return $this->redirect([
                'controller' => 'WorkflowExecutions',
                'action' => 'view',
                $execution->id,
            ]);
        } catch (Exception $e) {
            $this->Flash->error(__('Failed to execute workflow: {0}', $e->getMessage()));

            return $this->redirect(['action' => 'view', $id]);
        }
    }

    /**
     * Clone a workflow
     *
     * @param string|null $id Workflow id.
     * @return \Cake\Http\Response|null Redirects to edit.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function clone(?string $id = null)
    {
        $workflow = $this->Workflows->get($id);

        $newWorkflow = $workflow->cloneAsNewVersion();
        $newWorkflow->name = $workflow->name . ' (Copy)';
        $newWorkflow->created_by = $this->Authentication->getIdentity()->id;

        if ($this->Workflows->save($newWorkflow)) {
            $this->Flash->success(__('The workflow has been cloned.'));

            return $this->redirect(['action' => 'edit', $newWorkflow->id]);
        }

        $this->Flash->error(__('The workflow could not be cloned.'));

        return $this->redirect(['action' => 'view', $id]);
    }

    /**
     * Toggle workflow status
     *
     * @param string|null $id Workflow id.
     * @return \Cake\Http\Response|null Redirects back.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function toggleStatus(?string $id = null)
    {
        $this->request->allowMethod(['post']);
        $workflow = $this->Workflows->get($id);

        // Toggle between active and inactive
        if ($workflow->status === 'active') {
            $workflow->status = 'inactive';
        } elseif ($workflow->status === 'inactive') {
            $workflow->status = 'active';
        } else {
            $this->Flash->error(__('Only active and inactive workflows can be toggled.'));

            return $this->redirect(['action' => 'view', $id]);
        }

        if ($this->Workflows->save($workflow)) {
            $this->Flash->success(__('Workflow status has been updated.'));
        } else {
            $this->Flash->error(__('Could not update workflow status.'));
        }

        return $this->redirect(['action' => 'view', $id]);
    }

    /**
     * Builder method - Visual workflow builder
     *
     * @param string|null $id Workflow id (for editing).
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function builder(?string $id = null)
    {
        if ($id) {
            $workflow = $this->Workflows->get($id);
        } else {
            $workflow = $this->Workflows->newEmptyEntity();
        }

        // Get available nodes
        $nodeRegistry = new NodeRegistry();
        $availableNodes = $nodeRegistry->getMetadata();

        // Group nodes by category
        $nodesByCategory = [];
        foreach ($availableNodes as $name => $metadata) {
            $category = $metadata['category'] ?? 'other';
            $nodesByCategory[$category][$name] = $metadata;
        }

        $this->set(compact('workflow', 'nodesByCategory'));
    }
}
