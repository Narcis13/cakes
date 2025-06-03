<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AppointmentsFixture
 */
class AppointmentsFixture extends TestFixture
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
                'patient_name' => 'Lorem ipsum dolor sit amet',
                'patient_phone' => 'Lorem ipsum dolor ',
                'patient_email' => 'Lorem ipsum dolor sit amet',
                'service_id' => 1,
                'doctor_id' => 1,
                'appointment_date' => '2025-06-03 11:42:51',
                'status' => 'Lorem ipsum dolor ',
                'notes' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'created' => '2025-06-03 11:42:51',
                'modified' => '2025-06-03 11:42:51',
            ],
        ];
        parent::init();
    }
}
