# Appointment Reminder System

This system automatically sends email reminders to patients before their appointments.

## Setup Instructions

### 1. Cron Job Configuration

Add the following cron jobs to your server (run `crontab -e`):

```bash
# Send 24-hour reminders (runs every hour)
0 * * * * cd /path/to/your/project && bin/cake send_appointment_reminders -h 24

# Send 2-hour reminders (runs every 15 minutes)
*/15 * * * * cd /path/to/your/project && bin/cake send_appointment_reminders -h 2

# Optional: Combined reminders (runs every 15 minutes)
*/15 * * * * cd /path/to/your/project && bin/cake send_appointment_reminders -h 24,2
```

### 2. Manual Testing

You can test the reminder system manually:

```bash
# Test 24-hour reminders (dry run)
bin/cake send_appointment_reminders -h 24 --dry-run

# Test 2-hour reminders (dry run)
bin/cake send_appointment_reminders -h 2 --dry-run

# Send actual reminders
bin/cake send_appointment_reminders -h 24

# Send both 24h and 2h reminders
bin/cake send_appointment_reminders -h 24,2
```

### 3. How It Works

1. **24-Hour Reminders**: Sent to patients 24 hours before their confirmed appointment
2. **2-Hour Reminders**: Sent to patients 2 hours before their confirmed appointment
3. **Duplicate Prevention**: The system tracks which reminders have been sent using `reminded_24h` and `reminded_2h` fields
4. **Email Templates**: Uses the reminder templates in `templates/email/html/appointment_reminder.php` and `templates/email/text/appointment_reminder.php`

### 4. Database Fields

The system uses two new fields in the `appointments` table:
- `reminded_24h`: Timestamp when 24-hour reminder was sent
- `reminded_2h`: Timestamp when 2-hour reminder was sent

### 5. Email Configuration

Make sure your SMTP configuration is properly set up in `config/app_local.php`:

```php
'EmailTransport' => [
    'default' => [
        'className' => 'Smtp',
        'host' => env('EMAIL_HOST', 'smtp.gmail.com'),
        'port' => env('EMAIL_PORT', 587),
        'username' => env('EMAIL_USERNAME', 'your-email@gmail.com'),
        'password' => env('EMAIL_PASSWORD', 'your-app-password'),
        'tls' => true,
    ],
],
```

### 6. Environment Variables

Set these environment variables for email configuration:
- `EMAIL_HOST`: SMTP server hostname
- `EMAIL_PORT`: SMTP server port (usually 587 for TLS)
- `EMAIL_USERNAME`: Your email address
- `EMAIL_PASSWORD`: Your email password or app-specific password
- `EMAIL_FROM_ADDRESS`: From email address (e.g., noreply@spital.ro)
- `EMAIL_FROM_NAME`: From name (e.g., Spitalul Municipal)

### 7. Logging

Check the CakePHP logs for any email sending errors:
```bash
tail -f logs/error.log
tail -f logs/debug.log
```

### 8. Troubleshooting

If reminders are not being sent:
1. Check cron job logs: `tail -f /var/log/cron`
2. Verify SMTP configuration
3. Check appointment statuses (only 'confirmed' appointments get reminders)
4. Verify the appointment times are within the reminder window
5. Check that reminders haven't already been sent (`reminded_24h` and `reminded_2h` fields)