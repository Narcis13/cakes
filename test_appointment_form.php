<?php
// Simple test to check if appointment creation works
require 'vendor/autoload.php';
require 'config/bootstrap.php';

use Cake\ORM\TableRegistry;
use Cake\I18n\Date;
use Cake\I18n\Time;
use Cake\Utility\Security;

$appointmentsTable = TableRegistry::getTableLocator()->get('Appointments');

// Test data
$testData = [
    'doctor_id' => 1, // Dr. Popescu Ion
    'service_id' => 1, // Consult oftalmologic
    'appointment_date' => '2025-07-16', // Next Wednesday
    'appointment_time' => '14:00:00',
    'end_time' => '14:15:00',
    'patient_name' => 'Test Patient',
    'patient_email' => 'test@example.com',
    'patient_phone' => '0722123456',
    'patient_cnp' => '1234567890123',
    'notes' => 'Test appointment',
    'status' => 'pending',
    'confirmation_token' => Security::randomString(64)
];

echo "=== Testing Appointment Creation ===\n\n";

// Create new entity
$appointment = $appointmentsTable->newEntity($testData);

// Check for validation errors
if ($appointment->hasErrors()) {
    echo "Validation errors found:\n";
    print_r($appointment->getErrors());
} else {
    echo "No validation errors\n";
}

// Try to save
if ($appointmentsTable->save($appointment)) {
    echo "Appointment saved successfully! ID: " . $appointment->id . "\n";
    
    // Delete the test appointment
    $appointmentsTable->delete($appointment);
    echo "Test appointment deleted\n";
} else {
    echo "Failed to save appointment\n";
    if ($appointment->hasErrors()) {
        echo "Errors after save attempt:\n";
        print_r($appointment->getErrors());
    }
}

// Check what fields are required
echo "\n=== Checking Appointments Table Validation Rules ===\n";
$validator = $appointmentsTable->getValidator();
$schema = $appointmentsTable->getSchema();

echo "Table columns:\n";
foreach ($schema->columns() as $column) {
    $columnSchema = $schema->getColumn($column);
    $nullable = $columnSchema['null'] ? 'nullable' : 'NOT NULL';
    echo "  - {$column}: {$columnSchema['type']} ({$nullable})\n";
}