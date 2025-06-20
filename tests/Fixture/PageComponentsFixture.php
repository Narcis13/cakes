<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PageComponentsFixture
 */
class PageComponentsFixture extends TestFixture
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
                'page_id' => 1,
                'type' => 'Lorem ipsum dolor sit amet',
                'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'title' => 'Lorem ipsum dolor sit amet',
                'url' => 'Lorem ipsum dolor sit amet',
                'alt_text' => 'Lorem ipsum dolor sit amet',
                'css_class' => 'Lorem ipsum dolor sit amet',
                'sort_order' => 1,
                'is_active' => 1,
                'created' => '2025-06-20 08:29:47',
                'modified' => '2025-06-20 08:29:47',
            ],
        ];
        parent::init();
    }
}
