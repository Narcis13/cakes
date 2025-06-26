<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FilesFixture
 */
class FilesFixture extends TestFixture
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
                'file_path' => 'Lorem ipsum dolor sit amet',
                'file_url' => 'Lorem ipsum dolor sit amet',
                'mime_type' => 'Lorem ipsum dolor sit amet',
                'file_size' => 1,
                'file_type' => 'Lorem ipsum dolor sit amet',
                'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'category' => 'Lorem ipsum dolor sit amet',
                'is_public' => 1,
                'download_count' => 1,
                'uploaded_by' => 1,
                'created' => '2025-06-26 11:53:51',
                'modified' => '2025-06-26 11:53:51',
            ],
        ];
        parent::init();
    }
}
