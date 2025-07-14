<?php
require 'vendor/autoload.php';
require 'config/bootstrap.php';

use App\Service\AvailabilityService;
use Cake\I18n\Date;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;

$availabilityService = new AvailabilityService();

// Test for Dr. Popescu Ion (ID: 1)
$doctorId = 1;
$serviceId = 1; // Consult oftalmologic (15 min)

echo "=== Testing Different Wednesdays for Dr. Popescu Ion ===\n\n";

// Test multiple Wednesdays
$wednesdays = [
    '2025-07-16', // Next Wednesday after today (July 14)
    '2025-07-23', // The problematic Wednesday
    '2025-07-30', // Following Wednesday
    '2025-08-06', // August Wednesday
];

foreach ($wednesdays as $dateStr) {
    $date = new Date($dateStr);
    echo "--- Wednesday {$dateStr} ---\n";
    echo "Day of week: {$date->dayOfWeek}\n";
    echo "Days from today: " . Date::now()->diffInDays($date) . "\n";
    
    // Check various conditions that might affect availability
    
    // 1. Check if it's within booking window
    $maxDate = Date::now()->addDays(90);
    echo "Within 90-day window: " . ($date <= $maxDate ? "Yes" : "No") . "\n";
    
    // 2. Check if there's a schedule exception
    $scheduleExceptionsTable = TableRegistry::getTableLocator()->get('ScheduleExceptions');
    $exception = $scheduleExceptionsTable->find()
        ->where([
            'staff_id' => $doctorId,
            'exception_date' => $date
        ])
        ->first();
    
    if ($exception) {
        echo "Schedule exception found: " . ($exception->is_working ? "Working" : "Not working") . "\n";
    } else {
        echo "No schedule exception\n";
    }
    
    // 3. Check staff unavailabilities
    $unavailabilitiesTable = TableRegistry::getTableLocator()->get('StaffUnavailabilities');
    $unavailable = $unavailabilitiesTable->find()
        ->where([
            'staff_id' => $doctorId,
            'date_from <=' => $date,
            'date_to >=' => $date
        ])
        ->first();
    
    if ($unavailable) {
        echo "Staff unavailability found: {$unavailable->date_from} to {$unavailable->date_to}\n";
    } else {
        echo "No staff unavailability\n";
    }
    
    // 4. Check hospital holidays
    $holidaysTable = TableRegistry::getTableLocator()->get('HospitalHolidays');
    $holiday = $holidaysTable->find()
        ->where(['date' => $date])
        ->first();
    
    if ($holiday) {
        echo "Hospital holiday: {$holiday->name}\n";
    } else {
        echo "No hospital holiday\n";
    }
    
    // 5. Get available slots
    $slots = $availabilityService->getAvailableSlots($doctorId, $date, $serviceId);
    echo "Available slots: " . count($slots) . "\n";
    
    if (count($slots) > 0) {
        echo "First slot: {$slots[0]['time']}, Last slot: {$slots[count($slots)-1]['time']}\n";
    }
    
    echo "\n";
}

// Let's also check what exact date format is being used
echo "=== Date Format Check ===\n";
$testDate = new Date('2025-07-23');
echo "Date object: " . $testDate . "\n";
echo "Format Y-m-d: " . $testDate->format('Y-m-d') . "\n";
echo "Format for DB: " . $testDate->toDateString() . "\n";