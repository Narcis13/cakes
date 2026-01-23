<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddEmailPlatformSettings extends BaseMigration
{
    /**
     * Up Method.
     *
     * @return void
     */
    public function up(): void
    {
        $now = date('Y-m-d H:i:s');

        $settings = [
            [
                'key_name' => 'PLATFORMA_EMAIL',
                'value' => 'RESEND',
                'description' => 'Platforma de email (RESEND sau SMTP)',
                'created' => $now,
                'modified' => $now,
            ],
            [
                'key_name' => 'SMTP_HOST',
                'value' => '',
                'description' => 'Server SMTP (ex: smtp.gmail.com)',
                'created' => $now,
                'modified' => $now,
            ],
            [
                'key_name' => 'SMTP_PORT',
                'value' => '587',
                'description' => 'Port SMTP (ex: 587, 465, 26)',
                'created' => $now,
                'modified' => $now,
            ],
            [
                'key_name' => 'SMTP_USER',
                'value' => '',
                'description' => 'Utilizator SMTP',
                'created' => $now,
                'modified' => $now,
            ],
            [
                'key_name' => 'SMTP_PASSWORD',
                'value' => '',
                'description' => 'Parolă SMTP',
                'created' => $now,
                'modified' => $now,
            ],
            [
                'key_name' => 'SMTP_TLS',
                'value' => '1',
                'description' => 'Activează TLS pentru SMTP (1 = da, 0 = nu)',
                'created' => $now,
                'modified' => $now,
            ],
            [
                'key_name' => 'EMAIL_FROM_ADDRESS',
                'value' => 'noreply@smupitesti.online',
                'description' => 'Adresa de email expeditor',
                'created' => $now,
                'modified' => $now,
            ],
            [
                'key_name' => 'EMAIL_FROM_NAME',
                'value' => 'Spitalul Militar Pitesti',
                'description' => 'Numele expeditorului',
                'created' => $now,
                'modified' => $now,
            ],
        ];

        $table = $this->table('site_settings');
        $table->insert($settings)->saveData();
    }

    /**
     * Down Method.
     *
     * @return void
     */
    public function down(): void
    {
        $this->execute("DELETE FROM site_settings WHERE key_name IN (
            'PLATFORMA_EMAIL',
            'SMTP_HOST',
            'SMTP_PORT',
            'SMTP_USER',
            'SMTP_PASSWORD',
            'SMTP_TLS',
            'EMAIL_FROM_ADDRESS',
            'EMAIL_FROM_NAME'
        )");
    }
}
