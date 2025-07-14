<?php
require 'vendor/autoload.php';
require 'config/bootstrap.php';

use Cake\Datasource\ConnectionManager;
use Cake\I18n\Date;
use Cake\Core\Configure;

$connection = ConnectionManager::get('default');

// Check configuration
echo "=== Configuration ===\n";
echo "Max advance days: " . Configure::read('Appointments.max_advance_days', 90) . "\n";
echo "Min advance hours: " . Configure::read('Appointments.min_advance_hours', 1) . "\n";
echo "Use default hours when no schedule: " . Configure::read('Appointments.use_default_hours_when_no_schedule', false) . "\n";

// Test the actual query used in getWorkingHours
echo "\n=== Testing getWorkingHours Query ===\n";

$doctorId = 1;
$testDate = new Date('next wednesday'); // Should have schedule
echo "Testing for: " . $testDate->format('Y-m-d') . " (day of week: " . $testDate->dayOfWeek . ")\n";

// Direct query test
$schedule = $connection->execute(
    "SELECT * FROM doctor_schedules WHERE staff_id = ? AND day_of_week = ? AND is_active = 1",
    [$doctorId, $testDate->dayOfWeek]
)->fetch('assoc');

if ($schedule) {
    echo "Found schedule: " . $schedule['start_time'] . " - " . $schedule['end_time'] . "\n";
} else {
    echo "No schedule found\n";
}

// Check if the table has the correct connection
echo "\n=== Checking Table Configuration ===\n";
$doctorSchedulesTable = \Cake\ORM\TableRegistry::getTableLocator()->get('DoctorSchedules');
$query = $doctorSchedulesTable->find()
    ->where([
        'staff_id' => $doctorId,
        'day_of_week' => $testDate->dayOfWeek,
        'is_active' => true
    ]);

echo "Query SQL: " . $query->sql() . "\n";
$result = $query->first();
if ($result) {
    echo "Found via Table: " . $result->start_time->format('H:i:s') . " - " . $result->end_time->format('H:i:s') . "\n";
} else {
    echo "No result via Table\n";
}

// Check all schedules for doctor 1
echo "\n=== All schedules for Dr. Popescu Ion ===\n";
$allSchedules = $connection->execute(
    "SELECT day_of_week, start_time, end_time, is_active FROM doctor_schedules WHERE staff_id = ? ORDER BY day_of_week",
    [$doctorId]
)->fetchAll('assoc');

foreach ($allSchedules as $sched) {
    echo "Day {$sched['day_of_week']}: {$sched['start_time']} - {$sched['end_time']} (active: {$sched['is_active']})\n";
}