<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * StaffUnavailabilitiesFixture
 */
class StaffUnavailabilitiesFixture extends TestFixture
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
                'staff_id' => 1,
                'date_from' => '2025-07-09',
                'date_to' => '2025-07-09',
                'reason' => 'Lorem ipsum dolor sit amet',
                'created' => '2025-07-09 08:42:15',
                'modified' => '2025-07-09 08:42:15',
            ],
        ];
        parent::init();
    }
}
