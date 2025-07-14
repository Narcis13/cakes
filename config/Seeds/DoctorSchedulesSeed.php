<?php
declare(strict_types=1);

use Migrations\BaseSeed;
use Cake\I18n\Time;

/**
 * DoctorSchedules seed.
 */
class DoctorSchedulesSeed extends BaseSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/migrations/4/en/seeding.html
     *
     * @return void
     */
    public function run(): void
    {
        // Get all doctors
        $conn = $this->getAdapter();
        
        // Clear existing schedules first
        $conn->execute("DELETE FROM doctor_schedules");
        echo "Cleared existing doctor schedules.\n";
        
        $doctors = $conn->fetchAll("SELECT * FROM staff WHERE staff_type = 'doctor' AND is_active = 1");

        // Get all services
        $services = $conn->fetchAll("SELECT * FROM services WHERE is_active = 1");

        if (empty($doctors) || empty($services)) {
            echo "No doctors or services found. Please run StaffSeed and ServicesSeed first.\n";
            return;
        }

        $data = [];
        
        // Define different schedule patterns
        $schedulePatterns = [
            // Standard full-time schedule
            'standard' => [
                'days' => [1, 2, 3, 4, 5], // Mon-Fri
                'hours' => [
                    ['start' => '09:00:00', 'end' => '13:00:00'], // Morning
                    ['start' => '14:00:00', 'end' => '18:00:00'], // Afternoon
                ]
            ],
            // Early bird schedule
            'early' => [
                'days' => [1, 2, 3, 4, 5], // Mon-Fri
                'hours' => [
                    ['start' => '07:00:00', 'end' => '12:00:00'], // Early morning
                    ['start' => '13:00:00', 'end' => '16:00:00'], // Early afternoon
                ]
            ],
            // Part-time schedule
            'parttime' => [
                'days' => [1, 3, 5], // Mon, Wed, Fri
                'hours' => [
                    ['start' => '09:00:00', 'end' => '14:00:00'], // Morning only
                ]
            ],
            // Extended hours
            'extended' => [
                'days' => [1, 2, 3, 4, 5], // Mon-Fri
                'hours' => [
                    ['start' => '08:00:00', 'end' => '12:00:00'], // Morning
                    ['start' => '13:00:00', 'end' => '20:00:00'], // Extended afternoon
                ]
            ],
        ];
        
        $doctorIndex = 0;
        foreach ($doctors as $doctor) {
            // Assign different patterns to different doctors
            $patternKeys = array_keys($schedulePatterns);
            $pattern = $schedulePatterns[$patternKeys[$doctorIndex % count($patternKeys)]];
            
            foreach ($services as $service) {
                foreach ($pattern['days'] as $day) {
                    foreach ($pattern['hours'] as $hours) {
                        $data[] = [
                            'staff_id' => $doctor['id'],
                            'day_of_week' => $day,
                            'start_time' => $hours['start'],
                            'end_time' => $hours['end'],
                            'service_id' => $service['id'],
                            'max_appointments' => 1,
                            'slot_duration' => null, // Use service default
                            'buffer_minutes' => 15, // 15 minutes between appointments
                            'is_active' => true,
                            'created' => date('Y-m-d H:i:s'),
                            'modified' => date('Y-m-d H:i:s'),
                        ];
                    }
                }
                
                // Some doctors also work on Saturday
                if ($doctorIndex % 3 === 0) { // Every third doctor
                    $data[] = [
                        'staff_id' => $doctor['id'],
                        'day_of_week' => 6, // Saturday
                        'start_time' => '09:00:00',
                        'end_time' => '13:00:00',
                        'service_id' => $service['id'],
                        'max_appointments' => 1,
                        'slot_duration' => null,
                        'buffer_minutes' => 15,
                        'is_active' => true,
                        'created' => date('Y-m-d H:i:s'),
                        'modified' => date('Y-m-d H:i:s'),
                    ];
                }
                
                // Some doctors work on Sunday (emergency/special cases)
                if ($doctorIndex % 5 === 0) { // Every fifth doctor
                    $data[] = [
                        'staff_id' => $doctor['id'],
                        'day_of_week' => 7, // Sunday
                        'start_time' => '10:00:00',
                        'end_time' => '14:00:00',
                        'service_id' => $service['id'],
                        'max_appointments' => 1,
                        'slot_duration' => null,
                        'buffer_minutes' => 20, // Longer buffer on weekends
                        'is_active' => true,
                        'created' => date('Y-m-d H:i:s'),
                        'modified' => date('Y-m-d H:i:s'),
                    ];
                }
            }
            
            $doctorIndex++;
        }

        $table = $this->table('doctor_schedules');
        $table->insert($data)->save();
        
        echo "Created " . count($data) . " doctor schedule entries.\n";
    }
}