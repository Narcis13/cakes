<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

/**
 * Dashboard Controller
 *
 * Admin dashboard with overview of hospital management system
 */
class DashboardController extends AppController
{
    /**
     * Index method
     * 
     * @return \Cake\Http\Response|null|void
     */
    public function index()
    {
        // Get current user
        $user = $this->Authentication->getIdentity();
        
        // Load some basic statistics for the dashboard
        $appointmentsTable = $this->fetchTable('Appointments');
        $staffTable = $this->fetchTable('Staff');
        $departmentsTable = $this->fetchTable('Departments');
        $newsTable = $this->fetchTable('News');
        
        // Get counts for dashboard widgets
        $stats = [
            'total_appointments' => $appointmentsTable->find()->count(),
            'today_appointments' => $appointmentsTable->find()
                ->where(['DATE(appointment_date)' => date('Y-m-d')])
                ->count(),
            'total_staff' => $staffTable->find()->count(),
            'total_departments' => $departmentsTable->find()->count(),
            'recent_news' => $newsTable->find()
                ->order(['created' => 'DESC'])
                ->limit(5)
                ->toArray()
        ];
        
        $this->set([
            'title' => 'Admin Dashboard',
            'user' => $user,
            'stats' => $stats
        ]);
    }
}