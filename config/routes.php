<?php
/**
 * CakePHP Routes Configuration
 * File: config/routes.php
 */

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return static function (RouteBuilder $routes) {
    /*
     * The default class to use for all routes
     */
    $routes->setRouteClass(DashedRoute::class);

    $routes->scope('/', function (RouteBuilder $builder) {
        /*
         * Hospital Website Routes
         */
        
        // Home page - renders Pages/home.php
        $builder->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);
        
        // Contact page - renders Pages/contact.php  
        $builder->connect('/contact', ['controller' => 'Pages', 'action' => 'display', 'contact']);
        
        // Contact form page
        $builder->connect('/formular-contact', ['controller' => 'Pages', 'action' => 'contactForm']);
        
        // Handle contact form submission
        $builder->connect('/formular-contact', ['controller' => 'Pages', 'action' => 'contactForm'])
                ->setMethods(['POST']);
        
        // Map/Contact page (Cum ajungeti) - renders Pages/harta.php
        $builder->connect('/harta', ['controller' => 'Pages', 'action' => 'display', 'harta']);
        
        // Additional hospital pages
        $builder->connect('/about', ['controller' => 'Pages', 'action' => 'display', 'about']);
        $builder->connect('/services', ['controller' => 'Pages', 'action' => 'display', 'services']);
        $builder->connect('/doctors', ['controller' => 'Doctors', 'action' => 'index']);
        $builder->connect('/appointments', ['controller' => 'Appointments', 'action' => 'index']);
        $builder->connect('/appointments/book', ['controller' => 'Appointments', 'action' => 'book']);
        $builder->connect('/appointments/check-availability', ['controller' => 'Appointments', 'action' => 'checkAvailability']);
        $builder->connect('/appointments/get-available-slots', ['controller' => 'Appointments', 'action' => 'getAvailableSlots']);
        $builder->connect('/appointments/success/{id}', ['controller' => 'Appointments', 'action' => 'success'])
            ->setPass(['id'])
            ->setPatterns(['id' => '\d+']);
        
        // Patient Portal Routes
        $builder->connect('/portal', ['controller' => 'Patients', 'action' => 'portal']);
        $builder->connect('/portal/login', ['controller' => 'Patients', 'action' => 'login']);
        $builder->connect('/portal/register', ['controller' => 'Patients', 'action' => 'register']);
        $builder->connect('/portal/logout', ['controller' => 'Patients', 'action' => 'logout']);

        // Password reset routes
        $builder->connect('/portal/forgot-password', ['controller' => 'Patients', 'action' => 'forgotPassword']);
        $builder->connect('/portal/reset-password/{token}', ['controller' => 'Patients', 'action' => 'resetPassword'])
            ->setPass(['token'])
            ->setPatterns(['token' => '[a-zA-Z0-9]+']);

        // Email verification route
        $builder->connect('/portal/verify-email/{token}', ['controller' => 'Patients', 'action' => 'verifyEmail'])
            ->setPass(['token'])
            ->setPatterns(['token' => '[a-zA-Z0-9]+']);

        // Patient appointments management
        $builder->connect('/portal/appointments', ['controller' => 'Patients', 'action' => 'appointments']);
        $builder->connect('/portal/appointments/cancel/{id}', ['controller' => 'Patients', 'action' => 'cancelAppointment'])
            ->setPass(['id'])
            ->setPatterns(['id' => '\d+'])
            ->setMethods(['POST']);

        // Patient profile
        $builder->connect('/portal/profile', ['controller' => 'Patients', 'action' => 'profile']);
        
        // Medical services routes
        $builder->connect('/services/emergency', ['controller' => 'Services', 'action' => 'emergency']);
        $builder->connect('/services/cardiology', ['controller' => 'Services', 'action' => 'cardiology']);
        $builder->connect('/services/pediatrics', ['controller' => 'Services', 'action' => 'pediatrics']);
        $builder->connect('/services/radiology', ['controller' => 'Services', 'action' => 'radiology']);
        
        // Doctor profile routes
        $builder->connect('/doctors/{id}', ['controller' => 'Doctors', 'action' => 'view'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
        
        // Appointment routes
        $builder->connect('/appointments/new', ['controller' => 'Appointments', 'action' => 'add']);
        $builder->connect('/appointments/{id}', ['controller' => 'Appointments', 'action' => 'view'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
        
        // News and updates
        $builder->connect('/news', ['controller' => 'News', 'action' => 'index']);
        $builder->connect('/news/{slug}', ['controller' => 'News', 'action' => 'view'])
                ->setPass(['slug']);
        
        // Patient information routes
        $builder->connect('/insurance', ['controller' => 'Pages', 'action' => 'display', 'insurance']);
        $builder->connect('/billing', ['controller' => 'Pages', 'action' => 'display', 'billing']);
        $builder->connect('/records', ['controller' => 'Pages', 'action' => 'display', 'records']);
        $builder->connect('/privacy-policy', ['controller' => 'Pages', 'action' => 'display', 'privacy']);
        $builder->connect('/terms-of-service', ['controller' => 'Pages', 'action' => 'display', 'terms']);
        
        // Emergency and urgent care
        $builder->connect('/emergency', ['controller' => 'Pages', 'action' => 'display', 'emergency']);
        $builder->connect('/urgent-care', ['controller' => 'Pages', 'action' => 'display', 'urgent_care']);
        
        // Careers and employment
        $builder->connect('/careers', ['controller' => 'Careers', 'action' => 'index']);
        $builder->connect('/careers/{id}', ['controller' => 'Careers', 'action' => 'view'])
                ->setPass(['id'])
                ->setPatterns(['id' => '\d+']);
        
        // Health resources
        $builder->connect('/health-library', ['controller' => 'HealthLibrary', 'action' => 'index']);
        $builder->connect('/health-library/{category}', ['controller' => 'HealthLibrary', 'action' => 'category'])
                ->setPass(['category']);
        
        // API routes for AJAX requests
        $builder->prefix('Api', function (RouteBuilder $routes) {
            // Appointment availability
            $routes->connect('/appointments/availability', 
                ['controller' => 'Appointments', 'action' => 'availability']);
            
            // Doctor search
            $routes->connect('/doctors/search', 
                ['controller' => 'Doctors', 'action' => 'search']);
            
            // Contact form submission
            $routes->connect('/contact/submit', 
                ['controller' => 'Contact', 'action' => 'submit'])
                ->setMethods(['POST']);
        });

        /*
         * Admin routes (for hospital staff)
         * URL path obscured for security
         */
        $builder->prefix('Admin', ['path' => '/smupa1881'], function (RouteBuilder $routes) {
            $routes->connect('/', ['controller' => 'Dashboard', 'action' => 'index']);
            $routes->fallbacks(DashedRoute::class);
        });

        // Dynamic pages route - must be before catchall routes
        $builder->connect('/{slug}', ['controller' => 'Pages', 'action' => 'page'])
                ->setPass(['slug'])
                ->setPatterns(['slug' => '[a-z0-9\-]+']);

        /*
         * Connect catchall routes for all controllers.
         */
        $builder->connect('/{controller}', ['action' => 'index']);
        $builder->connect('/{controller}/{action}/*', []);
    });

    /*
     * If you need a different set of middleware or none at all,
     * open new scope and define routes there.
     *
     * ```
     * $routes->scope('/api', function (RouteBuilder $builder) {
     *     // No $builder->applyMiddleware() here.
     *     
     *     // Parse specified extensions from URLs
     *     // $builder->setExtensions(['json', 'xml']);
     *     
     *     // Connect API actions here.
     * });
     * ```
     */
};