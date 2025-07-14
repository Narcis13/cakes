<?php
require 'vendor/autoload.php';
require 'config/bootstrap.php';

use Cake\I18n\Date;
use Cake\I18n\Time;
use Cake\Core\Configure;

echo "=== Date Check ===\n";
echo "Today: " . Date::now() . "\n";
echo "Now: " . Time::now() . "\n";
echo "Max advance days: " . Configure::read('Appointments.max_advance_days', 90) . "\n";
echo "Max booking date: " . Date::now()->addDays(90) . "\n\n";

$testDates = [
    'Tomorrow' => Date::now()->addDays(1),
    'Next Monday' => new Date('next monday'),
    'Next Wednesday' => new Date('next wednesday'),
    'In 7 days' => Date::now()->addDays(7),
    'In 30 days' => Date::now()->addDays(30),
    'In 90 days' => Date::now()->addDays(90),
    'In 91 days' => Date::now()->addDays(91),
];

foreach ($testDates as $label => $date) {
    $withinWindow = $date <= Date::now()->addDays(90);
    echo "{$label}: {$date} (day of week: {$date->dayOfWeek}) - " . ($withinWindow ? "✓ Within window" : "✗ Outside window") . "\n";
}