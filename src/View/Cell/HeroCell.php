<?php
declare(strict_types=1);

namespace App\View\Cell;

use Cake\View\Cell;

/**
 * Hero cell
 */
class HeroCell extends Cell
{
    /**
     * Default display method.
     *
     * @return void
     */
    public function display(): void
    {
        $title = 'Welcome to Medilab';
        $subtitle = 'We are team of talented designers making websites with Bootstrap';
        $buttonText = 'Get Started';
        $buttonLink = '#about';
        
        $this->set(compact('title', 'subtitle', 'buttonText', 'buttonLink'));
    }
}
