<?php
require 'vendor/autoload.php';
require 'config/bootstrap.php';

use Cake\ORM\TableRegistry;

echo "=== Testing TableRegistry ===\n\n";

// Check if DoctorSchedules exists
$exists = TableRegistry::getTableLocator()->exists('DoctorSchedules');
echo "DoctorSchedules exists: " . ($exists ? "Yes" : "No") . "\n";

// Try to get it anyway
try {
    $table = TableRegistry::getTableLocator()->get('DoctorSchedules');
    echo "Successfully got DoctorSchedules table\n";
    echo "Table class: " . get_class($table) . "\n";
} catch (\Exception $e) {
    echo "Error getting DoctorSchedules: " . $e->getMessage() . "\n";
}

// Check the AvailabilityService initialization
echo "\n=== Testing AvailabilityService initialization ===\n";
$service = new \App\Service\AvailabilityService();

// Use reflection to check private properties
$reflection = new \ReflectionClass($service);
$doctorSchedulesTableProp = $reflection->getProperty('doctorSchedulesTable');
$doctorSchedulesTableProp->setAccessible(true);
$value = $doctorSchedulesTableProp->getValue($service);

if ($value === null) {
    echo "doctorSchedulesTable property is NULL\n";
} else {
    echo "doctorSchedulesTable property is initialized: " . get_class($value) . "\n";
}

// Let's check if the table file exists
$tableFile = ROOT . '/src/Model/Table/DoctorSchedulesTable.php';
echo "\n=== Checking table file ===\n";
echo "Looking for: $tableFile\n";
echo "File exists: " . (file_exists($tableFile) ? "Yes" : "No") . "\n";