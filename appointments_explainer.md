# Hospital Appointment System - Technical Documentation

## Table of Contents
1. [System Overview](#system-overview)
2. [Database Architecture](#database-architecture)
3. [Backend Implementation](#backend-implementation)
4. [Frontend Implementation](#frontend-implementation)
5. [Key Features](#key-features)
6. [API Endpoints](#api-endpoints)
7. [Security Considerations](#security-considerations)
8. [Testing](#testing)
9. [Future Enhancements](#future-enhancements)
10. [Troubleshooting](#troubleshooting)

## System Overview

The Hospital Appointment System is a comprehensive booking platform built with CakePHP 5.1 that allows patients to book appointments with doctors online. The system features a multi-step booking wizard, real-time availability checking, and automatic conflict prevention.

### Core Components
- **Multi-step booking wizard** with 5 steps
- **Real-time availability checking** with AJAX
- **Automatic slot conflict prevention**
- **Visual indication of busy/available slots**
- **Responsive design** for mobile and desktop
- **Email confirmation system** (ready but currently disabled)

### Technology Stack
- **Backend**: CakePHP 5.1
- **Frontend**: Vanilla JavaScript, HTML5, CSS3
- **Database**: MySQL/MariaDB
- **CSS Framework**: Milligram v1.3
- **Icons**: Font Awesome 5

## Database Architecture

### Core Tables

#### 1. `appointments` table
```sql
CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_name VARCHAR(100) NOT NULL,
    patient_email VARCHAR(100) NOT NULL,
    patient_phone VARCHAR(20) NOT NULL,
    patient_cnp VARCHAR(13), -- Romanian personal ID (optional)
    service_id INT NOT NULL,
    doctor_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    end_time TIME NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed', 'no-show') DEFAULT 'pending',
    notes TEXT,
    confirmation_token VARCHAR(64),
    confirmed_at DATETIME,
    cancelled_at DATETIME,
    cancellation_reason VARCHAR(255),
    created DATETIME,
    modified DATETIME,
    FOREIGN KEY (service_id) REFERENCES services(id),
    FOREIGN KEY (doctor_id) REFERENCES staff(id),
    INDEX idx_doctor_date (doctor_id, appointment_date),
    INDEX idx_status (status),
    INDEX idx_token (confirmation_token)
);
```

#### 2. `staff` table (doctors)
```sql
CREATE TABLE staff (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    staff_type ENUM('doctor', 'nurse', 'admin', 'other') DEFAULT 'doctor',
    specialization VARCHAR(100), -- Medical specialty
    department_id INT,
    photo VARCHAR(255), -- Profile photo path
    bio TEXT,
    is_active BOOLEAN DEFAULT true,
    created DATETIME,
    modified DATETIME
);
```

#### 3. `services` table
```sql
CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    duration_minutes INT NOT NULL DEFAULT 30,
    price DECIMAL(10,2),
    department_id INT,
    is_active BOOLEAN DEFAULT true,
    created DATETIME,
    modified DATETIME
);
```

#### 4. `doctor_schedules` table
```sql
CREATE TABLE doctor_schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    staff_id INT NOT NULL,
    service_id INT,
    day_of_week INT NOT NULL, -- 1-7 (Monday-Sunday)
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    buffer_minutes INT DEFAULT 0, -- Buffer between appointments
    is_active BOOLEAN DEFAULT true,
    created DATETIME,
    modified DATETIME,
    FOREIGN KEY (staff_id) REFERENCES staff(id),
    FOREIGN KEY (service_id) REFERENCES services(id),
    INDEX idx_staff_day (staff_id, day_of_week)
);
```

### Optional Supporting Tables

#### 5. `schedule_exceptions` table
```sql
CREATE TABLE schedule_exceptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    staff_id INT NOT NULL,
    exception_date DATE NOT NULL,
    is_working BOOLEAN DEFAULT false,
    start_time TIME,
    end_time TIME,
    reason VARCHAR(255),
    created DATETIME,
    modified DATETIME,
    FOREIGN KEY (staff_id) REFERENCES staff(id),
    UNIQUE KEY unique_staff_date (staff_id, exception_date)
);
```

#### 6. `hospital_holidays` table
```sql
CREATE TABLE hospital_holidays (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL UNIQUE,
    name VARCHAR(100),
    description TEXT,
    created DATETIME,
    modified DATETIME
);
```

## Backend Implementation

### 1. Model Layer

#### AppointmentsTable (`src/Model/Table/AppointmentsTable.php`)
- **Associations**:
  - `belongsTo` Services
  - `belongsTo` Doctors (alias for Staff table)
- **Validation Rules**:
  - Required fields: patient_name, patient_email, patient_phone, service_id, doctor_id, appointment_date, appointment_time
  - Email validation for patient_email
  - Phone number format validation
  - Future date validation for appointment_date
- **Custom Methods**:
  - `isTimeSlotAvailable()`: Checks for appointment conflicts
  - `findByDoctorAndDate()`: Gets all appointments for a doctor on a specific date
  - `generateConfirmationToken()`: Creates secure confirmation tokens

#### StaffTable (`src/Model/Table/StaffTable.php`)
- Represents doctors and other hospital staff
- Has many DoctorSchedules
- Has many Appointments (as Doctors)

#### ServicesTable (`src/Model/Table/ServicesTable.php`)
- Medical services offered by the hospital
- Contains duration and pricing information

### 2. Service Layer

#### AvailabilityService (`src/Service/AvailabilityService.php`)
This is the core service that handles all availability calculations:

```php
class AvailabilityService {
    // Main methods:
    public function getAvailableSlots($doctorId, $date, $serviceId)
    public function isSlotAvailable($doctorId, $date, $time, $serviceId)
    public function checkConflicts($doctorId, $date, $startTime, $endTime)
    public function calculateEndTime($startTime, $durationMinutes)
}
```

**Key Features**:
- Checks doctor's regular schedule
- Handles schedule exceptions (days off, extra working days)
- Validates against hospital holidays
- Prevents double booking
- Respects buffer times between appointments
- Validates minimum advance booking time
- Checks maximum advance booking period

### 3. Controller Layer

#### AppointmentsController (`src/Controller/AppointmentsController.php`)

**Actions**:

1. **index()** - Main booking page
   - Loads specializations and services
   - Renders the multi-step form

2. **checkAvailability()** - AJAX endpoint
   - Returns doctors for selected specialty
   - Includes available services for each doctor

3. **getAvailableSlots()** - AJAX endpoint
   - Returns time slots for selected doctor, date, and service
   - Marks slots as available/unavailable

4. **book()** - Form submission handler
   - Validates appointment data
   - Checks slot availability one more time
   - Saves appointment
   - Returns JSON response with redirect URL

5. **success($id)** - Success page
   - Displays appointment confirmation details

## Frontend Implementation

### 1. Multi-Step Form Structure

The booking form (`templates/Appointments/index.php`) consists of 5 steps:

#### Step 1: Select Medical Specialty
```javascript
// User clicks on specialty card
$('.specialty-card').click(function() {
    bookingData.specialty = $(this).data('specialty');
    enableNextButton(1);
});
```

#### Step 2: Select Doctor and Service
```javascript
// Loads doctors via AJAX
function loadDoctors() {
    fetch('/appointments/check-availability', {
        method: 'POST',
        body: JSON.stringify({ specialty: bookingData.specialty })
    })
    .then(response => response.json())
    .then(data => {
        // Render doctor cards with services
    });
}
```

#### Step 3: Select Date and Time
```javascript
// Loads time slots when date changes
$('#appointment-date').change(function() {
    loadTimeSlots(bookingData.doctorId, this.value, bookingData.serviceId);
});
```

#### Step 4: Enter Patient Details
- Patient name, email, phone
- Optional CNP (Romanian personal ID)
- Notes field for additional information

#### Step 5: Review and Confirm
- Summary of all selections
- Terms acceptance checkbox
- Submit button with loading state

### 2. JavaScript Architecture

```javascript
// Core data structure
let bookingData = {
    specialty: null,
    doctorId: null,
    doctorName: null,
    serviceId: null,
    serviceName: null,
    appointmentDate: null,
    appointmentTime: null
};

// Navigation functions
function goToStep(step) { /* ... */ }
function enableNextButton(step) { /* ... */ }
function validatePatientData() { /* ... */ }
```

### 3. Visual Slot States

#### Available Slots
```css
.time-slot {
    border: 2px solid #e9ecef;
    background: #fff;
    cursor: pointer;
}
```

#### Unavailable/Busy Slots
```css
.time-slot.unavailable {
    background: #f8f9fa;
    color: #6c757d;
    cursor: not-allowed;
    /* Red diagonal line */
    /* "Ocupat" label */
}
```

#### Expiring Soon Slots (within 2 hours)
```css
.time-slot.expiring-soon {
    border-color: #ffc107;
    background: #fff8e1;
    /* "Urgent" label */
}
```

## Key Features

### 1. Real-Time Availability Checking

The system performs multiple layers of availability checking:

1. **Frontend Check**: Visual indication of available/busy slots
2. **Backend Validation**: Double-checks availability before saving
3. **Conflict Prevention**: Uses database transactions to prevent race conditions

### 2. Slot Conflict Detection

```php
// Check for overlapping appointments
$conflictingAppointments = $this->appointmentsTable->find()
    ->where([
        'doctor_id' => $doctorId,
        'appointment_date' => $date,
        'status IN' => ['pending', 'confirmed'],
        'OR' => [
            // Various overlap conditions
        ]
    ]);
```

### 3. Business Rules

- **Working Hours**: Configurable per doctor per day
- **Buffer Time**: Optional break between appointments
- **Advance Booking**: Minimum 1 hour, maximum 90 days
- **Weekends**: Configurable, with exception handling
- **Holidays**: Hospital-wide holiday calendar

### 4. Progressive Enhancement

- Works without JavaScript (fallback to traditional form)
- AJAX enhancements for better UX
- Loading states for all async operations
- Error handling with user-friendly messages

## API Endpoints

### 1. Check Doctor Availability
```
POST /appointments/check-availability
Content-Type: application/json

{
    "specialty": "Cardiologie"
}

Response:
{
    "success": true,
    "doctors": [
        {
            "id": 1,
            "name": "Dr. John Doe",
            "specialization": "Cardiologie",
            "services": [...]
        }
    ]
}
```

### 2. Get Available Time Slots
```
POST /appointments/get-available-slots
Content-Type: application/json

{
    "doctor_id": 1,
    "date": "2024-01-15",
    "service_id": 3
}

Response:
{
    "success": true,
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
            "available": false,
            "end_time": "10:00"
        }
    ]
}
```

### 3. Book Appointment
```
POST /appointments/book
Content-Type: multipart/form-data

patient_name=John Doe
patient_email=john@example.com
patient_phone=0722123456
doctor_id=1
service_id=3
appointment_date=2024-01-15
appointment_time=09:00
notes=First visit

Response (JSON):
{
    "success": true,
    "message": "Programarea a fost creată cu succes!",
    "redirect": "http://localhost/appointments/success/123",
    "appointment_id": 123
}
```

## Security Considerations

### 1. CSRF Protection
- All forms include CSRF tokens
- AJAX requests send token in headers

### 2. Input Validation
- Server-side validation for all inputs
- Email format validation
- Phone number format validation
- Date/time range validation

### 3. SQL Injection Prevention
- CakePHP ORM handles parameterized queries
- No raw SQL in application code

### 4. XSS Prevention
- All output escaped with `h()` helper
- User input sanitized before display

### 5. Access Control
- Public booking (no authentication required)
- Admin panel requires authentication
- Confirmation tokens for email verification

## Testing

### Unit Tests
```bash
# Run model tests
vendor/bin/phpunit tests/TestCase/Model/Table/AppointmentsTableTest.php

# Run controller tests
vendor/bin/phpunit tests/TestCase/Controller/AppointmentsControllerTest.php
```

### Integration Tests
```bash
# Test the booking flow
vendor/bin/phpunit tests/TestCase/Integration/BookingFlowTest.php
```

### Manual Testing Checklist
1. ✅ Book appointment for today (check minimum advance time)
2. ✅ Book appointment for weekend (should fail unless exception)
3. ✅ Try to double-book same slot
4. ✅ Test with different screen sizes
5. ✅ Test without JavaScript enabled
6. ✅ Test concurrent bookings (race condition)

## Future Enhancements

### 1. Email Integration
```php
// Currently disabled but ready to enable:
$this->getMailer('Appointment')->send('confirmationEmail', [$appointment]);
```

### 2. SMS Notifications
- Add SMS gateway integration
- Send appointment reminders

### 3. Patient Portal
- View upcoming appointments
- Cancel/reschedule appointments
- View appointment history

### 4. Advanced Features
- Recurring appointments
- Group appointments
- Waiting list management
- Resource allocation (rooms, equipment)
- Insurance verification
- Online payment integration

### 5. Analytics
- Appointment statistics
- No-show tracking
- Popular time slots
- Doctor utilization reports

## Troubleshooting

### Common Issues

1. **"Slot not available" error when booking**
   - Check if slot was booked by another user
   - Verify doctor's schedule for that day
   - Check for hospital holidays

2. **No doctors showing for specialty**
   - Verify doctors have the specialty assigned
   - Check if doctors are active
   - Ensure doctor_schedules entries exist

3. **No time slots available**
   - Check doctor_schedules for the day_of_week
   - Verify service duration fits in schedule
   - Check for schedule_exceptions

4. **Form returns to step 1 after submission**
   - Check browser console for JavaScript errors
   - Verify AJAX endpoints return proper JSON
   - Check server logs for PHP errors

### Debug Mode
```php
// Enable debug output in AvailabilityService
Configure::write('debug', true);
\Cake\Log\Log::debug('Checking availability for doctor ' . $doctorId);
```

### Database Queries
```sql
-- Check appointments for a doctor on a date
SELECT * FROM appointments 
WHERE doctor_id = 1 
AND appointment_date = '2024-01-15'
AND status IN ('pending', 'confirmed')
ORDER BY appointment_time;

-- Check doctor's schedule
SELECT * FROM doctor_schedules
WHERE staff_id = 1
AND day_of_week = 2  -- Tuesday
AND is_active = 1;
```

## Code Structure

```
src/
├── Controller/
│   └── AppointmentsController.php
├── Model/
│   ├── Entity/
│   │   └── Appointment.php
│   └── Table/
│       ├── AppointmentsTable.php
│       ├── StaffTable.php
│       └── ServicesTable.php
├── Service/
│   └── AvailabilityService.php
└── Mailer/
    └── AppointmentMailer.php (ready but disabled)

templates/
├── Appointments/
│   ├── index.php        # Multi-step booking form
│   ├── success.php      # Confirmation page
│   └── confirm.php      # Email confirmation page
└── email/
    ├── html/
    │   └── appointment_confirmation.php
    └── text/
        └── appointment_confirmation.php

webroot/
├── css/
│   └── appointments.css
└── js/
    └── appointments.js
```

## Contributing

When adding new features to the appointment system:

1. **Follow CakePHP conventions**
   - Use bake for generating code
   - Follow PSR-12 coding standards
   - Write tests for new functionality

2. **Update documentation**
   - Add new endpoints to API section
   - Update database schema if needed
   - Document new business rules

3. **Consider backward compatibility**
   - Don't break existing bookings
   - Migrate data if schema changes
   - Version API endpoints if needed

4. **Performance considerations**
   - Index new database columns
   - Cache frequently accessed data
   - Optimize queries for large datasets

5. **Accessibility**
   - Ensure keyboard navigation works
   - Add proper ARIA labels
   - Test with screen readers

## Conclusion

The Hospital Appointment System provides a robust foundation for online appointment booking. Its modular architecture allows for easy extension while maintaining data integrity and providing excellent user experience. The separation of concerns between availability checking (AvailabilityService), data persistence (Models), and request handling (Controllers) makes the codebase maintainable and testable.

For questions or support, please refer to the main project documentation or contact the development team.