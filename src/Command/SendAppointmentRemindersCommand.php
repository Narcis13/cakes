<?php
declare(strict_types=1);

namespace App\Command;

use App\Mailer\AppointmentMailer;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Datasource\ModelAwareTrait;
use Cake\I18n\DateTime;
use Cake\Log\Log;
use Exception;

/**
 * SendAppointmentReminders command.
 */
class SendAppointmentRemindersCommand extends Command
{
    use ModelAwareTrait;

    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/4/en/console-commands/commands.html#defining-arguments-and-options
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        $parser->setDescription('Send appointment reminder emails to patients');

        $parser->addOption('hours', [
            'short' => 'h',
            'help' => 'Hours before appointment to send reminder (default: 24,2)',
            'default' => '24,2',
        ]);

        $parser->addOption('dry-run', [
            'short' => 'd',
            'help' => 'Show what would be sent without actually sending emails',
            'boolean' => true,
            'default' => false,
        ]);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return int|null|void The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $io->out('Starting appointment reminder process...');

        $hoursString = $args->getOption('hours');
        $hoursArray = array_map('intval', explode(',', $hoursString));
        $dryRun = $args->getOption('dry-run');

        if ($dryRun) {
            $io->info('DRY RUN MODE - No emails will be sent');
        }

        $appointments = $this->fetchTable('Appointments');
        $totalSent = 0;

        foreach ($hoursArray as $hours) {
            $io->out("Processing {$hours}-hour reminders...");

            // Calculate the target datetime
            $targetDateTime = DateTime::now()->addHours($hours);
            $startRange = $targetDateTime->copy()->subMinutes(30);
            $endRange = $targetDateTime->copy()->addMinutes(30);

            // Find appointments in the target time range that are confirmed and haven't been reminded for this specific hour
            $whereConditions = [
                'status' => 'confirmed',
                'appointment_date' => $targetDateTime->format('Y-m-d'),
                'appointment_time >=' => $startRange->format('H:i:s'),
                'appointment_time <=' => $endRange->format('H:i:s'),
            ];

            // Add specific reminder condition based on hours
            if ($hours === 24) {
                $whereConditions['reminded_24h IS'] = null;
            } elseif ($hours === 2) {
                $whereConditions['reminded_2h IS'] = null;
            }

            $appointmentsToRemind = $appointments->find()
                ->contain([
                    'Doctors' => ['Departments'],
                    'Services',
                ])
                ->where($whereConditions)
                ->toArray();

            $io->out('Found ' . count($appointmentsToRemind) . " appointments for {$hours}-hour reminders");

            foreach ($appointmentsToRemind as $appointment) {
                try {
                    if (!$dryRun) {
                        // Send reminder email
                        $mailer = new AppointmentMailer();
                        $mailer->reminder($appointment, $hours)->send();

                        // Update the appointment to mark reminder as sent
                        if ($hours === 24) {
                            $appointment->reminded_24h = DateTime::now();
                        } elseif ($hours === 2) {
                            $appointment->reminded_2h = DateTime::now();
                        }

                        $appointments->save($appointment);
                        $totalSent++;

                        $io->verbose("✓ Reminder sent to {$appointment->patient_email} ({$hours}h before)");
                    } else {
                        $io->out("Would send {$hours}h reminder to: {$appointment->patient_name} <{$appointment->patient_email}>");
                        $io->out("  Appointment: {$appointment->appointment_date->format('Y-m-d')} at {$appointment->appointment_time->format('H:i')}");

                        if (!empty($appointment->doctors)) {
                            $io->out("  Doctor: {$appointment->doctors->first_name} {$appointment->doctors->last_name}");
                        }

                        $totalSent++;
                    }
                } catch (Exception $e) {
                    $io->error("Failed to send reminder to {$appointment->patient_email}: " . $e->getMessage());
                    Log::error('Appointment reminder error: ' . $e->getMessage());
                }
            }
        }

        if ($dryRun) {
            $io->success("DRY RUN: Would have sent {$totalSent} reminder emails");
        } else {
            $io->success("Successfully sent {$totalSent} reminder emails");
        }

        return static::CODE_SUCCESS;
    }
}
