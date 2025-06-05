<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

class DashboardController extends AppController
{
    public function index()
    {
        // Add any data you want to show on the dashboard
        $this->set('title', 'Admin Dashboard');
    }
}