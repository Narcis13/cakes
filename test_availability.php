<?php
require 'vendor/autoload.php';
require 'config/bootstrap.php';

use App\Service\AvailabilityService;
use Cake\I18n\Date;
use Cake\I18n\Time;

$availabilityService = new AvailabilityService();

// Test for Dr. Popescu Ion (ID: 1)
$doctorId = 1;
$serviceId = 1; // Consult oftalmologic (15 min)

echo "=== Testing Availability for Dr. Popescu Ion ===\n\n";

// Test for different days of the week - using next week's dates
$today = new \DateTime();
$nextMonday = new \DateTime('monday next week');

// Make sure we're within the 90-day booking window
$maxDate = new \DateTime('+90 days');
if ($nextMonday > $maxDate) {
    $nextMonday = new \DateTime('+1 day'); // Use tomorrow if next week is too far
}

$testDays = [];
for ($i = 0; $i < 7; $i++) {
    $testDate = clone $nextMonday;
    $testDate->modify("+{$i} days");
    $dayName = $testDate->format('l');
    $testDays[$dayName] = $testDate->format('Y-m-d');
}

foreach ($testDays as $dayName => $dateStr) {
    $date = new Date($dateStr);
    echo "--- {$dayName} ({$dateStr}) ---\n";
    
    $slots = $availabilityService->getAvailableSlots($doctorId, $date, $serviceId);
    
    if (empty($slots)) {
        echo "No slots available (Doctor doesn't work this day)\n";
    } else {
        echo "Available slots:\n";
        $previousEnd = null;
        foreach ($slots as $slot) {
            echo "  {$slot['time']} - {$slot['end_time']}";
            
            // Calculate interval from previous slot
            if ($previousEnd) {
                $prev = new Time($previousEnd);
                $current = new Time($slot['time']);
                $prevMinutes = $prev->hour * 60 + $prev->minute;
                $currentMinutes = $current->hour * 60 + $current->minute;
                $interval = $currentMinutes - $prevMinutes;
                echo " (interval: {$interval} minutes)";
            }
            
            echo $slot['available'] ? " ✓" : " ✗";
            echo "\n";
            
            $previousEnd = $slot['end_time'];
        }
        echo "Total slots: " . count($slots) . "\n";
    }
    echo "\n";
}

// Test specific time availability
echo "=== Testing Specific Time Availability ===\n";
// Use the Monday from our test days
$mondayDateStr = $testDays['Monday'];
$mondayDate = new Date($mondayDateStr);
echo "Testing Monday: {$mondayDateStr}\n";
$testTimes = [
    '09:00' => 'Should be unavailable (before working hours)',
    '14:00' => 'Should be available (start of working hours)',
    '15:30' => 'Should be available (during working hours)',
    '17:45' => 'Should be unavailable (too close to end)',
    '18:00' => 'Should be unavailable (after working hours)'
];

foreach ($testTimes as $timeStr => $description) {
    $time = new Time($timeStr);
    $isAvailable = $availabilityService->isSlotAvailable($doctorId, $mondayDate, $time, $serviceId);
    echo "{$timeStr} - {$description}: " . ($isAvailable ? "✓ Available" : "✗ Not available") . "\n";
}