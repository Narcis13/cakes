<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * Users Controller
 *
 * Handles user authentication for the public site
 * Admin users should use the Admin prefix login
 */
class UsersController extends AppController
{
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        // Allow all actions since this is mainly for redirecting to admin
        $this->Authentication->addUnauthenticatedActions(['login', 'logout']);
    }

    /**
     * Login method - redirects to admin login
     *
     * @return \Cake\Http\Response|null|void
     */
    public function login()
    {
        // Check if user is already authenticated
        $result = $this->Authentication->getResult();
        if ($result && $result->isValid()) {
            $user = $this->Authentication->getIdentity();
            if ($user && in_array($user->get('role'), ['admin', 'staff'])) {
                return $this->redirect([
                    'controller' => 'Dashboard',
                    'action' => 'index',
                    'prefix' => 'Admin',
                ]);
            }
        }

        // Redirect public users to admin login
        return $this->redirect([
            'controller' => 'Users',
            'action' => 'login',
            'prefix' => 'Admin',
        ]);
    }

    /**
     * Logout method
     *
     * @return \Cake\Http\Response|null|void
     */
    public function logout()
    {
        $result = $this->Authentication->getResult();
        if ($result && $result->isValid()) {
            $this->Authentication->logout();

            return $this->redirect([
                'controller' => 'Pages',
                'action' => 'display',
                'index',
            ]);
        }

        return $this->redirect([
            'controller' => 'Pages',
            'action' => 'display',
            'index',
        ]);
    }
}
