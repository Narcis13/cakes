<?php
/*
 * Local configuration file to provide any overrides to your app.php configuration.
 * Copy and save this file as app_local.php and make changes as required.
 * Note: It is not recommended to commit files with credentials such as app_local.php
 * into source code version control.
 */
return [
    /*
     * Debug Level:
     *
     * Production Mode:
     * false: No error messages, errors, or warnings shown.
     *
     * Development Mode:
     * true: Errors and warnings shown.
     */
    'debug' => filter_var(env('DEBUG', true), FILTER_VALIDATE_BOOLEAN),

    /*
     * Security and encryption configuration
     *
     * - salt - A random string used in security hashing methods.
     *   The salt value is also used as the encryption key.
     *   You should treat it as extremely sensitive data.
     */
    'Security' => [
        'salt' => env('SECURITY_SALT', '97de4793d5e250d6a5ac5032627eb2ad425ae674a91153553ae353daf2f8689e'),
    ],

    /*
     * Third-party API Keys
     */
    'ApiKeys' => [
        'tinymce' => env('TINYMCE_API_KEY', 'mw6ldaj3x35183lcdhla0dtj3uqtuv8fxharylsurnqxyy1c'),
        'resend' => env('RESEND_API_KEY', 'your-resend-api-key-here'),
    ],

    /*
     * Connection information used by the ORM to connect
     * to your application's datastores.
     *
     * See app.php for more configuration options.
     */
    'Datasources' => [
        'default' => [
            'host' => '127.0.0.1',
            /*
             * CakePHP will use the default DB port based on the driver selected
             * MySQL on MAMP uses port 8889, MAMP users will want to uncomment
             * the following line and set the port accordingly
             */
            'port' => '8889',

            'username' => 'root',
            'password' => 'root',

            'database' => 'smupitesti',
            /*
             * If not using the default 'public' schema with the PostgreSQL driver
             * set it here.
             */
            //'schema' => 'myapp',

            /*
             * You can use a DSN string to set the entire configuration
             */
           // 'url' => env('DATABASE_URL', null),
        ],

        /*
         * The test connection is used during the test suite.
         */
        'test' => [
            'host' => 'localhost',
            //'port' => 'non_standard_port_number',
            'username' => 'my_app',
            'password' => 'secret',
            'database' => 'test_myapp',
            //'schema' => 'myapp',
            'url' => env('DATABASE_TEST_URL', 'sqlite://127.0.0.1/tmp/tests.sqlite'),
        ],
    ],

    /*
     * Email configuration.
     *
     * Host and credential configuration in case you are using SmtpTransport
     *
     * See app.php for more configuration options.
     */
    'EmailTransport' => [
        'default' => [
            'className' => 'Smtp',
            'host' => env('EMAIL_HOST', 'smtp.gmail.com'),
            'port' => env('EMAIL_PORT', 587),
            'username' => env('EMAIL_USERNAME', 'your-email@gmail.com'),
            'password' => env('EMAIL_PASSWORD', 'your-app-password'),
            'client' => null,
            'tls' => true,
            'timeout' => 30,
            'context' => [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ],
            'url' => env('EMAIL_TRANSPORT_DEFAULT_URL', null),
        ],
    ],
    
    /*
     * Email configuration for sending emails
     */
    'Email' => [
        'default' => [
            'transport' => 'default',
            'from' => [env('EMAIL_FROM_ADDRESS', 'noreply@spital.ro') => env('EMAIL_FROM_NAME', 'Spitalul Municipal')],
            'charset' => 'utf-8',
            'headerCharset' => 'utf-8',
        ],
    ],
    
    /*
     * Appointment System Configuration
     */
    'Appointments' => [
        'min_advance_hours' => 1, // Minimum hours in advance to book
        'max_advance_days' => 90, // Maximum days in advance to book
        'slot_interval' => 30, // Time slot interval in minutes
        'default_start_time' => '09:00:00', // Default working day start
        'default_end_time' => '17:00:00', // Default working day end
        'default_buffer_minutes' => 0, // Default buffer time between appointments
        'confirmation_token_expiry' => 24, // Token expiry in hours
        'default_appointment_status' => 'pending',
        'allow_weekend_appointments' => false,
        'business_hours' => [
            'start' => '08:00',
            'end' => '18:00'
        ],
        'rate_limit' => [
            'attempts' => 10,
            'window' => 3600 // 1 hour in seconds
        ]
    ],
    
    /*
     * Hospital Configuration
     */
    'Hospital' => [
        'name' => 'Spitalul Municipal',
        'phone' => '0123 456 789',
        'address' => 'Strada Sănătății, Nr. 1',
        'email' => 'contact@spital.ro'
    ],
];
