<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController as BaseController;

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

        // Load authentication and authorization components here for admin area
        // For example:
        // $this->loadComponent('Auth', [
        //     'authorize' => 'Controller', // or other authorization type
        //     'loginAction' => [
        //         'prefix' => 'Users', // or false if login is global
        //         'controller' => 'Users',
        //         'action' => 'login',
        //     ],
        //     'loginRedirect' => [
        //         'prefix' => 'Admin',
        //         'controller' => 'Dashboard',
        //         'action' => 'index',
        //     ],
        //     'logoutRedirect' => [
        //         'prefix' => 'Users', // or false
        //         'controller' => 'Users',
        //         'action' => 'login',
        //     ],
        //     'unauthorizedRedirect' => $this->referer(),
        //     'storage' => 'Session',
        // ]);
        //
        // // Allow display action for all controllers if you are using the PagesController for content.
        // // $this->Auth->allow(['display']);
    }

    // Optional: Add an isAuthorized method if using 'Controller' authorize type
    // public function isAuthorized($user)
    // {
    //     // By default, deny access.
    //     // You can add logic here to check roles, e.g., if ($user['role'] === 'admin') return true;
    //     return false;
    // }
}