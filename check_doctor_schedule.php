<?php
require 'vendor/autoload.php';
require 'config/bootstrap.php';

use Cake\Datasource\ConnectionManager;

$connection = ConnectionManager::get('default');

// Find Dr. Popescu Ion
echo "=== Finding Dr. Popescu Ion ===\n";
$staff = $connection->execute("SELECT * FROM staff WHERE (first_name LIKE '%Popescu%' AND last_name LIKE '%Ion%') OR (first_name LIKE '%Ion%' AND last_name LIKE '%Popescu%') OR CONCAT(first_name, ' ', last_name) LIKE '%Popescu%Ion%'")->fetchAll('assoc');
if (empty($staff)) {
    echo "Dr. Popescu Ion not found. Let's see all doctors:\n";
    $staff = $connection->execute("SELECT *, CONCAT(first_name, ' ', last_name) as full_name FROM staff WHERE staff_type = 'doctor'")->fetchAll('assoc');
}

foreach ($staff as $doctor) {
    $doctorName = $doctor['full_name'] ?? ($doctor['first_name'] . ' ' . $doctor['last_name']);
    echo "Doctor: {$doctorName} (ID: {$doctor['id']})\n";
    
    // Get schedules for this doctor
    echo "\n--- Schedules for {$doctorName} ---\n";
    $schedules = $connection->execute(
        "SELECT ds.*, s.name as service_name, s.duration_minutes 
         FROM doctor_schedules ds 
         JOIN services s ON ds.service_id = s.id 
         WHERE ds.staff_id = ? AND ds.is_active = 1
         ORDER BY ds.day_of_week, ds.start_time",
        [$doctor['id']]
    )->fetchAll('assoc');
    
    if (empty($schedules)) {
        echo "No active schedules found.\n";
    } else {
        $days = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday'];
        foreach ($schedules as $schedule) {
            echo sprintf(
                "%s: %s - %s | Service: %s (%d min) | Buffer: %d min\n",
                $days[$schedule['day_of_week']],
                $schedule['start_time'],
                $schedule['end_time'],
                $schedule['service_name'],
                $schedule['duration_minutes'],
                $schedule['buffer_minutes']
            );
        }
    }
    echo "\n";
}

// Check slot interval configuration
echo "\n=== Checking Slot Interval Configuration ===\n";
$configFile = file_get_contents('config/app.php');
if (strpos($configFile, 'slot_interval') !== false) {
    echo "Found slot_interval in config/app.php\n";
} else {
    echo "slot_interval not found in config/app.php - using default 30 minutes\n";
}

$localConfigFile = @file_get_contents('config/app_local.php');
if ($localConfigFile && strpos($localConfigFile, 'slot_interval') !== false) {
    echo "Found slot_interval in config/app_local.php\n";
}