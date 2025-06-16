<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController as BaseController;
use Cake\Event\EventInterface;

/**
 * Admin App Controller
 *
 * Base controller for the admin prefix.
 */
class AppController extends BaseController
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->viewBuilder()->setLayout('admin');
    }

    /**
     * Before filter callback.
     * 
     * @param \Cake\Event\EventInterface $event The beforeFilter event.
     * @return \Cake\Http\Response|null|void
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        
        // Require authentication for all admin actions except login
        $this->Authentication->addUnauthenticatedActions(['login']);
        
        // Check if user is authenticated
        $result = $this->Authentication->getResult();
        if (!$result->isValid()) {
            // Redirect to login if not authenticated
            if ($this->request->getParam('action') !== 'login') {
                $this->Flash->error(__('You must be logged in to access the admin area.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'login', 'prefix' => 'Admin']);
            }
        } else {
            // Check if user has admin role
            $user = $this->Authentication->getIdentity();
            if ($user && !in_array($user->get('role'), ['admin', 'staff'])) {
                $this->Flash->error(__('You do not have permission to access the admin area.'));
                $this->Authentication->logout();
                return $this->redirect(['controller' => 'Users', 'action' => 'login', 'prefix' => false]);
            }
        }
    }
}