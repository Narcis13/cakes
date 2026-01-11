<?php
declare(strict_types=1);

namespace App\Controller;

use App\Mailer\AppointmentMailer;
use App\Model\Table\ServicesTable;
use App\Model\Table\StaffTable;
use App\Service\AvailabilityService;
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\Date;
use Cake\I18n\Time;
use Cake\Log\Log;
use Cake\Routing\Router;
use Cake\Utility\Security;
use Exception;

/**
 * Appointments Controller
 *
 * @property \App\Model\Table\AppointmentsTable $Appointments
 * @property \App\Model\Table\StaffTable $Staff
 * @property \App\Model\Table\ServicesTable $Services
 */
class AppointmentsController extends AppController
{
    /**
     * @var \App\Service\AvailabilityService
     */
    private AvailabilityService $availabilityService;

    /**
     * @var \App\Model\Table\StaffTable
     */
    private StaffTable $Staff;

    /**
     * @var \App\Model\Table\ServicesTable
     */
    private ServicesTable $Services;

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
            'success',
            'generateCaptcha',
        ]);

        // Load AJAX routes
        $this->Authentication->allowUnauthenticated([
            'getDoctorsBySpecialty',
        ]);
    }

    /**
     * Index method - Display booking form
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        // Get all active specializations
        $specializations = $this->fetchTable('Specializations')->find()
            ->select(['id', 'name'])
            ->where(['is_active' => true])
            ->orderAsc('name')
            ->all()
            ->map(function ($spec) {
                return [
                    'value' => $spec->name,
                    'text' => $spec->name,
                ];
            })
            ->toArray();

        // Get all active services
        $services = $this->Services->find('list', [
            'keyField' => 'id',
            'valueField' => 'name',
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

        Log::debug('CheckAvailability called with specialty: ' . $specialty);

        if (!$specialty) {
            $this->set([
                'success' => false,
                'message' => 'Vă rugăm să selectați specializarea.',
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'message']);

            return;
        }

        try {
            // First get the specialization ID
            $specialization = $this->fetchTable('Specializations')->find()
                ->where(['name' => $specialty])
                ->first();

            if (!$specialization) {
                $this->set([
                    'success' => false,
                    'message' => 'Specializarea selectată nu există.',
                ]);
                $this->viewBuilder()->setOption('serialize', ['success', 'message']);

                return;
            }

            // Get all doctors with this specialty, regardless of availability
            $doctors = $this->Staff->find()
                ->contain(['Specializations'])
                ->where([
                    'specialization_id' => $specialization->id,
                    'Staff.is_active' => true,
                    'staff_type' => 'doctor',
                ])
                ->toArray();

            $availableDoctors = [];
            foreach ($doctors as $doctor) {
                // Get services for this doctor from doctor_schedules
                $doctorSchedules = $this->fetchTable('DoctorSchedules')->find()
                    ->where([
                        'staff_id' => $doctor->id,
                        'is_active' => true,
                    ])
                    ->select(['service_id'])
                    ->distinct(['service_id'])
                    ->toArray();

                $serviceIds = array_map(function ($schedule) {
                    return $schedule->service_id;
                }, $doctorSchedules);

                $services = [];
                if (!empty($serviceIds)) {
                    // Get services that this doctor provides
                    $doctorServices = $this->Services->find()
                        ->where([
                            'id IN' => $serviceIds,
                            'is_active' => true,
                        ])
                        ->toArray();

                    foreach ($doctorServices as $service) {
                        $services[] = [
                            'id' => $service->id,
                            'name' => $service->name,
                            'duration_minutes' => $service->duration_minutes,
                            'price' => $service->price,
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
                            'price' => $service->price,
                        ];
                    }
                }

                if (!empty($services)) {
                    // SECURITY: Only expose public-safe doctor information
                    // Do NOT include email or phone in public API responses
                    $availableDoctors[] = [
                        'id' => $doctor->id,
                        'name' => $doctor->first_name . ' ' . $doctor->last_name,
                        'specialization' => $doctor->specialization,
                        'photo' => $doctor->photo,
                        'services' => $services,
                    ];
                }
            }

            Log::debug('Found ' . count($availableDoctors) . ' available doctors');

            $this->set([
                'success' => true,
                'doctors' => $availableDoctors,
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'doctors']);
        } catch (Exception $e) {
            Log::error('Appointment availability error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            $this->set([
                'success' => false,
                'message' => 'A apărut o eroare: ' . $e->getMessage(),
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
                'message' => 'Date invalide.',
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
                'slots' => $slots,
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'date', 'doctor_id', 'service_id', 'slots']);
        } catch (Exception $e) {
            $this->set([
                'success' => false,
                'message' => 'A apărut o eroare la încărcarea sloturilor disponibile.',
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

        // Rate limiting check
        if (!$this->checkRateLimit()) {
            $this->Flash->error('Prea multe încercări de programare. Vă rugăm să încercați din nou mai târziu.');

            if ($this->request->is('ajax')) {
                $this->viewBuilder()->setClassName('Json');
                $this->set([
                    'success' => false,
                    'message' => 'Prea multe încercări de programare. Vă rugăm să încercați din nou mai târziu.',
                ]);
                $this->viewBuilder()->setOption('serialize', ['success', 'message']);

                return;
            }

            return $this->redirect(['action' => 'index']);
        }

        $appointment = $this->Appointments->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            // Debug log (without PII)
            Log::debug('Book appointment request received');

            // Validate required fields
            if (
                empty($data['doctor_id']) || empty($data['service_id']) ||
                empty($data['appointment_date']) || empty($data['appointment_time'])
            ) {
                Log::error('Missing required fields in appointment booking');
                $this->Flash->error('Vă rugăm să completați toate câmpurile obligatorii.');

                return $this->redirect(['action' => 'index']);
            }

            // Validate CAPTCHA
            if (!$this->validateCaptcha($data['captcha_answer'] ?? '')) {
                $this->Flash->error('Răspunsul la întrebarea de securitate este incorect.');

                if ($this->request->is('ajax')) {
                    $this->viewBuilder()->setClassName('Json');
                    $this->set([
                        'success' => false,
                        'message' => 'Răspunsul la întrebarea de securitate este incorect.',
                    ]);
                    $this->viewBuilder()->setOption('serialize', ['success', 'message']);

                    return;
                }

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
                if (
                    !$this->availabilityService->isSlotAvailable(
                        (int)$data['doctor_id'],
                        $appointmentDate,
                        $appointmentTime,
                        (int)$data['service_id'],
                    )
                ) {
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
            } catch (Exception $e) {
                $this->Flash->error('Date invalide. Vă rugăm să încercați din nou.');

                return $this->redirect(['action' => 'index']);
            }

            if ($this->Appointments->save($appointment)) {
                // SECURITY: Store appointment ID in session for success page access
                // This prevents ID enumeration attacks on the success page
                $this->request->getSession()->write('last_booked_appointment_id', $appointment->id);

                // Send confirmation email
                try {
                    $mailer = new AppointmentMailer();
                    $mailer->confirmation($appointment, $appointment->confirmation_token)->send();

                    $this->Flash->success('Programarea a fost creată cu succes. Vă rugăm să verificați emailul pentru confirmare.');
                } catch (Exception $e) {
                    Log::error('Email error: ' . $e->getMessage());
                    $this->Flash->warning('Programarea a fost creată, dar emailul de confirmare nu a putut fi trimis.');
                }

                // Send admin notification email
                try {
                    // Reload appointment with associations for admin email
                    $appointmentForAdmin = $this->Appointments->get($appointment->id, [
                        'contain' => [
                            'Doctors' => ['Departments'],
                            'Services',
                        ],
                    ]);

                    $adminMailer = new AppointmentMailer();
                    $adminMailer->adminNotification($appointmentForAdmin)->send();
                } catch (Exception $e) {
                    Log::error('Admin notification email error: ' . $e->getMessage());
                    // Don't show error to user as this is internal
                }

                // Log successful save for debugging
                Log::debug('Appointment saved successfully with ID: ' . $appointment->id);

                $this->Flash->success('Programarea a fost creată cu succes!');

                // Always return JSON for AJAX requests with proper redirect URL
                if ($this->request->is('ajax') || $this->request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest') {
                    Log::debug('AJAX request detected, returning JSON response');
                    $this->viewBuilder()->setClassName('Json');
                    $this->set([
                        'success' => true,
                        'message' => 'Programarea a fost creată cu succes!',
                        'redirect' => Router::url(['controller' => 'Appointments', 'action' => 'success', $appointment->id], true),
                        'appointment_id' => $appointment->id,
                    ]);
                    $this->viewBuilder()->setOption('serialize', ['success', 'message', 'redirect', 'appointment_id']);

                    return;
                }

                Log::debug('Non-AJAX request, redirecting to success page');

                return $this->redirect(['action' => 'success', $appointment->id]);
            } else {
                // Log validation errors (without patient PII)
                Log::error('Appointment save failed - validation errors occurred');
                $this->Flash->error('Programarea nu a putut fi salvată. Vă rugăm să încercați din nou.');

                // For AJAX requests, return JSON error response
                if ($this->request->is('ajax') || $this->request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest') {
                    $this->viewBuilder()->setClassName('Json');
                    $this->set([
                        'success' => false,
                        'message' => 'Programarea nu a putut fi salvată. Vă rugăm să încercați din nou.',
                        'errors' => $appointment->getErrors(),
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
    public function confirm(?string $token = null)
    {
        if (!$token) {
            throw new NotFoundException('Token invalid.');
        }

        $appointment = $this->Appointments->find()
            ->where(['confirmation_token' => $token])
            ->contain([
                'Doctors' => ['Departments'],
                'Services',
            ])
            ->first();

        if (!$appointment) {
            $this->Flash->error('Token de confirmare invalid sau expirat.');

            return $this->redirect(['action' => 'index']);
        }

        // Check if already confirmed
        if ($appointment->status === 'confirmed') {
            $this->Flash->info('Această programare a fost deja confirmată.');

            // SECURITY: Include token in redirect for authorization
            return $this->redirect([
                'action' => 'success',
                $appointment->id,
                '?' => ['token' => $token],
            ]);
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
            // Send confirmed email
            try {
                $mailer = new AppointmentMailer();
                $mailer->confirmed($appointment)->send();
            } catch (Exception $e) {
                Log::error('Confirmed email error: ' . $e->getMessage());
            }

            $this->Flash->success('Programarea a fost confirmată cu succes!');

            // SECURITY: Include token in redirect for authorization
            return $this->redirect([
                'action' => 'success',
                $appointment->id,
                '?' => ['token' => $token],
            ]);
        } else {
            $this->Flash->error('Nu am putut confirma programarea. Vă rugăm să încercați din nou.');
        }

        $this->set(compact('appointment'));
    }

    /**
     * Success page - Booking confirmation
     *
     * SECURITY: Protected against ID enumeration
     * Access is only allowed if:
     * 1. User just booked this appointment (session contains appointment ID), OR
     * 2. User has a valid confirmation token (from email link)
     *
     * @param string|null $id Appointment id
     * @return \Cake\Http\Response|null|void
     * @throws \Cake\Http\Exception\ForbiddenException When access is denied
     */
    public function success(?string $id = null)
    {
        if (!$id) {
            throw new ForbiddenException('Access denied');
        }

        $session = $this->request->getSession();
        $validAppointmentId = $session->read('last_booked_appointment_id');
        $token = $this->request->getQuery('token');

        // Allow access if: 1) just booked (session), or 2) has valid token
        if ($id === (string)$validAppointmentId) {
            // Access via session (just booked)
            $appointment = $this->Appointments->get($id, [
                'contain' => [
                    'Doctors' => ['Departments'],
                    'Services',
                ],
            ]);
            // Clear session after first access
            $session->delete('last_booked_appointment_id');
        } elseif ($token) {
            // Access via confirmation token (from email)
            $appointment = $this->Appointments->find()
                ->where([
                    'id' => $id,
                    'confirmation_token' => $token,
                ])
                ->contain([
                    'Doctors' => ['Departments'],
                    'Services',
                ])
                ->first();

            if (!$appointment) {
                throw new ForbiddenException('Access denied');
            }
        } else {
            // No valid session or token - access denied
            throw new ForbiddenException('Access denied');
        }

        $this->set(compact('appointment'));
    }

    /**
     * Generate CAPTCHA question and store answer in session
     *
     * @return array
     */
    public function generateCaptcha(): array
    {
        $this->request->allowMethod(['post']);
        $this->viewBuilder()->setClassName('Json');

        $num1 = rand(1, 10);
        $num2 = rand(1, 10);
        $operation = rand(0, 1) ? '+' : '-';

        if ($operation === '-' && $num1 < $num2) {
            // Ensure positive result
            $temp = $num1;
            $num1 = $num2;
            $num2 = $temp;
        }

        $question = "$num1 $operation $num2 = ?";
        $answer = $operation === '+' ? $num1 + $num2 : $num1 - $num2;

        // Store answer in session
        $session = $this->request->getSession();
        $session->write('captcha_answer', $answer);

        $this->set([
            'success' => true,
            'question' => $question,
        ]);
        $this->viewBuilder()->setOption('serialize', ['success', 'question']);

        return [
            'question' => $question,
            'answer' => $answer,
        ];
    }

    /**
     * Validate CAPTCHA answer
     *
     * @param string $userAnswer
     * @return bool
     */
    private function validateCaptcha(string $userAnswer): bool
    {
        $session = $this->request->getSession();
        $correctAnswer = $session->read('captcha_answer');

        if ($correctAnswer === null) {
            return false;
        }

        // Clean up session after validation
        $session->delete('captcha_answer');

        return (int)$userAnswer === (int)$correctAnswer;
    }

    /**
     * Check rate limiting for appointment booking
     *
     * @return bool
     */
    private function checkRateLimit(): bool
    {
        $rateLimitConfig = Configure::read('Appointments.rate_limit');
        $maxAttempts = $rateLimitConfig['attempts'] ?? 10;
        $timeWindow = $rateLimitConfig['window'] ?? 3600; // 1 hour

        $clientIp = $this->request->clientIp();
        $cacheKey = 'appointment_rate_limit_' . md5($clientIp);

        // Get current attempt count from cache
        $attempts = Cache::read($cacheKey, 'default');

        if ($attempts === false) {
            // First attempt, initialize counter
            Cache::write($cacheKey, 1, $timeWindow);

            return true;
        }

        if ($attempts >= $maxAttempts) {
            // Rate limit exceeded
            return false;
        }

        // Increment counter
        Cache::write($cacheKey, $attempts + 1, $timeWindow);

        return true;
    }
}
