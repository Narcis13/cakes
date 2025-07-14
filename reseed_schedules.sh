#!/bin/bash

echo "Clearing existing doctor schedules..."
echo "TRUNCATE TABLE doctor_schedules;" | mysql -u root -p'password' cakes 2>/dev/null || echo "Could not connect to MySQL directly"

echo "Running new seed..."
bin/cake migrations seed --seed DoctorSchedulesSeed

echo "Done! Doctor schedules have been reseeded with more comprehensive data."