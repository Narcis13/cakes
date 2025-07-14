<?php
require 'vendor/autoload.php';
require 'config/bootstrap.php';

use App\Service\AvailabilityService;
use Cake\I18n\Date;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;

$availabilityService = new AvailabilityService();

// Test for next Wednesday (Dr. Popescu Ion works 14:00-18:00 on Wednesdays)
$doctorId = 1;
$serviceId = 1;
$testDate = new Date('next wednesday');

echo "=== Testing Wednesday {$testDate} ===\n";
echo "Day of week: {$testDate->dayOfWeek}\n";

// Check if it's within booking window
$maxDate = Date::now()->addDays(90);
echo "Max booking date: {$maxDate}\n";
echo "Is within window: " . ($testDate <= $maxDate ? "Yes" : "No") . "\n\n";

// Manually check the schedule
$doctorSchedulesTable = TableRegistry::getTableLocator()->get('DoctorSchedules');
$schedule = $doctorSchedulesTable->find()
    ->where([
        'staff_id' => $doctorId,
        'day_of_week' => $testDate->dayOfWeek,
        'is_active' => true
    ])
    ->first();

if ($schedule) {
    echo "Found schedule in database:\n";
    echo "  Start time: " . $schedule->start_time->format('H:i:s') . "\n";
    echo "  End time: " . $schedule->end_time->format('H:i:s') . "\n";
    echo "  Service ID: " . $schedule->service_id . "\n";
    echo "  Buffer minutes: " . $schedule->buffer_minutes . "\n\n";
} else {
    echo "No schedule found in database\n\n";
}

// Test getAvailableSlots
echo "=== Testing getAvailableSlots ===\n";
$slots = $availabilityService->getAvailableSlots($doctorId, $testDate, $serviceId);

if (empty($slots)) {
    echo "No slots returned by getAvailableSlots\n";
    
    // Let's manually check each condition
    echo "\n=== Debugging why no slots ===\n";
    
    // Check if doctor schedules table exists
    if (TableRegistry::getTableLocator()->exists('DoctorSchedules')) {
        echo "✓ DoctorSchedules table exists\n";
    } else {
        echo "✗ DoctorSchedules table doesn't exist\n";
    }
    
} else {
    echo "Found " . count($slots) . " slots:\n";
    foreach ($slots as $slot) {
        echo "  {$slot['time']} - {$slot['end_time']} " . ($slot['available'] ? "✓" : "✗") . "\n";
    }
}