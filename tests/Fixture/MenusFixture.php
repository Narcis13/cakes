<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MenusFixture
 */
class MenusFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'menus';
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
                'title' => 'Lorem ipsum dolor sit amet',
                'url' => 'Lorem ipsum dolor sit amet',
                'parent_id' => 1,
                'sort_order' => 1,
                'is_active' => 1,
                'created' => '2025-06-03 11:44:09',
                'modified' => '2025-06-03 11:44:09',
            ],
        ];
        parent::init();
    }
}
