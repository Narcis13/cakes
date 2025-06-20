<?php
declare(strict_types=1);

use Migrations\BaseSeed;

/**
 * Pages seed.
 */
class PagesSeed extends BaseSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/migrations/4/en/seeding.html
     *
     * @return void
     */
    public function run(): void
    {
        $data = [
            [
                'title' => 'About Our Hospital',
                'slug' => 'about-our-hospital',
                'content' => 'Welcome to Medilab Hospital, where compassionate care meets cutting-edge medical technology.',
                'meta_description' => 'Learn about Medilab Hospital, our mission, values, and commitment to excellence in healthcare.',
                'is_published' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Patient Information',
                'slug' => 'patient-information',
                'content' => 'Important information for patients and visitors at Medilab Hospital.',
                'meta_description' => 'Patient information, visiting hours, policies, and guidelines for Medilab Hospital.',
                'is_published' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Medical Research',
                'slug' => 'medical-research',
                'content' => 'Discover our groundbreaking medical research initiatives and clinical trials.',
                'meta_description' => 'Learn about medical research, clinical trials, and scientific discoveries at Medilab Hospital.',
                'is_published' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
        ];

        $table = $this->table('pages');
        $table->insert($data)->save();
    }
}
