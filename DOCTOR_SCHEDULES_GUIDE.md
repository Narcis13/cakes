# Doctor Schedules Configuration Guide

## Overview
The appointment booking system now enforces doctor schedules. Patients can only book appointments during the configured working hours for each doctor.

## How It Works

### Schedule Validation
When a patient selects a date and time during appointment booking:
1. The system checks if the doctor works on that day of the week
2. Verifies the selected time is within the doctor's working hours
3. Checks for schedule exceptions (days off, holidays)
4. Ensures no conflicts with existing appointments
5. Applies buffer time between appointments

### Default Configuration
- **Working Days**: Monday to Friday (some doctors also Saturday)
- **Working Hours**: 9:00 AM - 5:00 PM (Saturday: 9:00 AM - 1:00 PM)
- **Buffer Time**: 15 minutes between appointments
- **Slot Duration**: Based on service duration

## Managing Doctor Schedules

### Admin Panel
1. Navigate to **Admin Panel** > **Doctor Schedules**
2. Here you can:
   - View all doctor schedules
   - Add new schedules for doctors
   - Edit existing schedules
   - Deactivate schedules
   - Set service-specific schedules

### Schedule Components

1. **Regular Schedules** (`doctor_schedules` table)
   - Day of week (1=Monday, 7=Sunday)
   - Start and end times
   - Service-specific schedules
   - Buffer minutes between appointments

2. **Schedule Exceptions** (`schedule_exceptions` table)
   - Specific dates where regular schedule doesn't apply
   - Can mark days as working/not working
   - Different hours for exception days

3. **Staff Unavailabilities** (`staff_unavailabilities` table)
   - Vacation days or sick leave
   - All-day or time-specific unavailabilities

4. **Hospital Holidays** (`hospital_holidays` table)
   - System-wide holidays where no appointments are available

## Configuration Options

Edit `config/app_local.php` to customize:

```php
'Appointments' => [
    'slot_interval' => 30, // Time slot intervals in minutes
    'min_advance_hours' => 1, // Minimum hours before appointment
    'max_advance_days' => 90, // Maximum days in advance
    'allow_weekend_appointments' => false, // Enable weekend bookings
    'default_buffer_minutes' => 15, // Default buffer between appointments
    'default_start_time' => '09:00:00',
    'default_end_time' => '17:00:00',
],
```

## Testing

1. Visit `/appointments` as a patient
2. Select a specialty and doctor
3. In Step 3, you'll only see available time slots based on:
   - Doctor's schedule for that day
   - Existing appointments
   - Configured constraints

## Troubleshooting

### No Time Slots Available
- Check if doctor has schedules configured
- Verify the selected date is not a holiday
- Ensure doctor is not marked as unavailable
- Check if service is linked to the doctor

### All Time Slots Shown as Available
- Run the seed: `bin/cake migrations seed --seed DoctorSchedulesSeed`
- Verify schedules exist in admin panel
- Check if `AvailabilityService` is being used correctly