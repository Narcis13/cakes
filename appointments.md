# Appointment Booking System - Technical Specifications

## 1. Database Schema Specifications

### 1.1 doctor_schedules Table
```sql
CREATE TABLE doctor_schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    staff_id INT NOT NULL,
    day_of_week TINYINT NOT NULL CHECK (day_of_week BETWEEN 1 AND 7),
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    service_id INT NOT NULL,
    max_appointments INT DEFAULT 1,
    slot_duration INT DEFAULT NULL COMMENT 'Override service duration if needed',
    buffer_minutes INT DEFAULT 0 COMMENT 'Minutes between appointments',
    is_active BOOLEAN DEFAULT TRUE,
    created DATETIME NOT NULL,
    modified DATETIME NOT NULL,
    FOREIGN KEY (staff_id) REFERENCES staff(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
    UNIQUE KEY unique_schedule (staff_id, day_of_week, start_time, service_id),
    INDEX idx_active_schedules (is_active, day_of_week),
    INDEX idx_staff_schedules (staff_id, is_active)
);
```

### 1.2 schedule_exceptions Table
```sql
CREATE TABLE schedule_exceptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    staff_id INT NOT NULL,
    exception_date DATE NOT NULL,
    is_working BOOLEAN DEFAULT FALSE,
    start_time TIME DEFAULT NULL,
    end_time TIME DEFAULT NULL,
    reason VARCHAR(255) DEFAULT NULL,
    created DATETIME NOT NULL,
    modified DATETIME NOT NULL,
    FOREIGN KEY (staff_id) REFERENCES staff(id) ON DELETE CASCADE,
    UNIQUE KEY unique_exception (staff_id, exception_date),
    INDEX idx_exceptions_date (exception_date),
    INDEX idx_staff_exceptions (staff_id, exception_date)
);
```

### 1.3 Appointments Table Updates
```sql
ALTER TABLE appointments 
ADD COLUMN appointment_time TIME NOT NULL AFTER appointment_date,
ADD COLUMN end_time TIME NOT NULL AFTER appointment_time,
ADD COLUMN confirmation_token VARCHAR(64) DEFAULT NULL,
ADD COLUMN confirmed_at DATETIME DEFAULT NULL,
ADD COLUMN cancelled_at DATETIME DEFAULT NULL,
ADD COLUMN cancellation_reason VARCHAR(255) DEFAULT NULL,
ADD INDEX idx_confirmation (confirmation_token),
ADD INDEX idx_appointment_datetime (appointment_date, appointment_time),
ADD INDEX idx_doctor_appointments (doctor_id, appointment_date, status);
```

### 1.4 waiting_list Table (Phase 5)
```sql
CREATE TABLE waiting_list (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_name VARCHAR(100) NOT NULL,
    patient_email VARCHAR(100) NOT NULL,
    patient_phone VARCHAR(20) NOT NULL,
    service_id INT NOT NULL,
    preferred_doctor_id INT DEFAULT NULL,
    preferred_date_from DATE NOT NULL,
    preferred_date_to DATE NOT NULL,
    preferred_time_from TIME DEFAULT NULL,
    preferred_time_to TIME DEFAULT NULL,
    priority INT DEFAULT 0,
    status ENUM('waiting', 'notified', 'booked', 'expired') DEFAULT 'waiting',
    notified_at DATETIME DEFAULT NULL,
    expires_at DATETIME NOT NULL,
    created DATETIME NOT NULL,
    modified DATETIME NOT NULL,
    FOREIGN KEY (service_id) REFERENCES services(id),
    FOREIGN KEY (preferred_doctor_id) REFERENCES staff(id),
    INDEX idx_waiting_status (status, expires_at),
    INDEX idx_patient_lookup (patient_email, patient_phone)
);
```

## 2. Model Specifications

### 2.1 DoctorSchedulesTable
```php
namespace App\Model\Table;

class DoctorSchedulesTable extends Table
{
    public function initialize(array $config): void
    {
        $this->belongsTo('Staff', ['foreignKey' => 'staff_id']);
        $this->belongsTo('Services', ['foreignKey' => 'service_id']);
        
        $this->addBehavior('Timestamp');
    }
    
    public function validationDefault(Validator $validator): Validator
    {
        // Day of week: 1 (Monday) to 7 (Sunday)
        $validator->range('day_of_week', [1, 7]);
        
        // Time validations
        $validator->time('start_time')->notEmptyTime('start_time');
        $validator->time('end_time')->notEmptyTime('end_time');
        
        // Custom validation: end_time must be after start_time
        $validator->add('end_time', 'validTimeRange', [
            'rule' => function ($value, $context) {
                return strtotime($value) > strtotime($context['data']['start_time']);
            },
            'message' => 'End time must be after start time'
        ]);
        
        // Max appointments validation
        $validator->integer('max_appointments')
            ->greaterThan('max_appointments', 0);
            
        return $validator;
    }
    
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        // Prevent overlapping schedules
        $rules->add(function ($entity, $options) {
            $conditions = [
                'staff_id' => $entity->staff_id,
                'day_of_week' => $entity->day_of_week,
                'is_active' => true,
                'OR' => [
                    // New schedule starts during existing schedule
                    ['start_time <=' => $entity->start_time, 'end_time >' => $entity->start_time],
                    // New schedule ends during existing schedule
                    ['start_time <' => $entity->end_time, 'end_time >=' => $entity->end_time],
                    // New schedule completely overlaps existing schedule
                    ['start_time >=' => $entity->start_time, 'end_time <=' => $entity->end_time]
                ]
            ];
            
            if (!$entity->isNew()) {
                $conditions['id !='] = $entity->id;
            }
            
            return !$this->exists($conditions);
        }, 'noOverlap', [
            'errorField' => 'start_time',
            'message' => 'This time slot overlaps with an existing schedule'
        ]);
        
        return $rules;
    }
}
```

### 2.2 Entity Virtual Fields
```php
// In Staff Entity
protected function _getScheduleSummary()
{
    if (!$this->has('doctor_schedules')) {
        return [];
    }
    
    $summary = [];
    foreach ($this->doctor_schedules as $schedule) {
        $dayName = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $summary[] = sprintf(
            '%s: %s - %s (%s)',
            $dayName[$schedule->day_of_week - 1],
            $schedule->start_time->format('H:i'),
            $schedule->end_time->format('H:i'),
            $schedule->service->name ?? 'N/A'
        );
    }
    return $summary;
}

// In Appointment Entity
protected function _getFullDateTime()
{
    if ($this->has('appointment_date') && $this->has('appointment_time')) {
        return $this->appointment_date->setTime(
            $this->appointment_time->hour,
            $this->appointment_time->minute
        );
    }
    return null;
}
```

## 3. Service Layer Specifications

### 3.1 AvailabilityService
```php
namespace App\Service;

use Cake\I18n\Date;
use Cake\I18n\Time;

class AvailabilityService
{
    private $doctorSchedulesTable;
    private $appointmentsTable;
    private $staffUnavailabilitiesTable;
    private $hospitalHolidaysTable;
    private $scheduleExceptionsTable;
    
    /**
     * Get available doctors for a specialty on a given date
     * 
     * @param string $specialty Medical specialty
     * @param Date $date Requested date
     * @return array Available doctors with their time slots
     */
    public function getAvailableDoctors(string $specialty, Date $date): array
    {
        // Algorithm:
        // 1. Get all active doctors with the specified specialty
        // 2. For each doctor, check if they work on this day of week
        // 3. Check schedule exceptions (extra work days or days off)
        // 4. Check staff unavailabilities
        // 5. Check hospital holidays
        // 6. Return doctors with available time slots
    }
    
    /**
     * Get available time slots for a doctor on a specific date
     * 
     * @param int $doctorId Doctor ID
     * @param Date $date Requested date
     * @param int $serviceId Service ID
     * @return array Available time slots
     */
    public function getAvailableSlots(int $doctorId, Date $date, int $serviceId): array
    {
        // Algorithm:
        // 1. Get doctor's schedule for the day of week
        // 2. Check schedule exceptions
        // 3. Generate time slots based on service duration
        // 4. Remove slots that conflict with existing appointments
        // 5. Apply buffer time between appointments
        // 6. Return available slots with start and end times
    }
    
    /**
     * Check if a specific time slot is available
     * 
     * @param int $doctorId Doctor ID
     * @param Date $date Appointment date
     * @param Time $time Start time
     * @param int $serviceId Service ID
     * @return bool
     */
    public function isSlotAvailable(int $doctorId, Date $date, Time $time, int $serviceId): bool
    {
        // Check all constraints:
        // - Not a weekend (unless exception)
        // - Not a hospital holiday
        // - Doctor is not unavailable
        // - Doctor has schedule for this day/time
        // - No conflicting appointments
        // - Time slot accommodates service duration
    }
    
    /**
     * Generate time slots for a schedule
     * 
     * @param Time $startTime Schedule start time
     * @param Time $endTime Schedule end time
     * @param int $slotDuration Duration in minutes
     * @param int $bufferMinutes Buffer between slots
     * @return array Time slots
     */
    private function generateTimeSlots(
        Time $startTime, 
        Time $endTime, 
        int $slotDuration, 
        int $bufferMinutes = 0
    ): array {
        $slots = [];
        $current = clone $startTime;
        $totalDuration = $slotDuration + $bufferMinutes;
        
        while ($current->addMinutes($slotDuration) <= $endTime) {
            $slots[] = [
                'start' => clone $current,
                'end' => $current->addMinutes($slotDuration)
            ];
            $current = $current->addMinutes($totalDuration);
        }
        
        return $slots;
    }
}
```

## 4. Controller Specifications

### 4.1 Public AppointmentsController
```php
namespace App\Controller;

class AppointmentsController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Flash');
        $this->loadService('Availability');
    }
    
    /**
     * Booking form - Step 1: Select specialty
     */
    public function index()
    {
        // Get unique specialties from active staff
        $specialties = $this->Appointments->Doctors->find()
            ->select(['specialization'])
            ->where(['is_active' => true, 'specialization IS NOT' => null])
            ->distinct(['specialization'])
            ->orderAsc('specialization')
            ->toArray();
    }
    
    /**
     * AJAX: Get available doctors for specialty
     * 
     * Expected POST data:
     * - specialty: string
     * - date: Y-m-d format
     */
    public function checkAvailability()
    {
        $this->request->allowMethod(['post']);
        $this->viewBuilder()->setOption('serialize', ['doctors', 'success']);
        
        // Validate input
        // Call AvailabilityService->getAvailableDoctors()
        // Return JSON response
    }
    
    /**
     * AJAX: Get time slots for doctor
     * 
     * Expected POST data:
     * - doctor_id: int
     * - date: Y-m-d format
     * - service_id: int
     */
    public function getAvailableSlots()
    {
        $this->request->allowMethod(['post']);
        $this->viewBuilder()->setOption('serialize', ['slots', 'success']);
        
        // Validate input
        // Call AvailabilityService->getAvailableSlots()
        // Return JSON response with time slots
    }
    
    /**
     * Process booking
     * 
     * Expected POST data:
     * - doctor_id: int
     * - service_id: int
     * - appointment_date: Y-m-d format
     * - appointment_time: H:i format
     * - patient_name: string
     * - patient_email: email
     * - patient_phone: string
     * - notes: text (optional)
     */
    public function book()
    {
        $this->request->allowMethod(['post']);
        
        // Begin transaction
        // Validate all inputs
        // Check slot availability again (double-check)
        // Create appointment with status 'pending'
        // Generate confirmation token
        // Send confirmation email
        // Commit transaction
        // Redirect to success page
    }
    
    /**
     * Confirm appointment via email token
     * 
     * @param string $token Confirmation token
     */
    public function confirm($token = null)
    {
        // Find appointment by token
        // Check token expiry (24 hours)
        // Update status to 'confirmed'
        // Update confirmed_at timestamp
        // Send confirmation email
        // Display success message
    }
}
```

### 4.2 Admin DoctorSchedulesController
```php
namespace App\Controller\Admin;

class DoctorSchedulesController extends AppController
{
    /**
     * List all schedules with filtering
     */
    public function index()
    {
        $query = $this->DoctorSchedules->find()
            ->contain(['Staff', 'Services']);
            
        // Add filters for:
        // - Doctor
        // - Day of week
        // - Active/inactive
        // - Service
    }
    
    /**
     * Bulk schedule creation
     */
    public function bulkAdd()
    {
        if ($this->request->is('post')) {
            // Allow creating multiple schedules at once
            // For multiple days of week
            // For multiple doctors
            // With same time slots
        }
    }
    
    /**
     * Copy schedule from one doctor to another
     */
    public function copySchedule()
    {
        // Select source doctor
        // Select target doctor(s)
        // Option to adjust times
        // Validate no conflicts
        // Create new schedules
    }
    
    /**
     * Calendar view of all schedules
     */
    public function calendar()
    {
        // Weekly calendar view
        // Show all doctors and their schedules
        // Color-coded by service
        // Click to edit/view details
    }
}
```

## 5. Validation Rules

### 5.1 Appointment Validation
```php
// In AppointmentsTable::validationDefault()

// Patient information
$validator
    ->scalar('patient_name')
    ->maxLength('patient_name', 100)
    ->requirePresence('patient_name', 'create')
    ->notEmptyString('patient_name');
    
$validator
    ->email('patient_email')
    ->requirePresence('patient_email', 'create')
    ->notEmptyString('patient_email');
    
$validator
    ->scalar('patient_phone')
    ->maxLength('patient_phone', 20)
    ->requirePresence('patient_phone', 'create')
    ->notEmptyString('patient_phone')
    ->add('patient_phone', 'validPhone', [
        'rule' => ['custom', '/^[\d\s\-\+\(\)]+$/'],
        'message' => 'Please enter a valid phone number'
    ]);

// Appointment datetime validation
$validator
    ->date('appointment_date')
    ->requirePresence('appointment_date', 'create')
    ->notEmptyDate('appointment_date')
    ->add('appointment_date', 'futureDate', [
        'rule' => function ($value) {
            return $value >= Date::now();
        },
        'message' => 'Appointment date must be today or in the future'
    ]);
    
$validator
    ->time('appointment_time')
    ->requirePresence('appointment_time', 'create')
    ->notEmptyTime('appointment_time');

// Combined validation in buildRules()
$rules->add(function ($entity, $options) {
    // Check if slot is still available
    $availabilityService = new AvailabilityService();
    return $availabilityService->isSlotAvailable(
        $entity->doctor_id,
        $entity->appointment_date,
        $entity->appointment_time,
        $entity->service_id
    );
}, 'slotAvailable', [
    'errorField' => 'appointment_time',
    'message' => 'This time slot is no longer available'
]);
```

## 6. Email Templates

### 6.1 Appointment Confirmation Email
```php
// templates/email/html/appointment_confirmation.php
<h2>Appointment Confirmation</h2>
<p>Dear <?= h($appointment->patient_name) ?>,</p>
<p>Your appointment has been scheduled successfully.</p>

<h3>Appointment Details:</h3>
<table>
    <tr>
        <td><strong>Date:</strong></td>
        <td><?= $appointment->appointment_date->format('l, F j, Y') ?></td>
    </tr>
    <tr>
        <td><strong>Time:</strong></td>
        <td><?= $appointment->appointment_time->format('g:i A') ?></td>
    </tr>
    <tr>
        <td><strong>Doctor:</strong></td>
        <td><?= h($appointment->doctor->name) ?></td>
    </tr>
    <tr>
        <td><strong>Service:</strong></td>
        <td><?= h($appointment->service->name) ?></td>
    </tr>
    <tr>
        <td><strong>Duration:</strong></td>
        <td><?= $appointment->service->duration_minutes ?> minutes</td>
    </tr>
</table>

<p><strong>Important:</strong> Please arrive 10 minutes before your appointment time.</p>

<p>To confirm this appointment, please click the link below:</p>
<p><?= $this->Html->link(
    'Confirm Appointment',
    ['controller' => 'Appointments', 'action' => 'confirm', $appointment->confirmation_token, '_full' => true],
    ['class' => 'button']
) ?></p>

<p>If you need to cancel or reschedule, please call us at <?= Configure::read('Hospital.phone') ?>.</p>
```

## 7. AJAX Endpoints Response Format

### 7.1 Available Doctors Response
```json
{
    "success": true,
    "doctors": [
        {
            "id": 1,
            "name": "Dr. John Smith",
            "specialization": "Cardiology",
            "photo": "/img/staff/john-smith.jpg",
            "available_slots_count": 5,
            "next_available": "2024-01-15 09:00:00",
            "services": [
                {
                    "id": 1,
                    "name": "Consultation",
                    "duration_minutes": 30,
                    "price": 100.00
                }
            ]
        }
    ]
}
```

### 7.2 Available Slots Response
```json
{
    "success": true,
    "date": "2024-01-15",
    "doctor_id": 1,
    "service_id": 1,
    "slots": [
        {
            "time": "09:00",
            "display": "9:00 AM",
            "available": true,
            "end_time": "09:30"
        },
        {
            "time": "09:30",
            "display": "9:30 AM",
            "available": true,
            "end_time": "10:00"
        },
        {
            "time": "10:00",
            "display": "10:00 AM",
            "available": false,
            "reason": "Already booked"
        }
    ]
}
```

## 8. Business Rules

### 8.1 Scheduling Rules
1. **Minimum advance booking**: 1 hour before appointment
2. **Maximum advance booking**: 90 days
3. **Appointment duration**: Determined by service, minimum 15 minutes
4. **Buffer time**: Optional, default 0 minutes between appointments
5. **Working hours**: Typically 8:00 AM - 6:00 PM, configurable per doctor
6. **Lunch breaks**: Can be implemented as unavailable time slots

### 8.2 Cancellation Rules
1. **Patient cancellation**: Allowed up to 24 hours before appointment
2. **No-show tracking**: Mark as no-show if not cancelled and patient doesn't arrive
3. **Cancellation reasons**: Required for tracking
4. **Refund policy**: Configurable based on cancellation timing

### 8.3 Booking Limits
1. **Per patient**: Maximum 3 pending appointments
2. **Per doctor**: Configurable maximum appointments per day
3. **Per service**: Some services may have daily/weekly limits

## 9. Security Specifications

### 9.1 Authentication & Authorization
- Public booking: No authentication required
- Email/phone verification for booking
- Confirmation token: 64 characters, cryptographically secure
- Token expiry: 24 hours
- Admin access: Role-based (admin, staff)

### 9.2 Data Protection
- PII encryption for patient data at rest
- HTTPS required for all booking pages
- CSRF protection on all forms
- Rate limiting: 10 booking attempts per IP per hour
- Input sanitization for all user inputs

### 9.3 Audit Trail
- Log all appointment changes
- Track who made changes (admin/patient)
- Store IP addresses for bookings
- Maintain history of cancellations/rescheduling

## 10. Performance Specifications

### 10.1 Database Optimization
```sql
-- Indexes for common queries
CREATE INDEX idx_appointment_lookup ON appointments(patient_email, patient_phone, appointment_date);
CREATE INDEX idx_availability_check ON appointments(doctor_id, appointment_date, appointment_time, status);
CREATE INDEX idx_schedule_lookup ON doctor_schedules(staff_id, day_of_week, is_active);
```

### 10.2 Caching Strategy
- Cache doctor schedules (1 hour TTL)
- Cache service list (24 hour TTL)
- Cache holiday list (24 hour TTL)
- Real-time availability (no caching)

### 10.3 Query Optimization
- Use eager loading for related data
- Limit date ranges for availability checks
- Paginate appointment lists
- Use database views for complex availability queries

## 11. Integration Specifications

### 11.1 Calendar Integration
```php
// ICS file generation
public function generateICS($appointment)
{
    $ics = "BEGIN:VCALENDAR\r\n";
    $ics .= "VERSION:2.0\r\n";
    $ics .= "PRODID:-//Hospital//Appointment//EN\r\n";
    $ics .= "BEGIN:VEVENT\r\n";
    $ics .= "UID:" . $appointment->id . "@hospital.com\r\n";
    $ics .= "DTSTART:" . $appointment->full_date_time->format('Ymd\THis') . "\r\n";
    $ics .= "DTEND:" . $appointment->end_date_time->format('Ymd\THis') . "\r\n";
    $ics .= "SUMMARY:Medical Appointment - " . $appointment->service->name . "\r\n";
    $ics .= "DESCRIPTION:Appointment with " . $appointment->doctor->name . "\r\n";
    $ics .= "LOCATION:" . Configure::read('Hospital.address') . "\r\n";
    $ics .= "END:VEVENT\r\n";
    $ics .= "END:VCALENDAR\r\n";
    
    return $ics;
}
```

### 11.2 SMS Integration (Optional)
```php
interface SMSProviderInterface
{
    public function send(string $to, string $message): bool;
    public function getDeliveryStatus(string $messageId): string;
}

class TwilioSMSProvider implements SMSProviderInterface
{
    // Implementation details
}
```

## 12. Error Handling

### 12.1 User-Friendly Error Messages
```php
$errorMessages = [
    'slot_unavailable' => 'This time slot has just been booked. Please select another time.',
    'doctor_unavailable' => 'This doctor is not available on the selected date.',
    'holiday' => 'The hospital is closed on this date. Please select another date.',
    'weekend' => 'Appointments are not available on weekends.',
    'past_date' => 'Please select a future date for your appointment.',
    'too_far_ahead' => 'Appointments can only be booked up to 90 days in advance.',
    'invalid_service' => 'The selected service is not available with this doctor.',
];
```

### 12.2 Logging
```php
// Log all booking attempts
Log::info('Appointment booking attempt', [
    'patient_email' => $data['patient_email'],
    'doctor_id' => $data['doctor_id'],
    'requested_datetime' => $data['appointment_date'] . ' ' . $data['appointment_time'],
    'ip_address' => $this->request->clientIp(),
    'user_agent' => $this->request->getHeaderLine('User-Agent')
]);

// Log failures
Log::warning('Appointment booking failed', [
    'reason' => $error,
    'data' => $data
]);
```

## 13. Testing Specifications

### 13.1 Unit Test Cases
1. Test availability calculation with various scenarios
2. Test time slot generation with different durations
3. Test validation rules for all fields
4. Test schedule overlap detection
5. Test holiday and weekend detection

### 13.2 Integration Test Cases
1. Complete booking flow from start to finish
2. Concurrent booking attempts for same slot
3. Email notification delivery
4. Token expiration and renewal
5. Schedule exception handling

### 13.3 Performance Test Cases
1. Load test with 100 concurrent users
2. Availability check response time < 500ms
3. Booking completion time < 2 seconds
4. Database query optimization verification

## 14. Configuration

### 14.1 Application Configuration
```php
// config/app_local.php
'Appointments' => [
    'min_advance_hours' => 1,
    'max_advance_days' => 90,
    'confirmation_token_expiry' => 24, // hours
    'default_appointment_status' => 'pending',
    'allow_weekend_appointments' => false,
    'business_hours' => [
        'start' => '08:00',
        'end' => '18:00'
    ],
    'rate_limit' => [
        'attempts' => 10,
        'window' => 3600 // 1 hour in seconds
    ]
]
```

### 14.2 Email Configuration
```php
'EmailTransport' => [
    'appointments' => [
        'className' => 'Smtp',
        'host' => 'smtp.hospital.com',
        'port' => 587,
        'username' => 'appointments@hospital.com',
        'password' => 'secure_password',
        'tls' => true
    ]
]
```