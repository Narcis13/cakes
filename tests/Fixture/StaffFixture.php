<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * StaffFixture
 */
class StaffFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'staff';
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
                'first_name' => 'Lorem ipsum dolor sit amet',
                'last_name' => 'Lorem ipsum dolor sit amet',
                'title' => 'Lorem ipsum dolor sit amet',
                'specialization' => 'Lorem ipsum dolor sit amet',
                'department_id' => 1,
                'phone' => 'Lorem ipsum dolor ',
                'email' => 'Lorem ipsum dolor sit amet',
                'bio' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'photo' => 'Lorem ipsum dolor sit amet',
                'years_experience' => 1,
                'staff_type' => 'Lorem ipsum dolor ',
                'is_active' => 1,
                'created' => '2025-06-03 11:41:24',
                'modified' => '2025-06-03 11:41:24',
            ],
        ];
        parent::init();
    }
}
