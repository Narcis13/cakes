<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * WorkflowSchedulesFixture
 */
class WorkflowSchedulesFixture extends TestFixture
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
                'name' => 'Lorem ipsum dolor sit amet',
                'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'cron_expression' => 'Lorem ipsum dolor sit amet',
                'schedule_type' => 'Lorem ipsum dolor sit amet',
                'interval_minutes' => 1,
                'run_at' => '2025-07-08 11:47:12',
                'input_data_json' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'timezone' => 'Lorem ipsum dolor sit amet',
                'is_active' => 1,
                'last_run_at' => '2025-07-08 11:47:12',
                'last_execution_id' => 1,
                'next_run_at' => '2025-07-08 11:47:12',
                'run_count' => 1,
                'max_runs' => 1,
                'created_by' => 1,
                'created' => '2025-07-08 11:47:12',
                'modified' => '2025-07-08 11:47:12',
            ],
        ];
        parent::init();
    }
}
