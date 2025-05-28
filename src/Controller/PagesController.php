<?php
declare(strict_types=1);

/**
 * CakePHP Hospital Pages Controller
 * File: src/Controller/PagesController.php
 */

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\View\Exception\MissingTemplateException;
use Cake\Mailer\Mailer;
use Cake\Validation\Validator;

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
     * Handle contact form submission
     *
     * @return \Cake\Http\Response
     */
    private function handleContactForm(): Response
    {
        $data = $this->request->getData();
        
        // Validate form data
        $validator = new Validator();
        $validator
            ->requirePresence('first_name', 'create')
            ->notEmptyString('first_name', 'First name is required')
            ->maxLength('first_name', 50, 'First name must be less than 50 characters')
            
            ->requirePresence('last_name', 'create')
            ->notEmptyString('last_name', 'Last name is required')
            ->maxLength('last_name', 50, 'Last name must be less than 50 characters')
            
            ->requirePresence('email', 'create')
            ->email('email', false, 'Please enter a valid email address')
            ->notEmptyString('email', 'Email address is required')
            
            ->allowEmptyString('phone')
            ->add('phone', 'custom', [
                'rule' => function ($value) {
                    if (empty($value)) return true;
                    return preg_match('/^[\+]?[1-9][\d]{0,15}$/', preg_replace('/[\s\-\(\)]/', '', $value));
                },
                'message' => 'Please enter a valid phone number'
            ])
            
            ->requirePresence('subject', 'create')
            ->notEmptyString('subject', 'Please select a subject')
            ->inList('subject', [
                'general', 'appointment', 'billing', 'medical_records', 
                'insurance', 'complaint', 'compliment', 'other'
            ], 'Invalid subject selected')
            
            ->requirePresence('message', 'create')
            ->notEmptyString('message', 'Message is required')
            ->minLength('message', 10, 'Message must be at least 10 characters')
            ->maxLength('message', 1000, 'Message must be less than 1000 characters')
            
            ->requirePresence('privacy_consent', 'create')
            ->boolean('privacy_consent', 'You must consent to our privacy policy');

        $errors = $validator->validate($data);
        
        if (!empty($errors)) {
            $this->Flash->error('Please correct the errors below.');
            $this->set(compact('data', 'errors'));
            return $this->render('contact');
        }

        // Process the form - send email
        try {
            $this->sendContactEmail($data);
            $this->Flash->success('Thank you for contacting us! We will get back to you within 24 hours.');
            return $this->redirect(['action' => 'display', 'contact']);
        } catch (\Exception $e) {
            $this->Flash->error('Sorry, there was an error sending your message. Please try again or call us directly.');
            $this->set(compact('data'));
            return $this->render('contact');
        }
    }

    /**
     * Send contact form email
     *
     * @param array $data Form data
     * @return void
     */
    private function sendContactEmail(array $data): void
    {
        $mailer = new Mailer('default');
        
        // Email to hospital
        $mailer
            ->setTo('info@citygeneralhospital.com')
            ->setFrom(['noreply@citygeneralhospital.com' => 'City General Hospital Website'])
            ->setReplyTo($data['email'], $data['first_name'] . ' ' . $data['last_name'])
            ->setSubject('Contact Form Submission: ' . ucfirst(str_replace('_', ' ', $data['subject'])))
            ->setEmailFormat('both')
            ->setViewVars([
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? 'Not provided',
                'subject' => ucfirst(str_replace('_', ' ', $data['subject'])),
                'message' => $data['message'],
                'submitted_at' => date('Y-m-d H:i:s')
            ])
            ->viewBuilder()
            ->setTemplate('contact_notification')
            ->setLayout('email');
            
        $mailer->deliver();

        // Confirmation email to sender
        $confirmationMailer = new Mailer('default');
        $confirmationMailer
            ->setTo($data['email'], $data['first_name'] . ' ' . $data['last_name'])
            ->setFrom(['noreply@citygeneralhospital.com' => 'City General Hospital'])
            ->setSubject('We received your message - City General Hospital')
            ->setEmailFormat('both')
            ->setViewVars([
                'name' => $data['first_name'],
                'subject' => ucfirst(str_replace('_', ' ', $data['subject']))
            ])
            ->viewBuilder()
            ->setTemplate('contact_confirmation')
            ->setLayout('email');
            
        $confirmationMailer->deliver();
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
                    'keywords' => 'hospital, healthcare, emergency, medical services, doctors, appointments'
                ]);
                break;
                
            case 'contact':
                $this->set([
                    'title' => 'Contact Us - City General Hospital',
                    'description' => 'Get in touch with City General Hospital. Contact information, directions, and online contact form. 24/7 emergency services available.',
                    'keywords' => 'contact, hospital, emergency, phone, address, directions'
                ]);
                break;
                
            case 'about':
                $this->set([
                    'title' => 'About Us - City General Hospital',
                    'description' => 'Learn about City General Hospital - our history, mission, values, and commitment to providing exceptional healthcare to our community.',
                    'keywords' => 'about, hospital history, mission, values, healthcare'
                ]);
                break;
                
            case 'services':
                $this->set([
                    'title' => 'Medical Services - City General Hospital',
                    'description' => 'Comprehensive medical services including emergency care, cardiology, pediatrics, surgery, imaging, and specialized treatments.',
                    'keywords' => 'medical services, emergency, cardiology, pediatrics, surgery, imaging'
                ]);
                break;
                
            case 'emergency':
                $this->set([
                    'title' => 'Emergency Services - City General Hospital',
                    'description' => '24/7 Emergency Department with advanced life support, trauma care, and rapid response team. Call 911 for emergencies.',
                    'keywords' => 'emergency, 911, trauma, urgent care, emergency room'
                ]);
                break;
                
            default:
                $this->set([
                    'title' => 'City General Hospital',
                    'description' => 'City General Hospital - Your trusted healthcare provider',
                    'keywords' => 'hospital, healthcare, medical'
                ]);
        }
    }

    /**
     * Before render callback
     *
     * @param \Cake\Event\EventInterface $event Event
     * @return \Cake\Http\Response|null
     */
    public function beforeRender(\Cake\Event\EventInterface $event): ?Response
    {
        parent::beforeRender($event);
        
        // Set global variables for all pages
        $this->set([
            'cakeDescription' => 'City General Hospital',
            'hospitalName' => 'City General Hospital',
            'hospitalPhone' => '(555) 123-4567',
            'emergencyPhone' => '911',
            'hospitalAddress' => '123 Medical Center Drive, Healthcare City, HC 12345'
        ]);
        
        return null;
    }
}