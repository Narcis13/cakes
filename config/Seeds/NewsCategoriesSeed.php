<?php
declare(strict_types=1);

use Migrations\BaseSeed;

/**
 * NewsCategories seed.
 */
class NewsCategoriesSeed extends BaseSeed
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
                'name' => 'Hospital Updates',
                'slug' => 'hospital-updates',
                'description' => 'Latest news and updates about our hospital facilities, services, and policies.',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Health Tips',
                'slug' => 'health-tips',
                'description' => 'Medical advice, wellness tips, and health information from our expert doctors.',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Events',
                'slug' => 'events',
                'description' => 'Upcoming health seminars, community events, and hospital activities.',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Medical Breakthroughs',
                'slug' => 'medical-breakthroughs',
                'description' => 'Latest medical research, new treatments, and technological advances in healthcare.',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Patient Stories',
                'slug' => 'patient-stories',
                'description' => 'Inspiring stories from our patients and their journey to recovery.',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Staff Highlights',
                'slug' => 'staff-highlights',
                'description' => 'Recognizing our dedicated healthcare professionals and their achievements.',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
        ];

        $table = $this->table('news_categories');
        $table->insert($data)->save();
    }
}
