<?php
require 'vendor/autoload.php';
require 'config/bootstrap.php';

use Cake\Datasource\ConnectionManager;

$connection = ConnectionManager::get('default');

echo "=== Checking Doctor Schedule Requirements ===\n\n";

// Check all schedules for doctor 1
$schedules = $connection->execute(
    "SELECT ds.*, s.name as service_name 
     FROM doctor_schedules ds 
     JOIN services s ON ds.service_id = s.id 
     WHERE ds.staff_id = 1 
     ORDER BY ds.day_of_week"
)->fetchAll('assoc');

echo "Doctor 1 schedules:\n";
foreach ($schedules as $schedule) {
    echo "Day {$schedule['day_of_week']}: {$schedule['start_time']} - {$schedule['end_time']} "
        . "| Service: {$schedule['service_name']} (ID: {$schedule['service_id']}) "
        . "| Active: {$schedule['is_active']}\n";
}

echo "\n=== Looking for the getWorkingHours method logic ===\n";

// Test the exact query used in getWorkingHours
$doctorId = 1;
$dayOfWeek = 3; // Wednesday
$serviceId = 1;

echo "\nTesting query for doctor=$doctorId, day=$dayOfWeek:\n";
$result = $connection->execute(
    "SELECT * FROM doctor_schedules 
     WHERE staff_id = ? AND day_of_week = ? AND is_active = 1",
    [$doctorId, $dayOfWeek]
)->fetchAll('assoc');

echo "Found " . count($result) . " schedules\n";
if (!empty($result)) {
    foreach ($result as $r) {
        echo "  Service ID: {$r['service_id']}, Time: {$r['start_time']} - {$r['end_time']}\n";
    }
}

// Check if there's a service-specific filter
echo "\n=== Checking if service-specific schedule is required ===\n";
$serviceSpecificResult = $connection->execute(
    "SELECT * FROM doctor_schedules 
     WHERE staff_id = ? AND day_of_week = ? AND service_id = ? AND is_active = 1",
    [$doctorId, $dayOfWeek, $serviceId]
)->fetch('assoc');

if ($serviceSpecificResult) {
    echo "Found service-specific schedule for service $serviceId\n";
} else {
    echo "No service-specific schedule found for service $serviceId\n";
}