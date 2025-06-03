<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MediaFixture
 */
class MediaFixture extends TestFixture
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
                'filename' => 'Lorem ipsum dolor sit amet',
                'original_name' => 'Lorem ipsum dolor sit amet',
                'mime_type' => 'Lorem ipsum dolor sit amet',
                'size' => 1,
                'alt_text' => 'Lorem ipsum dolor sit amet',
                'created' => '2025-06-03 11:43:37',
                'modified' => '2025-06-03 11:43:37',
            ],
        ];
        parent::init();
    }
}
