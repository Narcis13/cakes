<?php
require 'vendor/autoload.php';
require 'config/bootstrap.php';

use Cake\Datasource\ConnectionManager;

$connection = ConnectionManager::get('default');

echo "=== Hospital Holidays ===\n\n";

$holidays = $connection->execute(
    "SELECT * FROM hospital_holidays ORDER BY date"
)->fetchAll('assoc');

foreach ($holidays as $holiday) {
    $date = new \Cake\I18n\Date($holiday['date']);
    $dayName = $date->format('l'); // Day of week
    echo "{$holiday['date']} ({$dayName}): {$holiday['name']}";
    if ($holiday['is_recurring']) {
        echo " [Recurring]";
    }
    if ($holiday['description']) {
        echo " - {$holiday['description']}";
    }
    echo "\n";
}