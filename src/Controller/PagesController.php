<?php
declare(strict_types=1);

/**
 * CakePHP Hospital Pages Controller
 * File: src/Controller/PagesController.php
 */

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\EventInterface;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\View\Exception\MissingTemplateException;

/**
 * Static content controller
 *
 * This controller will render views from templates/Pages/
 */
class PagesController extends AppController
{
    /**
     * Displays a view
     *
     * @param string ...$path Path segments.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Http\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\Http\Exception\NotFoundException When the view file could not be found.
     * @throws \Cake\View\Exception\MissingTemplateException In debug mode.
     */
    public function display(string ...$path): ?Response
    {
        if (!$path) {
            return $this->redirect('/');
        }

        if (in_array('..', $path, true) || in_array('.', $path, true)) {
            throw new ForbiddenException();
        }

        $page = $count = null;
        if (!empty($path[0])) {
            $page = $path[0];
        }
        if (!empty($path[1])) {
            $count = $path[1];
        }

        // Set page-specific variables
        $this->setPageVariables($page);

        $this->set(compact('page', 'count'));

        try {
            return $this->render(implode('/', $path));
        } catch (MissingTemplateException $exception) {
            if (Configure::read('debug')) {
                throw $exception;
            }
            throw new NotFoundException();
        }
    }

    /**
     * Contact form handler
     *
     * @return \Cake\Http\Response|null
     */
    public function contact(): ?Response
    {
        if ($this->request->is('post')) {
            return $this->handleContactForm();
        }

        // GET request - display contact form
        $this->set('title', 'Contact Us');

        return $this->render('contact');
    }

    /**
     * Contact form page
     *
     * @return \Cake\Http\Response|null
     */
    public function contactForm(): ?Response
    {
        if ($this->request->is('post')) {
            return $this->handleContactForm();
        }

        // GET request - display contact form
        $this->setPageVariables('contact_form');
        $contactInfo = $this->getContactInfo();
        $this->set(compact('contactInfo'));

        return $this->render('contact_form');
    }

    /**
     * Handle contact form submission
     *
     * @return \Cake\Http\Response
     */
    private function handleContactForm(): Response
    {
        $contactMessagesTable = $this->fetchTable('ContactMessages');
        $contactMessage = $contactMessagesTable->newEmptyEntity();

        $data = $this->request->getData();
        $contactMessage = $contactMessagesTable->patchEntity($contactMessage, $data);

        if ($contactMessagesTable->save($contactMessage)) {
            // Set success flag and redirect to show success message
            $this->set([
                'success' => true,
                'message' => 'Mesajul dumneavoastră a fost salvat cu succes! Vă mulțumim că ne-ați contactat. Vă vom răspunde în cel mai scurt timp.'
            ]);
            $contactInfo = $this->getContactInfo();
            $this->set(compact('contactInfo'));
            return $this->render('contact_form');
        } else {
            $this->Flash->error('A apărut o eroare la trimiterea mesajului. Vă rugăm să încercați din nou.');
            $contactInfo = $this->getContactInfo();
            $this->set(compact('contactMessage', 'contactInfo'));

            return $this->render('contact_form');
        }
    }

    /**
     * Get contact information from settings
     *
     * @return array
     */
    private function getContactInfo(): array
    {
        $settingsTable = $this->fetchTable('Settings');
        
        $contactEmail = $settingsTable->find()
            ->where(['key_name' => 'contact_email'])
            ->first();
            
        $contactPhone = $settingsTable->find()
            ->where(['key_name' => 'contact_phone'])
            ->first();
            
        return [
            'address' => 'Arges, Pitesti, Str. Negru Voda nr 47',
            'email' => $contactEmail ? $contactEmail->value : 'info@example.com',
            'phone' => $contactPhone ? $contactPhone->value : '+40 123 456 789',
        ];
    }

    /**
     * Set page-specific variables and metadata
     *
     * @param string|null $page Page name
     * @return void
     */
    private function setPageVariables(?string $page): void
    {
        switch ($page) {
            case 'index':
                $this->set([
                    'title' => 'Welcome to City General Hospital',
                    'description' => 'Excellence in healthcare with compassionate care for over 50 years. 24/7 emergency services, specialist care, and comprehensive medical services.',
                    'keywords' => 'hospital, healthcare, emergency, medical services, doctors, appointments',
                ]);
                break;

            case 'contact':
                $this->set([
                    'title' => 'Contact Us - City General Hospital',
                    'description' => 'Get in touch with City General Hospital. Contact information, directions, and online contact form. 24/7 emergency services available.',
                    'keywords' => 'contact, hospital, emergency, phone, address, directions',
                ]);
                break;

            case 'contact_form':
                $this->set([
                    'title' => 'Formular de Contact - City General Hospital',
                    'description' => 'Completați formularul de contact pentru a ne trimite un mesaj. Vă vom răspunde în cel mai scurt timp.',
                    'keywords' => 'formular, contact, mesaj, spital, comunicare',
                ]);
                break;

            case 'about':
                $this->set([
                    'title' => 'About Us - City General Hospital',
                    'description' => 'Learn about City General Hospital - our history, mission, values, and commitment to providing exceptional healthcare to our community.',
                    'keywords' => 'about, hospital history, mission, values, healthcare',
                ]);
                break;

            case 'services':
                $this->set([
                    'title' => 'Medical Services - City General Hospital',
                    'description' => 'Comprehensive medical services including emergency care, cardiology, pediatrics, surgery, imaging, and specialized treatments.',
                    'keywords' => 'medical services, emergency, cardiology, pediatrics, surgery, imaging',
                ]);
                break;

            case 'emergency':
                $this->set([
                    'title' => 'Emergency Services - City General Hospital',
                    'description' => '24/7 Emergency Department with advanced life support, trauma care, and rapid response team. Call 911 for emergencies.',
                    'keywords' => 'emergency, 911, trauma, urgent care, emergency room',
                ]);
                break;

            default:
                $this->set([
                    'title' => 'City General Hospital',
                    'description' => 'City General Hospital - Your trusted healthcare provider',
                    'keywords' => 'hospital, healthcare, medical',
                ]);
        }
    }

    /**
     * Before render callback
     *
     * @param \Cake\Event\EventInterface $event Event
     * @return \Cake\Http\Response|null
     */
    public function beforeRender(EventInterface $event): ?Response
    {
        parent::beforeRender($event);

        // Set global variables for all pages
        $this->set([
            'cakeDescription' => 'City General Hospital',
            'hospitalName' => 'City General Hospital',
            'hospitalPhone' => '(555) 123-4567',
            'emergencyPhone' => '911',
            'hospitalAddress' => '123 Medical Center Drive, Healthcare City, HC 12345',
        ]);

        return null;
    }

    /**
     * Display a dynamic page by slug
     *
     * @param string $slug Page slug.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Http\Exception\NotFoundException When the page could not be found.
     */
    public function page(string $slug): ?Response
    {
        $PagesTable = $this->fetchTable('Pages');

        try {
            $page = $PagesTable->find()
                ->where([
                    'Pages.slug' => $slug,
                    'Pages.is_published' => true,
                ])
                ->contain(['PageComponents' => function ($q) {
                    return $q->where(['PageComponents.is_active' => true])
                             ->order(['PageComponents.sort_order' => 'ASC']);
                }])
                ->firstOrFail();
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException('Page not found');
        }

        $this->set(compact('page'));
        $this->set([
            'title' => $page->title,
            'description' => $page->meta_description ?: substr(strip_tags($page->content), 0, 160),
            'keywords' => 'hospital, healthcare, medical',
        ]);

        // Use custom template if specified
        if ($page->template) {
            $this->render($page->template);
        } else {
            $this->render('page');
        }

        return null;
    }
}
