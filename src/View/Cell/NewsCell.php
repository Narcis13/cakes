<?php
declare(strict_types=1);

namespace App\View\Cell;

use Cake\ORM\TableRegistry;
use Cake\View\Cell;

/**
 * News cell
 */
class NewsCell extends Cell
{
    /**
     * Default display method.
     *
     * @return void
     */
    public function display(): void
    {
        $newsTable = TableRegistry::getTableLocator()->get('News');
        
        $news = $newsTable->find()
            ->where(['News.is_published' => true])
            ->orderBy(['News.publish_date' => 'DESC'])
            ->limit(6)
            ->toArray();

        $videoUrl = 'https://www.youtube.com/watch?v=jDDaplaOz7Q';
        $title = 'ULTIMELE NOUTATI';
        $description = 'Află ultimele noutați din viața spitalului nostru';

        $this->set(compact('videoUrl', 'title', 'description', 'news'));
    }
}