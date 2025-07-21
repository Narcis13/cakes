<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\AvailabilityService;
use Cake\Core\Configure;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\Date;
use Cake\I18n\Time;
// use Cake\Mailer\Mailer;
// use Cake\Mailer\MailerAwareTrait;
use Cake\Utility\Security;

/**
 * Appointments Controller
 *
 * @property \App\Model\Table\AppointmentsTable $Appointments
 * @property \App\Model\Table\StaffTable $Staff
 * @property \App\Model\Table\ServicesTable $Services
 */
class AppointmentsController extends AppController
{
    // use MailerAwareTrait;
    
    /**
     * @var \App\Service\AvailabilityService
     */
    private $availabilityService;
    
    /**
     * @var \App\Model\Table\StaffTable
     */
    private $Staff;
    
    /**
     * @var \App\Model\Table\ServicesTable
     */
    private $Services;

    /**
     * Initialize method
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        
        $this->Staff = $this->fetchTable('Staff');
        $this->Services = $this->fetchTable('Services');
        
        // Initialize AvailabilityService
        $this->availabilityService = new AvailabilityService();
        
        // Allow public access to all appointment actions
        $this->Authentication->allowUnauthenticated([
            'index',
            'checkAvailability',
            'getAvailableSlots',
            'book',
            'confirm',
            'success'
        ]);
        
        // Load AJAX routes
        $this->Authentication->allowUnauthenticated([
            'getDoctorsBySpecialty'
        ]);
    }

    /**
     * Index method - Display booking form
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        // Get all active specializations from doctors
        $specializations = $this->Staff->find()
            ->select(['specialization'])
            ->where([
                'is_active' => true,
                'specialization IS NOT' => null,
                'specialization !=' => '',
                'staff_type' => 'doctor'
            ])
            ->distinct(['specialization'])
            ->orderAsc('specialization')
            ->all()
            ->map(function ($staff) {
                return [
                    'value' => $staff->specialization,
                    'text' => $staff->specialization
                ];
            })
            ->toArray();

        // Get all active services
        $services = $this->Services->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])
        ->where(['is_active' => true])
        ->orderAsc('name')
        ->toArray();

        $this->set(compact('specializations', 'services'));
    }

    /**
     * Check availability - AJAX endpoint
     *
     * @return \Cake\Http\Response|null|void
     */
    public function checkAvailability()
    {
        $this->request->allowMethod(['post']);
        $this->viewBuilder()->setClassName('Json');

        $data = $this->request->getData();
        $specialty = $data['specialty'] ?? null;

        \Cake\Log\Log::debug('CheckAvailability called with specialty: ' . $specialty);

        if (!$specialty) {
            $this->set([
                'success' => false,
                'message' => 'Vă rugăm să selectați specializarea.'
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'message']);
            return;
        }

        try {
            // Get all doctors with this specialty, regardless of availability
            $doctors = $this->Staff->find()
                ->where([
                    'specialization' => $specialty,
                    'is_active' => true,
                    'staff_type' => 'doctor'
                ])
                ->toArray();

            $availableDoctors = [];
            foreach ($doctors as $doctor) {
                // Get services for this doctor from doctor_schedules
                $doctorSchedules = $this->fetchTable('DoctorSchedules')->find()
                    ->where([
                        'staff_id' => $doctor->id,
                        'is_active' => true
                    ])
                    ->select(['service_id'])
                    ->distinct(['service_id'])
                    ->toArray();
                
                $serviceIds = array_map(function($schedule) {
                    return $schedule->service_id;
                }, $doctorSchedules);
                
                $services = [];
                if (!empty($serviceIds)) {
                    // Get services that this doctor provides
                    $doctorServices = $this->Services->find()
                        ->where([
                            'id IN' => $serviceIds,
                            'is_active' => true
                        ])
                        ->toArray();
                    
                    foreach ($doctorServices as $service) {
                        $services[] = [
                            'id' => $service->id,
                            'name' => $service->name,
                            'duration_minutes' => $service->duration_minutes,
                            'price' => $service->price
                        ];
                    }
                } else {
                    // If no services linked through schedules, get all active services
                    $allServices = $this->Services->find()
                        ->where(['is_active' => true])
                        ->toArray();
                    foreach ($allServices as $service) {
                        $services[] = [
                            'id' => $service->id,
                            'name' => $service->name,
                            'duration_minutes' => $service->duration_minutes,
                            'price' => $service->price
                        ];
                    }
                }
                
                if (!empty($services)) {
                    $availableDoctors[] = [
                        'id' => $doctor->id,
                        'name' => $doctor->first_name . ' ' . $doctor->last_name,
                        'specialization' => $doctor->specialization,
                        'photo' => $doctor->photo,
                        'email' => $doctor->email,
                        'phone' => $doctor->phone,
                        'services' => $services
                    ];
                }
            }

            \Cake\Log\Log::debug('Found ' . count($availableDoctors) . ' available doctors');

            $this->set([
                'success' => true,
                'doctors' => $availableDoctors
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'doctors']);

        } catch (\Exception $e) {
            \Cake\Log\Log::error('Appointment availability error: ' . $e->getMessage());
            \Cake\Log\Log::error('Stack trace: ' . $e->getTraceAsString());
            
            $this->set([
                'success' => false,
                'message' => 'A apărut o eroare: ' . $e->getMessage()
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'message']);
        }
    }

    /**
     * Get available time slots - AJAX endpoint
     *
     * @return \Cake\Http\Response|null|void
     */
    public function getAvailableSlots()
    {
        $this->request->allowMethod(['post']);
        $this->viewBuilder()->setClassName('Json');

        $data = $this->request->getData();
        $doctorId = $data['doctor_id'] ?? null;
        $date = $data['date'] ?? null;
        $serviceId = $data['service_id'] ?? null;

        if (!$doctorId || !$date || !$serviceId) {
            $this->set([
                'success' => false,
                'message' => 'Date invalide.'
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'message']);
            return;
        }

        try {
            $appointmentDate = new Date($date);
            
            // Use AvailabilityService to get available slots
            $slots = $this->availabilityService->getAvailableSlots((int)$doctorId, $appointmentDate, (int)$serviceId);

            $this->set([
                'success' => true,
                'date' => $appointmentDate->format('Y-m-d'),
                'doctor_id' => $doctorId,
                'service_id' => $serviceId,
                'slots' => $slots
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'date', 'doctor_id', 'service_id', 'slots']);

        } catch (\Exception $e) {
            $this->set([
                'success' => false,
                'message' => 'A apărut o eroare la încărcarea sloturilor disponibile.'
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'message']);
        }
    }

    /**
     * Book appointment - Process booking
     *
     * @return \Cake\Http\Response|null|void
     */
    public function book()
    {
        $this->request->allowMethod(['post']);

        $appointment = $this->Appointments->newEmptyEntity();
        
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            
            // Debug log the received data
            \Cake\Log\Log::debug('Book appointment data received: ' . json_encode($data));
            
            // Validate required fields
            if (empty($data['doctor_id']) || empty($data['service_id']) || 
                empty($data['appointment_date']) || empty($data['appointment_time'])) {
                \Cake\Log\Log::error('Missing required fields. Data: ' . json_encode($data));
                $this->Flash->error('Vă rugăm să completați toate câmpurile obligatorii.');
                return $this->redirect(['action' => 'index']);
            }
            
            try {
                $appointmentDate = new Date($data['appointment_date']);
                // Ensure time format is HH:MM:SS
                $timeStr = $data['appointment_time'];
                if (strlen($timeStr) == 5) { // If format is HH:MM
                    $timeStr .= ':00'; // Add seconds
                }
                $appointmentTime = new Time($timeStr);
                
                // Check if slot is still available using AvailabilityService
                if (!$this->availabilityService->isSlotAvailable(
                    (int)$data['doctor_id'],
                    $appointmentDate,
                    $appointmentTime,
                    (int)$data['service_id']
                )) {
                    $this->Flash->error('Acest slot nu mai este disponibil. Vă rugăm să alegeți altul.');
                    return $this->redirect(['action' => 'index']);
                }
                
                // Calculate end time based on service duration
                $service = $this->Services->get($data['service_id']);
                $data['end_time'] = $this->availabilityService->calculateEndTime($appointmentTime, $service->duration_minutes);
                
                // Generate confirmation token
                $data['confirmation_token'] = Security::randomString(64);
                $data['status'] = 'pending';
                
                $appointment = $this->Appointments->patchEntity($appointment, $data);
            } catch (\Exception $e) {
                $this->Flash->error('Date invalide. Vă rugăm să încercați din nou.');
                return $this->redirect(['action' => 'index']);
            }

            if ($this->Appointments->save($appointment)) {
                // Send confirmation email - disabled temporarily
                /*
                try {
                    $this->getMailer('Appointment')->send('confirmationEmail', [$appointment]);
                    $this->Flash->success('Programarea a fost creată cu succes. Vă rugăm să verificați emailul pentru confirmare.');
                } catch (\Exception $e) {
                    \Cake\Log\Log::error('Email error: ' . $e->getMessage());
                    $this->Flash->warning('Programarea a fost creată, dar emailul de confirmare nu a putut fi trimis.');
                }
                */
                
                $this->Flash->success('Programarea a fost creată cu succes!');
                
                // Check if it's an AJAX request
                if ($this->request->is('ajax')) {
                    $this->viewBuilder()->setClassName('Json');
                    $this->set([
                        'success' => true,
                        'message' => 'Programarea a fost creată cu succes!',
                        'redirect' => $this->Url->build(['action' => 'success', $appointment->id])
                    ]);
                    $this->viewBuilder()->setOption('serialize', ['success', 'message', 'redirect']);
                    return;
                }
                
                return $this->redirect(['action' => 'success', $appointment->id]);
            } else {
                // Log validation errors
                \Cake\Log\Log::error('Appointment save failed. Errors: ' . json_encode($appointment->getErrors()));
                $this->Flash->error('Programarea nu a putut fi salvată. Vă rugăm să încercați din nou.');
                
                // For AJAX requests, return JSON error response
                if ($this->request->is('ajax')) {
                    $this->viewBuilder()->setClassName('Json');
                    $this->set([
                        'success' => false,
                        'message' => 'Programarea nu a putut fi salvată. Vă rugăm să încercați din nou.',
                        'errors' => $appointment->getErrors()
                    ]);
                    $this->viewBuilder()->setOption('serialize', ['success', 'message', 'errors']);
                    return;
                }
            }
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Confirm appointment via email token
     *
     * @param string|null $token Confirmation token
     * @return \Cake\Http\Response|null|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found
     */
    public function confirm($token = null)
    {
        if (!$token) {
            throw new NotFoundException('Token invalid.');
        }

        $appointment = $this->Appointments->find()
            ->where(['confirmation_token' => $token])
            ->contain(['Staff', 'Services'])
            ->first();

        if (!$appointment) {
            $this->Flash->error('Token de confirmare invalid sau expirat.');
            return $this->redirect(['action' => 'index']);
        }

        // Check if already confirmed
        if ($appointment->status === 'confirmed') {
            $this->Flash->info('Această programare a fost deja confirmată.');
            return $this->redirect(['action' => 'success', $appointment->id]);
        }

        // Check token expiry (24 hours)
        $tokenAge = Time::now()->diffInHours($appointment->created);
        if ($tokenAge > 24) {
            $this->Flash->error('Token de confirmare expirat. Vă rugăm să faceți o nouă programare.');
            return $this->redirect(['action' => 'index']);
        }

        // Update appointment status
        $appointment->status = 'confirmed';
        $appointment->confirmed_at = Time::now();

        if ($this->Appointments->save($appointment)) {
            // Send confirmation email - disabled temporarily
            /*
            try {
                $this->getMailer('Appointment')->send('confirmedEmail', [$appointment]);
            } catch (\Exception $e) {
                // Log error but don't show to user
            }
            */

            $this->Flash->success('Programarea a fost confirmată cu succes!');
            return $this->redirect(['action' => 'success', $appointment->id]);
        } else {
            $this->Flash->error('Nu am putut confirma programarea. Vă rugăm să încercați din nou.');
        }

        $this->set(compact('appointment'));
    }

    /**
     * Success page - Booking confirmation
     *
     * @param string|null $id Appointment id
     * @return \Cake\Http\Response|null|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found
     */
    public function success($id = null)
    {
        $appointment = $this->Appointments->get($id, [
            'contain' => ['Staff', 'Services']
        ]);

        $this->set(compact('appointment'));
    }
}