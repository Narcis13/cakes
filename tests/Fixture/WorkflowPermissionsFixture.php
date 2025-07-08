<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * WorkflowPermissionsFixture
 */
class WorkflowPermissionsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'workflow_id' => 1,
                'user_id' => 1,
                'role' => 'Lorem ipsum dolor sit amet',
                'can_execute' => 1,
                'can_edit' => 1,
                'can_delete' => 1,
                'can_view_logs' => 1,
                'can_manage_permissions' => 1,
                'created' => '2025-07-08 11:45:45',
                'modified' => '2025-07-08 11:45:45',
            ],
        ];
        parent::init();
    }
}
