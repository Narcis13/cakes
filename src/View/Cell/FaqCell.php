<?php
declare(strict_types=1);

namespace App\View\Cell;

use Cake\View\Cell;

/**
 * Faq cell
 */
class FaqCell extends Cell
{
    /**
     * Default display method.
     *
     * @return void
     */
    public function display(): void
    {
        $sectionTitle = 'Frequently Asked Questions';
        $sectionDescription = 'Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia fugiat sit in iste officiis commodi quidem hic quas.';
        
        $faqs = [
            [
                'id' => 'faq-list-1',
                'question' => 'Non consectetur a erat nam at lectus urna duis?',
                'answer' => 'Feugiat pretium nibh ipsum consequat. Tempus iaculis urna id volutpat lacus laoreet non curabitur gravida. Venenatis lectus magna fringilla urna porttitor rhoncus dolor purus non.',
                'show' => true,
                'delay' => 0
            ],
            [
                'id' => 'faq-list-2',
                'question' => 'Feugiat scelerisque varius morbi enim nunc?',
                'answer' => 'Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit laoreet id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium. Est pellentesque elit ullamcorper dignissim. Mauris ultrices eros in cursus turpis massa tincidunt dui.',
                'show' => false,
                'delay' => 100
            ],
            [
                'id' => 'faq-list-3',
                'question' => 'Dolor sit amet consectetur adipiscing elit?',
                'answer' => 'Eleifend mi in nulla posuere sollicitudin aliquam ultrices sagittis orci. Faucibus pulvinar elementum integer enim. Sem nulla pharetra diam sit amet nisl suscipit. Rutrum tellus pellentesque eu tincidunt. Lectus urna duis convallis convallis tellus. Urna molestie at elementum eu facilisis sed odio morbi quis',
                'show' => false,
                'delay' => 200
            ],
            [
                'id' => 'faq-list-4',
                'question' => 'Tempus quam pellentesque nec nam aliquam sem et tortor consequat?',
                'answer' => 'Molestie a iaculis at erat pellentesque adipiscing commodo. Dignissim suspendisse in est ante in. Nunc vel risus commodo viverra maecenas accumsan. Sit amet nisl suscipit adipiscing bibendum est. Purus gravida quis blandit turpis cursus in.',
                'show' => false,
                'delay' => 300
            ],
            [
                'id' => 'faq-list-5',
                'question' => 'Tortor vitae purus faucibus ornare. Varius vel pharetra vel turpis nunc eget lorem dolor?',
                'answer' => 'Laoreet sit amet cursus sit amet dictum sit amet justo. Mauris vitae ultricies leo integer malesuada nunc vel. Tincidunt eget nullam non nisi est sit amet. Turpis nunc eget lorem dolor sed. Ut venenatis tellus in metus vulputate eu scelerisque.',
                'show' => false,
                'delay' => 400
            ]
        ];
        
        $this->set(compact('sectionTitle', 'sectionDescription', 'faqs'));
    }
}
