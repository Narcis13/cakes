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
        $title = 'Empatie.Excelență.';
        $subtitle = 'Suntem o echipă medicală dedicată, oferind îngrijire de calitate și suport pacienților noștri.';
        $buttonText = 'Despre noi';
        $buttonLink = '#about';

        $this->set(compact('title', 'subtitle', 'buttonText', 'buttonLink'));
    }
}
