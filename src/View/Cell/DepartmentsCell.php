<?php
declare(strict_types=1);

namespace App\View\Cell;

use Cake\View\Cell;

/**
 * Departments cell
 */
class DepartmentsCell extends Cell
{
    /**
     * Default display method.
     *
     * @return void
     */
    public function display(): void
    {
        $sectionTitle = 'Departments';
        $sectionDescription = 'Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia fugiat sit in iste officiis commodi quidem hic quas.';

        $departments = [
            [
                'id' => 'tab-1',
                'name' => 'Cardiology',
                'active' => true,
                'title' => 'Cardiology',
                'subtitle' => 'Qui laudantium consequatur laborum sit qui ad sapiente dila parde sonata raqer a videna mareta paulona marka',
                'description' => 'Et nobis maiores eius. Voluptatibus ut enim blanditiis atque harum sint. Laborum eos ipsum ipsa odit magni. Incidunt hic ut molestiae aut qui. Est repellat minima eveniet eius et quis magni nihil. Consequatur dolorem quaerat quos qui similique accusamus nostrum rem vero',
                'image' => '/img/departments-1.jpg',
            ],
            [
                'id' => 'tab-2',
                'name' => 'Neurology',
                'active' => false,
                'title' => 'Et blanditiis nemo veritatis excepturi',
                'subtitle' => 'Qui laudantium consequatur laborum sit qui ad sapiente dila parde sonata raqer a videna mareta paulona marka',
                'description' => 'Ea ipsum voluptatem consequatur quis est. Illum error ullam omnis quia et reiciendis sunt sunt est. Non aliquid repellendus itaque accusamus eius et velit ipsa voluptates. Optio nesciunt eaque beatae accusamus lerode pakto madirna desera vafle de nideran pal',
                'image' => '/img/departments-2.jpg',
            ],
            [
                'id' => 'tab-3',
                'name' => 'Hepatology',
                'active' => false,
                'title' => 'Impedit facilis occaecati odio neque aperiam sit',
                'subtitle' => 'Eos voluptatibus quo. Odio similique illum id quidem non enim fuga. Qui natus non sunt dicta dolor et. In asperiores velit quaerat perferendis aut',
                'description' => 'Iure officiis odit rerum. Harum sequi eum illum corrupti culpa veritatis quisquam. Neque necessitatibus illo rerum eum ut. Commodi ipsam minima molestiae sed laboriosam a iste odio. Earum odit nesciunt fugiat sit ullam. Soluta et harum voluptatem optio quae',
                'image' => '/img/departments-3.jpg',
            ],
            [
                'id' => 'tab-4',
                'name' => 'Pediatrics',
                'active' => false,
                'title' => 'Fuga dolores inventore laboriosam ut est accusamus laboriosam dolore',
                'subtitle' => 'Totam aperiam accusamus. Repellat consequuntur iure voluptas iure porro quis delectus',
                'description' => 'Eaque consequuntur consequuntur libero expedita in voluptas. Nostrum ipsam necessitatibus aliquam fugiat debitis quis velit. Eum ex maxime error in consequatur corporis atque. Eligendi asperiores sed qui veritatis aperiam quia a laborum inventore',
                'image' => '/img/departments-4.jpg',
            ],
            [
                'id' => 'tab-5',
                'name' => 'Eye Care',
                'active' => false,
                'title' => 'Est eveniet ipsam sindera pad rone matrelat sando reda',
                'subtitle' => 'Omnis blanditiis saepe eos autem qui sunt debitis porro quia.',
                'description' => 'Exercitationem nostrum omnis. Ut reiciendis repudiandae minus. Omnis recusandae ut non quam ut quod eius qui. Ipsum quia odit vero atque qui quibusdam amet. Occaecati sed est sint aut vitae molestiae voluptate vel',
                'image' => '/img/departments-5.jpg',
            ],
        ];

        $this->set(compact('sectionTitle', 'sectionDescription', 'departments'));
    }
}
