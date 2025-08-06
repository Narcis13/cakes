<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Table\AppointmentsTable;
use App\Model\Table\DoctorSchedulesTable;
use App\Model\Table\HospitalHolidaysTable;
use App\Model\Table\ScheduleExceptionsTable;
use App\Model\Table\ServicesTable;
use App\Model\Table\StaffTable;
use App\Model\Table\StaffUnavailabilitiesTable;
use Cake\Core\Configure;
use Cake\I18n\Date;
use Cake\I18n\FrozenTime;
use Cake\I18n\Time;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Exception;

/**
 * AvailabilityService
 *
 * Service for managing appointment availability calculations
 */
class AvailabilityService
{
    /**
     * @var \App\Model\Table\StaffTable
     */
    private StaffTable $staffTable;

    /**
     * @var \App\Model\Table\AppointmentsTable
     */
    private AppointmentsTable $appointmentsTable;

    /**
     * @var \App\Model\Table\ServicesTable
     */
    private ServicesTable $servicesTable;

    /**
     * @var \App\Model\Table\DoctorSchedulesTable
     */
    private DoctorSchedulesTable $doctorSchedulesTable;

    /**
     * @var \App\Model\Table\ScheduleExceptionsTable
     */
    private ScheduleExceptionsTable $scheduleExceptionsTable;

    /**
     * @var \App\Model\Table\StaffUnavailabilitiesTable
     */
    private StaffUnavailabilitiesTable $staffUnavailabilitiesTable;

    /**
     * @var \App\Model\Table\HospitalHolidaysTable
     */
    private HospitalHolidaysTable $hospitalHolidaysTable;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->staffTable = TableRegistry::getTableLocator()->get('Staff');
        $this->appointmentsTable = TableRegistry::getTableLocator()->get('Appointments');
        $this->servicesTable = TableRegistry::getTableLocator()->get('Services');

        // Initialize optional tables - use try/catch to handle if tables don't exist
        try {
            $this->doctorSchedulesTable = TableRegistry::getTableLocator()->get('DoctorSchedules');
        } catch (Exception $e) {
            // Table doesn't exist yet
        }

        try {
            $this->scheduleExceptionsTable = TableRegistry::getTableLocator()->get('ScheduleExceptions');
        } catch (Exception $e) {
            // Table doesn't exist yet
        }

        try {
            $this->staffUnavailabilitiesTable = TableRegistry::getTableLocator()->get('StaffUnavailabilities');
        } catch (Exception $e) {
            // Table doesn't exist yet
        }

        try {
            $this->hospitalHolidaysTable = TableRegistry::getTableLocator()->get('HospitalHolidays');
        } catch (Exception $e) {
            // Table doesn't exist yet
        }
    }

    /**
     * Get available doctors for a specialty on a given date
     *
     * @param string $specialty Medical specialty
     * @param \Cake\I18n\Date $date Requested date
     * @return array Available doctors with their time slots
     */
    public function getAvailableDoctors(string $specialty, Date $date): array
    {
        // Get all active doctors with the specified specialty
        $doctors = $this->staffTable->find()
            ->where([
                'specialization' => $specialty,
                'is_active' => true,
                'staff_type' => 'doctor', // Only get doctors, not other staff
            ])
            ->toArray();

        $availableDoctors = [];

        foreach ($doctors as $doctor) {
            // Get services for this doctor's department or all active services
            $services = $this->servicesTable->find()
                ->where(['is_active' => true])
                ->toArray();

            // Skip if no services available
            if (empty($services)) {
                continue;
            }

            // Assign services to doctor for later use
            $doctor->services = $services;

            // Apply all availability rules
            // 1. Check hospital holidays
            if ($this->isHospitalHoliday($date)) {
                continue;
            }

            // 2. Check if doctor works on this day
            // CakePHP 5 uses dayOfWeek 1-7 where 7=Sunday, same as our database
            $dayOfWeek = $date->dayOfWeek;

            // 3. Check weekends
            $allowWeekends = Configure::read('Appointments.allow_weekend_appointments', false);
            if (!$allowWeekends && ($dayOfWeek == 7 || $dayOfWeek == 6)) {
                if (!$this->hasWeekendException($doctor->id, $date)) {
                    continue;
                }
            }

            // 4. Check schedule exceptions
            if ($this->hasScheduleException($doctor->id, $date)) {
                $exception = $this->getScheduleException($doctor->id, $date);
                if (!$exception->is_working) {
                    continue; // Doctor is off this day
                }
            }

            // 5. Check staff unavailabilities
            if ($this->staffUnavailabilitiesTable) {
                $isUnavailable = $this->staffUnavailabilitiesTable->exists([
                    'staff_id' => $doctor->id,
                    'date_from <=' => $date,
                    'date_to >=' => $date,
                ]);

                if ($isUnavailable) {
                    continue;
                }
            }

            // Check if doctor has any availability on this date
            $hasAvailability = false;
            foreach ($doctor->services as $service) {
                $slots = $this->getAvailableSlots($doctor->id, $date, $service->id);
                if (!empty($slots)) {
                    $hasAvailability = true;
                    break;
                }
            }

            if ($hasAvailability) {
                $availableDoctors[] = [
                    'id' => $doctor->id,
                    'name' => $doctor->name,
                    'specialization' => $doctor->specialization,
                    'photo' => $doctor->photo,
                    'email' => $doctor->email,
                    'phone' => $doctor->phone,
                    'services' => array_map(function ($service) {
                        return [
                            'id' => $service->id,
                            'name' => $service->name,
                            'duration_minutes' => $service->duration_minutes,
                            'price' => $service->price,
                        ];
                    }, $doctor->services),
                ];
            }
        }

        return $availableDoctors;
    }

    /**
     * Get available time slots for a doctor on a specific date
     *
     * @param int $doctorId Doctor ID
     * @param \Cake\I18n\Date $date Requested date
     * @param int $serviceId Service ID
     * @return array Available time slots
     */
    public function getAvailableSlots(int $doctorId, Date $date, int $serviceId): array
    {
        // Early return if it's a hospital holiday
        if ($this->isHospitalHoliday($date)) {
            return [];
        }

        // Get service details
        $service = $this->servicesTable->get($serviceId);
        $duration = $service->duration_minutes;
        $bufferMinutes = $this->getBufferTime($doctorId, $serviceId);

        // Get doctor's schedule for the day
        // CakePHP 5 uses dayOfWeek 1-7 where 7=Sunday, same as our database
        $dayOfWeek = $date->dayOfWeek;

        // Check for schedule exceptions first
        $workingHours = null;
        if ($this->hasScheduleException($doctorId, $date)) {
            $exception = $this->getScheduleException($doctorId, $date);
            if (!$exception->is_working) {
                return []; // Doctor is off this day
            }
            if ($exception->start_time && $exception->end_time) {
                $workingHours = [
                    'start' => $exception->start_time->format('H:i:s'),
                    'end' => $exception->end_time->format('H:i:s'),
                ];
            }
        }

        // If no exception, get regular working hours
        if (!$workingHours) {
            $workingHours = $this->getWorkingHours($doctorId, $dayOfWeek);
        }

        if (!$workingHours) {
            return [];
        }

        $startTime = new Time($workingHours['start']);
        $endTime = new Time($workingHours['end']);

        // Get existing appointments for this doctor on this date
        $existingAppointments = $this->appointmentsTable->find()
            ->where([
                'doctor_id' => $doctorId,
                'appointment_date' => $date,
                'status IN' => ['pending', 'confirmed'],
            ])
            ->orderAsc('appointment_time')
            ->toArray();

        // Generate time slots
        $slots = [];
        $currentTime = clone $startTime;
        // Use service duration as the interval (plus buffer if needed)
        $interval = $duration + $bufferMinutes;

        while ($currentTime < $endTime) {
            $slotEnd = $this->calculateEndTime($currentTime, $duration);
            if ($bufferMinutes > 0) {
                $frozenTime = FrozenTime::parse($slotEnd->format('H:i:s'));
                $newFrozenTime = $frozenTime->addMinutes($bufferMinutes);
                $slotEndWithBuffer = Time::parse($newFrozenTime->format('H:i:s'));
            } else {
                $slotEndWithBuffer = $slotEnd;
            }

            // Check if slot end time (with buffer) is within working hours
            if ($slotEndWithBuffer <= $endTime) {
                // Check if slot is available using all rules
                $isAvailable = $this->isSlotAvailable($doctorId, $date, $currentTime, $serviceId);

                $slots[] = [
                    'time' => $currentTime->format('H:i'),
                    'display' => $currentTime->i18nFormat('h:mm a'),
                    'available' => $isAvailable,
                    'end_time' => $slotEnd->format('H:i'),
                ];
            }

            // Move to next interval
            $frozenCurrent = FrozenTime::parse($currentTime->format('H:i:s'));
            $newFrozenCurrent = $frozenCurrent->addMinutes($interval);
            $currentTime = Time::parse($newFrozenCurrent->format('H:i:s'));
        }

        return $slots;
    }

    /**
     * Check if a specific time slot is available
     *
     * @param int $doctorId Doctor ID
     * @param \Cake\I18n\Date $date Appointment date
     * @param \Cake\I18n\Time $time Start time
     * @param int $serviceId Service ID
     * @return bool
     */
    public function isSlotAvailable(int $doctorId, Date $date, Time $time, int $serviceId): bool
    {
        // Check if date is not in the past
        if ($date < Date::now()) {
            return false;
        }

        // Check if it's today and time has passed
        if ($date->equals(Date::now()) && $time < Time::now()) {
            return false;
        }

        // Check minimum advance booking time (default 1 hour)
        $minAdvanceHours = Configure::read('Appointments.min_advance_hours', 1);
        $nowFrozen = FrozenTime::now();
        $minBookingFrozen = $nowFrozen->addHours($minAdvanceHours);
        $minBookingTime = Time::parse($minBookingFrozen->format('H:i:s'));

        if ($date->equals(Date::now()) && $time < $minBookingTime) {
            return false;
        }

        // Check maximum advance booking (default 90 days)
        $maxAdvanceDays = Configure::read('Appointments.max_advance_days', 90);
        $maxDate = Date::now()->addDays($maxAdvanceDays);

        if ($date > $maxDate) {
            return false;
        }

        // 1. Check doctor's regular weekly schedule
        if (!$this->isDoctorWorkingOnDay($doctorId, $date, $time)) {
            return false;
        }

        // 2. Check schedule exceptions (extra days or days off)
        if ($this->hasScheduleException($doctorId, $date)) {
            $exception = $this->getScheduleException($doctorId, $date);
            if (!$exception->is_working) {
                return false; // Day off
            }
            // Check if time is within exception hours if specified
            if ($exception->start_time && $exception->end_time) {
                if ($time < $exception->start_time || $time >= $exception->end_time) {
                    return false;
                }
            }
        }

        // 3. Check staff unavailabilities
        if ($this->isStaffUnavailable($doctorId, $date, $time)) {
            return false;
        }

        // 4. Check hospital holidays
        if ($this->isHospitalHoliday($date)) {
            return false;
        }

        // 5. Exclude weekends (unless exception)
        $dayOfWeek = $date->dayOfWeek;
        $allowWeekends = Configure::read('Appointments.allow_weekend_appointments', false);
        if (!$allowWeekends && ($dayOfWeek == 7 || $dayOfWeek == 6)) {
            if (!$this->hasWeekendException($doctorId, $date)) {
                return false;
            }
        }

        // 6. Get service duration and check duration requirements
        $service = $this->servicesTable->get($serviceId);
        $endTime = $this->calculateEndTime($time, $service->duration_minutes);

        // 7. Apply buffer time if configured
        $bufferMinutes = $this->getBufferTime($doctorId, $serviceId);
        if ($bufferMinutes > 0) {
            $frozenTime = FrozenTime::parse($endTime->format('H:i:s'));
            $newFrozenTime = $frozenTime->addMinutes($bufferMinutes);
            $endTime = Time::parse($newFrozenTime->format('H:i:s'));
        }

        // Check if the appointment fits within working hours
        if (!$this->fitsWithinWorkingHours($doctorId, $date, $time, $endTime)) {
            return false;
        }

        // 8. Check for conflicts with existing appointments (including buffer)
        return !$this->checkConflicts($doctorId, $date, $time, $endTime);
    }

    /**
     * Calculate end time based on start time and duration
     *
     * @param \Cake\I18n\Time $startTime Start time
     * @param int $durationMinutes Duration in minutes
     * @return \Cake\I18n\Time End time
     */
    public function calculateEndTime(Time $startTime, int $durationMinutes): Time
    {
        // Convert to FrozenTime for calculation
        $frozenTime = FrozenTime::parse($startTime->format('H:i:s'));
        $newFrozenTime = $frozenTime->addMinutes($durationMinutes);

        // Convert back to Time
        return Time::parse($newFrozenTime->format('H:i:s'));
    }

    /**
     * Check for appointment conflicts
     *
     * @param int $doctorId Doctor ID
     * @param \Cake\I18n\Date $date Appointment date
     * @param \Cake\I18n\Time $startTime Start time
     * @param \Cake\I18n\Time $endTime End time
     * @return bool True if there's a conflict, false otherwise
     */
    public function checkConflicts(int $doctorId, Date $date, Time $startTime, Time $endTime): bool
    {
        // Find appointments that would conflict with the proposed time slot
        $conflictingAppointments = $this->appointmentsTable->find()
            ->where([
                'doctor_id' => $doctorId,
                'appointment_date' => $date,
                'status IN' => ['pending', 'confirmed'],
                'OR' => [
                    // New appointment starts during existing appointment
                    [
                        'appointment_time <=' => $startTime,
                        'end_time >' => $startTime,
                    ],
                    // New appointment ends during existing appointment
                    [
                        'appointment_time <' => $endTime,
                        'end_time >=' => $endTime,
                    ],
                    // New appointment completely overlaps existing appointment
                    [
                        'appointment_time >=' => $startTime,
                        'end_time <=' => $endTime,
                    ],
                ],
            ])
            ->count();

        return $conflictingAppointments > 0;
    }

    /**
     * Get working hours for a doctor on a specific day
     *
     * @param int $doctorId Doctor ID
     * @param int $dayOfWeek Day of week (0-6, where 0 is Sunday)
     * @return array|null Working hours or null if not working
     */
    private function getWorkingHours(int $doctorId, int $dayOfWeek): ?array
    {
        // If DoctorSchedules table exists, use it
        if ($this->doctorSchedulesTable) {
            $schedule = $this->doctorSchedulesTable->find()
                ->where([
                    'staff_id' => $doctorId,
                    'day_of_week' => $dayOfWeek,
                    'is_active' => true,
                ])
                ->first();

            if ($schedule) {
                return [
                    'start' => $schedule->start_time->format('H:i:s'),
                    'end' => $schedule->end_time->format('H:i:s'),
                ];
            }
        }

        // Only use default hours if explicitly enabled in configuration
        $useDefaultHours = Configure::read('Appointments.use_default_hours_when_no_schedule', false);

        if ($useDefaultHours && $dayOfWeek >= 1 && $dayOfWeek <= 5) {
            return [
                'start' => Configure::read('Appointments.default_start_time', '09:00:00'),
                'end' => Configure::read('Appointments.default_end_time', '17:00:00'),
            ];
        }

        return null;
    }

    /**
     * Check if doctor has weekend exception
     *
     * @param int $doctorId Doctor ID
     * @param \Cake\I18n\Date $date Date to check
     * @return bool
     */
    private function hasWeekendException(int $doctorId, Date $date): bool
    {
        if (!$this->scheduleExceptionsTable) {
            return false;
        }

        $exception = $this->scheduleExceptionsTable->find()
            ->where([
                'staff_id' => $doctorId,
                'exception_date' => $date,
                'is_working' => true,
            ])
            ->first();

        return $exception !== null;
    }

    /**
     * Check if doctor is working on a specific day and time
     *
     * @param int $doctorId Doctor ID
     * @param \Cake\I18n\Date $date Date to check
     * @param \Cake\I18n\Time $time Time to check
     * @return bool
     */
    private function isDoctorWorkingOnDay(int $doctorId, Date $date, Time $time): bool
    {
        // CakePHP 5 uses dayOfWeek 1-7 where 7=Sunday, same as our database
        $dayOfWeek = $date->dayOfWeek;
        $workingHours = $this->getWorkingHours($doctorId, $dayOfWeek);

        if (!$workingHours) {
            return false;
        }

        $startTime = new Time($workingHours['start']);
        $endTime = new Time($workingHours['end']);

        return $time >= $startTime && $time < $endTime;
    }

    /**
     * Check if doctor has schedule exception
     *
     * @param int $doctorId Doctor ID
     * @param \Cake\I18n\Date $date Date to check
     * @return bool
     */
    private function hasScheduleException(int $doctorId, Date $date): bool
    {
        if (!$this->scheduleExceptionsTable) {
            return false;
        }

        return $this->scheduleExceptionsTable->exists([
            'staff_id' => $doctorId,
            'exception_date' => $date,
        ]);
    }

    /**
     * Get schedule exception details
     *
     * @param int $doctorId Doctor ID
     * @param \Cake\I18n\Date $date Date to check
     * @return \Cake\ORM\Entity|null
     */
    private function getScheduleException(int $doctorId, Date $date): ?Entity
    {
        if (!$this->scheduleExceptionsTable) {
            return null;
        }

        return $this->scheduleExceptionsTable->find()
            ->where([
                'staff_id' => $doctorId,
                'exception_date' => $date,
            ])
            ->first();
    }

    /**
     * Check if staff is unavailable
     *
     * @param int $doctorId Doctor ID
     * @param \Cake\I18n\Date $date Date to check
     * @param \Cake\I18n\Time $time Time to check
     * @return bool
     */
    private function isStaffUnavailable(int $doctorId, Date $date, Time $time): bool
    {
        if (!$this->staffUnavailabilitiesTable) {
            return false;
        }

        // Check if the date falls within any unavailability period
        return $this->staffUnavailabilitiesTable->exists([
            'staff_id' => $doctorId,
            'date_from <=' => $date,
            'date_to >=' => $date,
        ]);
    }

    /**
     * Check if date is a hospital holiday
     *
     * @param \Cake\I18n\Date $date Date to check
     * @return bool
     */
    private function isHospitalHoliday(Date $date): bool
    {
        if (!$this->hospitalHolidaysTable) {
            return false;
        }

        return $this->hospitalHolidaysTable->exists([
            'date' => $date,
        ]);
    }

    /**
     * Get buffer time for appointments
     *
     * @param int $doctorId Doctor ID
     * @param int $serviceId Service ID
     * @return int Buffer time in minutes
     */
    private function getBufferTime(int $doctorId, int $serviceId): int
    {
        // First check if doctor has specific buffer time in schedule
        if ($this->doctorSchedulesTable) {
            $schedule = $this->doctorSchedulesTable->find()
                ->where([
                    'staff_id' => $doctorId,
                    'service_id' => $serviceId,
                    'is_active' => true,
                ])
                ->first();

            if ($schedule && $schedule->buffer_minutes !== null) {
                return $schedule->buffer_minutes;
            }
        }

        // Use default buffer time from configuration
        return Configure::read('Appointments.default_buffer_minutes', 0);
    }

    /**
     * Check if appointment fits within working hours
     *
     * @param int $doctorId Doctor ID
     * @param \Cake\I18n\Date $date Date
     * @param \Cake\I18n\Time $startTime Start time
     * @param \Cake\I18n\Time $endTime End time (including buffer)
     * @return bool
     */
    private function fitsWithinWorkingHours(int $doctorId, Date $date, Time $startTime, Time $endTime): bool
    {
        // CakePHP 5 uses dayOfWeek 1-7 where 7=Sunday, same as our database
        $dayOfWeek = $date->dayOfWeek;

        // Check for schedule exception first
        if ($this->hasScheduleException($doctorId, $date)) {
            $exception = $this->getScheduleException($doctorId, $date);
            if ($exception->is_working && $exception->start_time && $exception->end_time) {
                return $startTime >= $exception->start_time && $endTime <= $exception->end_time;
            }
        }

        // Check regular working hours
        $workingHours = $this->getWorkingHours($doctorId, $dayOfWeek);
        if (!$workingHours) {
            return false;
        }

        $workStart = new Time($workingHours['start']);
        $workEnd = new Time($workingHours['end']);

        return $startTime >= $workStart && $endTime <= $workEnd;
    }

    /**
     * Generate time slots for a given time range
     *
     * @param \Cake\I18n\Time $startTime Start time
     * @param \Cake\I18n\Time $endTime End time
     * @param int $slotDuration Duration in minutes
     * @param int $bufferMinutes Buffer between slots
     * @return array Time slots
     */
    public function generateTimeSlots(
        Time $startTime,
        Time $endTime,
        int $slotDuration,
        int $bufferMinutes = 0,
    ): array {
        $slots = [];
        $current = clone $startTime;
        $totalDuration = $slotDuration + $bufferMinutes;

        while (true) {
            $frozenCurrent = FrozenTime::parse($current->format('H:i:s'));
            $slotEndFrozen = $frozenCurrent->addMinutes($slotDuration);
            $slotEnd = Time::parse($slotEndFrozen->format('H:i:s'));

            if ($slotEnd > $endTime) {
                break;
            }

            $slots[] = [
                'start' => clone $current,
                'end' => $slotEnd,
            ];

            $nextFrozen = $frozenCurrent->addMinutes($totalDuration);
            $current = Time::parse($nextFrozen->format('H:i:s'));
        }

        return $slots;
    }
}
