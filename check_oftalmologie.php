<?php
require 'vendor/autoload.php';
require 'config/bootstrap.php';

use Cake\Datasource\ConnectionManager;

$connection = ConnectionManager::get('default');

echo "=== Checking Oftalmologie Doctors ===\n\n";

// Check all doctors with Oftalmologie specialty
$doctors = $connection->execute(
    "SELECT * FROM staff WHERE specialization LIKE '%Oftalmolog%' AND is_active = 1"
)->fetchAll('assoc');

echo "Found " . count($doctors) . " active Oftalmologie doctors:\n";
foreach ($doctors as $doctor) {
    echo "- {$doctor['first_name']} {$doctor['last_name']} (ID: {$doctor['id']}, Specialization: {$doctor['specialization']})\n";
}

// Check exact specializations
echo "\n=== All unique specializations in database ===\n";
$specializations = $connection->execute(
    "SELECT DISTINCT specialization FROM staff WHERE staff_type = 'doctor' AND is_active = 1 ORDER BY specialization"
)->fetchAll('assoc');

foreach ($specializations as $spec) {
    echo "- {$spec['specialization']}\n";
}