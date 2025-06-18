<?php
declare(strict_types=1);

namespace App\View\Cell;

use Cake\View\Cell;

/**
 * Testimonials cell
 */
class TestimonialsCell extends Cell
{
    /**
     * Default display method.
     *
     * @return void
     */
    public function display(): void
    {
        $testimonials = [
            [
                'name' => 'Saul Goodman',
                'position' => 'Ceo & Founder',
                'image' => '/img/testimonials/testimonials-1.jpg',
                'quote' => 'Proin iaculis purus consequat sem cure digni ssim donec porttitora entum suscipit rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et. Maecen aliquam, risus at semper.'
            ],
            [
                'name' => 'Sara Wilsson',
                'position' => 'Designer',
                'image' => '/img/testimonials/testimonials-2.jpg',
                'quote' => 'Export tempor illum tamen malis malis eram quae irure esse labore quem cillum quid cillum eram malis quorum velit fore eram velit sunt aliqua noster fugiat irure amet legam anim culpa.'
            ],
            [
                'name' => 'Jena Karlis',
                'position' => 'Store Owner',
                'image' => '/img/testimonials/testimonials-3.jpg',
                'quote' => 'Enim nisi quem export duis labore cillum quae magna enim sint quorum nulla quem veniam duis minim tempor labore quem eram duis noster aute amet eram fore quis sint minim.'
            ],
            [
                'name' => 'Matt Brandon',
                'position' => 'Freelancer',
                'image' => '/img/testimonials/testimonials-4.jpg',
                'quote' => 'Fugiat enim eram quae cillum dolore dolor amet nulla culpa multos export minim fugiat minim velit minim dolor enim duis veniam ipsum anim magna sunt elit fore quem dolore labore illum veniam.'
            ],
            [
                'name' => 'John Larson',
                'position' => 'Entrepreneur',
                'image' => '/img/testimonials/testimonials-5.jpg',
                'quote' => 'Quis quorum aliqua sint quem legam fore sunt eram irure aliqua veniam tempor noster veniam enim culpa labore duis sunt culpa nulla illum cillum fugiat legam esse veniam culpa fore nisi cillum quid.'
            ]
        ];
        
        $this->set(compact('testimonials'));
    }
}
