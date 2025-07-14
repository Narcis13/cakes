<?php
require 'vendor/autoload.php';
require 'config/bootstrap.php';

use Cake\I18n\Date;
use Cake\Datasource\ConnectionManager;

$connection = ConnectionManager::get('default');

// Check what day of week CakePHP thinks each date is
echo "=== Day of Week Mapping ===\n";
$testDates = [
    'Monday' => '2025-01-20',
    'Tuesday' => '2025-01-21',
    'Wednesday' => '2025-01-22',
    'Thursday' => '2025-01-23',
    'Friday' => '2025-01-24',
    'Saturday' => '2025-01-25',
    'Sunday' => '2025-01-26'
];

foreach ($testDates as $dayName => $dateStr) {
    $date = new Date($dateStr);
    $cakeDayOfWeek = $date->dayOfWeek; // CakePHP format (0-6, 0=Sunday)
    $dbDayOfWeek = $cakeDayOfWeek == 0 ? 7 : $cakeDayOfWeek; // Database format (1-7, 1=Monday)
    
    echo "{$dayName} ({$dateStr}): CakePHP={$cakeDayOfWeek}, Database={$dbDayOfWeek}\n";
}

// Check Dr. Popescu Ion's schedules again
echo "\n=== Dr. Popescu Ion's Schedules ===\n";
$schedules = $connection->execute(
    "SELECT day_of_week, start_time, end_time FROM doctor_schedules WHERE staff_id = 1 AND is_active = 1 ORDER BY day_of_week"
)->fetchAll('assoc');

$days = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday'];
foreach ($schedules as $schedule) {
    echo "Day {$schedule['day_of_week']} ({$days[$schedule['day_of_week']]}): {$schedule['start_time']} - {$schedule['end_time']}\n";
}

// Test the actual query used in getWorkingHours
echo "\n=== Testing getWorkingHours Query ===\n";
$testDay = 3; // Wednesday
$result = $connection->execute(
    "SELECT * FROM doctor_schedules WHERE staff_id = 1 AND day_of_week = ? AND is_active = 1",
    [$testDay]
)->fetchAll('assoc');

echo "Query for day {$testDay} (Wednesday) returned " . count($result) . " results\n";
if (!empty($result)) {
    var_dump($result[0]);
}