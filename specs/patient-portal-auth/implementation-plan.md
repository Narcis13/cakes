# Implementation Plan: Patient Portal Authentication System

## Overview

Build a complete patient authentication system with registration, email verification, login, password reset, and a portal for managing appointments. Restrict appointment booking to authenticated patients only. All UI in Romanian.

---

## Phase 1: Database Schema

Create the patients table and link appointments to patients.

### Tasks

- [ ] Create migration for `patients` table with all required fields
- [ ] Create migration to add `patient_id` foreign key to `appointments` table
- [ ] Run migrations to apply schema changes

### Technical Details

**Migration 1: CreatePatients**

File: `config/Migrations/20260114000001_CreatePatients.php`

```php
<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreatePatients extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('patients');
        $table
            ->addColumn('full_name', 'string', ['limit' => 100, 'null' => false])
            ->addColumn('email', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('phone', 'string', ['limit' => 20, 'null' => false])
            ->addColumn('password', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('email_verified_at', 'datetime', ['null' => true, 'default' => null])
            ->addColumn('verification_token', 'string', ['limit' => 64, 'null' => true, 'default' => null])
            ->addColumn('password_reset_token', 'string', ['limit' => 64, 'null' => true, 'default' => null])
            ->addColumn('password_reset_expires', 'datetime', ['null' => true, 'default' => null])
            ->addColumn('failed_login_attempts', 'integer', ['default' => 0, 'null' => false])
            ->addColumn('locked_until', 'datetime', ['null' => true, 'default' => null])
            ->addColumn('last_login_at', 'datetime', ['null' => true, 'default' => null])
            ->addColumn('last_login_ip', 'string', ['limit' => 45, 'null' => true, 'default' => null])
            ->addColumn('is_active', 'boolean', ['default' => true, 'null' => false])
            ->addColumn('created', 'datetime', ['null' => false])
            ->addColumn('modified', 'datetime', ['null' => false])
            ->addIndex(['email'], ['unique' => true, 'name' => 'idx_patients_email'])
            ->addIndex(['verification_token'], ['name' => 'idx_patients_verification_token'])
            ->addIndex(['password_reset_token'], ['name' => 'idx_patients_reset_token'])
            ->create();
    }
}
```

**Migration 2: AddPatientIdToAppointments**

File: `config/Migrations/20260114000002_AddPatientIdToAppointments.php`

```php
<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddPatientIdToAppointments extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('appointments');
        $table
            ->addColumn('patient_id', 'integer', ['null' => true, 'default' => null, 'after' => 'id'])
            ->addForeignKey('patient_id', 'patients', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'CASCADE',
            ])
            ->addIndex(['patient_id'], ['name' => 'idx_appointments_patient_id'])
            ->update();
    }
}
```

**CLI Commands:**
```bash
bin/cake migrations migrate
bin/cake migrations status
```

---

## Phase 2: Patient Model

Create the Patient entity and table class with validation and finders.

### Tasks

- [ ] Create Patient entity with password hashing mutator
- [ ] Create PatientsTable with validation rules and associations
- [ ] Update AppointmentsTable to add `belongsTo('Patients')` association
- [ ] Update Appointment entity to include `patient_id` in accessible fields

### Technical Details

**Patient Entity**

File: `src/Model/Entity/Patient.php`

```php
<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\I18n\DateTime;
use Cake\ORM\Entity;

class Patient extends Entity
{
    protected array $_accessible = [
        'full_name' => true,
        'email' => true,
        'phone' => true,
        'password' => true,
        // Security fields NOT accessible via mass assignment
    ];

    protected array $_hidden = [
        'password',
        'verification_token',
        'password_reset_token',
    ];

    protected function _setPassword(string $password): string
    {
        $hasher = new DefaultPasswordHasher();
        return $hasher->hash($password);
    }

    protected function _getIsEmailVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    protected function _getIsLocked(): bool
    {
        if ($this->locked_until === null) {
            return false;
        }
        return $this->locked_until > DateTime::now();
    }
}
```

**PatientsTable Validation**

File: `src/Model/Table/PatientsTable.php`

Key validation rules:
- `full_name`: required, maxLength 100
- `email`: required, email format, unique
- `phone`: required, maxLength 20
- `password`: required on create, minLength 8

```php
public function validationDefault(Validator $validator): Validator
{
    $validator
        ->scalar('full_name')
        ->maxLength('full_name', 100)
        ->requirePresence('full_name', 'create')
        ->notEmptyString('full_name');

    $validator
        ->email('email')
        ->maxLength('email', 255)
        ->requirePresence('email', 'create')
        ->notEmptyString('email');

    $validator
        ->scalar('phone')
        ->maxLength('phone', 20)
        ->requirePresence('phone', 'create')
        ->notEmptyString('phone');

    $validator
        ->scalar('password')
        ->minLength('password', 8, 'Parola trebuie să aibă minim 8 caractere.')
        ->requirePresence('password', 'create')
        ->notEmptyString('password');

    return $validator;
}
```

**Custom Finders:**
- `findActive()`: where `is_active = true` AND `email_verified_at IS NOT NULL`
- `findByVerificationToken($token)`
- `findByPasswordResetToken($token)`

---

## Phase 3: Patient Authentication Service

Create the service class handling authentication logic, rate limiting, and token management.

### Tasks

- [ ] Create PatientAuthService with login rate limiting (follows LoginSecurityService pattern)
- [ ] Implement verification token generation and validation
- [ ] Implement password reset token generation and validation
- [ ] Add methods for tracking login attempts and lockouts

### Technical Details

**PatientAuthService**

File: `src/Service/PatientAuthService.php`

```php
<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\Patient;
use App\Model\Table\LoginAttemptsTable;
use App\Model\Table\PatientsTable;
use Cake\I18n\DateTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Utility\Security;

class PatientAuthService
{
    use LocatorAwareTrait;

    public const MAX_FAILED_ATTEMPTS = 3;
    public const LOCKOUT_DURATION = 3600; // 1 hour
    public const VERIFICATION_TOKEN_EXPIRY = 86400; // 24 hours
    public const PASSWORD_RESET_EXPIRY = 3600; // 1 hour

    private PatientsTable $patientsTable;
    private LoginAttemptsTable $loginAttemptsTable;

    public function __construct()
    {
        $this->patientsTable = $this->fetchTable('Patients');
        $this->loginAttemptsTable = $this->fetchTable('LoginAttempts');
    }

    public function isLoginAllowed(string $email, string $ipAddress): array
    {
        // Check account lockout
        $patient = $this->patientsTable->findByEmail($email)->first();
        if ($patient && $patient->is_locked) {
            $minutesLeft = ceil(($patient->locked_until->getTimestamp() - time()) / 60);
            return [
                'allowed' => false,
                'reason' => "Contul este blocat. Încercați din nou în {$minutesLeft} minute.",
            ];
        }

        return ['allowed' => true];
    }

    public function generateVerificationToken(): string
    {
        return Security::randomString(64);
    }

    public function generatePasswordResetToken(Patient $patient): string
    {
        $token = Security::randomString(64);
        $patient->password_reset_token = $token;
        $patient->password_reset_expires = DateTime::now()->addHour();
        $this->patientsTable->save($patient);
        return $token;
    }

    public function verifyEmail(string $token): ?Patient
    {
        $patient = $this->patientsTable->find()
            ->where(['verification_token' => $token])
            ->first();

        if (!$patient) {
            return null;
        }

        $patient->email_verified_at = DateTime::now();
        $patient->verification_token = null;
        $this->patientsTable->save($patient);

        return $patient;
    }

    public function validatePasswordResetToken(string $token): ?Patient
    {
        $patient = $this->patientsTable->find()
            ->where([
                'password_reset_token' => $token,
                'password_reset_expires >=' => DateTime::now(),
            ])
            ->first();

        return $patient;
    }

    public function resetPassword(string $token, string $newPassword): bool
    {
        $patient = $this->validatePasswordResetToken($token);
        if (!$patient) {
            return false;
        }

        $patient->password = $newPassword;
        $patient->password_reset_token = null;
        $patient->password_reset_expires = null;
        $patient->failed_login_attempts = 0;
        $patient->locked_until = null;

        return (bool)$this->patientsTable->save($patient);
    }

    public function recordFailedAttempt(string $email): void
    {
        $patient = $this->patientsTable->findByEmail($email)->first();
        if (!$patient) {
            return;
        }

        $patient->failed_login_attempts += 1;

        if ($patient->failed_login_attempts >= self::MAX_FAILED_ATTEMPTS) {
            $patient->locked_until = DateTime::now()->addSeconds(self::LOCKOUT_DURATION);
        }

        $this->patientsTable->save($patient);
    }

    public function clearFailedAttempts(Patient $patient): void
    {
        $patient->failed_login_attempts = 0;
        $patient->locked_until = null;
        $this->patientsTable->save($patient);
    }

    public function updateLastLogin(Patient $patient, string $ipAddress): void
    {
        $patient->last_login_at = DateTime::now();
        $patient->last_login_ip = $ipAddress;
        $this->patientsTable->save($patient);
    }
}
```

---

## Phase 4: Patient Mailer

Create email templates for verification, password reset, and welcome messages.

### Tasks

- [ ] Create PatientMailer class with verification, passwordReset, and welcome methods
- [ ] Create HTML email template for patient verification (Romanian)
- [ ] Create HTML email template for password reset (Romanian)
- [ ] Create HTML email template for welcome message (Romanian)

### Technical Details

**PatientMailer**

File: `src/Mailer/PatientMailer.php`

```php
<?php
declare(strict_types=1);

namespace App\Mailer;

use App\Model\Entity\Patient;
use Cake\Mailer\Mailer;
use Cake\Routing\Router;

class PatientMailer extends Mailer
{
    public function verification(Patient $patient, string $token): void
    {
        $verifyUrl = Router::url([
            'controller' => 'Patients',
            'action' => 'verifyEmail',
            $token,
        ], true);

        $this
            ->setTo($patient->email)
            ->setSubject('Verificare cont - Spitalul Militar Pitești')
            ->setEmailFormat('both')
            ->setViewVars([
                'patient' => $patient,
                'verifyUrl' => $verifyUrl,
            ])
            ->viewBuilder()
            ->setTemplate('patient_verification');
    }

    public function passwordReset(Patient $patient, string $token): void
    {
        $resetUrl = Router::url([
            'controller' => 'Patients',
            'action' => 'resetPassword',
            $token,
        ], true);

        $this
            ->setTo($patient->email)
            ->setSubject('Resetare parolă - Spitalul Militar Pitești')
            ->setEmailFormat('both')
            ->setViewVars([
                'patient' => $patient,
                'resetUrl' => $resetUrl,
            ])
            ->viewBuilder()
            ->setTemplate('patient_password_reset');
    }

    public function welcome(Patient $patient): void
    {
        $this
            ->setTo($patient->email)
            ->setSubject('Bine ați venit - Spitalul Militar Pitești')
            ->setEmailFormat('both')
            ->setViewVars(['patient' => $patient])
            ->viewBuilder()
            ->setTemplate('patient_welcome');
    }
}
```

**Email Template Files:**
- `templates/email/html/patient_verification.php`
- `templates/email/html/patient_password_reset.php`
- `templates/email/html/patient_welcome.php`
- `templates/email/text/patient_verification.php`
- `templates/email/text/patient_password_reset.php`
- `templates/email/text/patient_welcome.php`

---

## Phase 5: Authentication Configuration

Configure CakePHP Authentication to support both patient and admin contexts.

### Tasks

- [ ] Update Application.php to detect request path and return appropriate auth service
- [ ] Create patient authentication service using Patients table with separate session key
- [ ] Configure patient auth to use `findActive` finder (only verified patients)

### Technical Details

**Application.php Changes**

File: `src/Application.php`

Modify `getAuthenticationService()` method:

```php
public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
{
    $path = $request->getUri()->getPath();

    // Patient portal and appointment routes use patient authentication
    if (str_starts_with($path, '/portal') || str_starts_with($path, '/appointments')) {
        return $this->getPatientAuthenticationService($request);
    }

    // Default: Admin authentication (existing code)
    return $this->getAdminAuthenticationService($request);
}

private function getPatientAuthenticationService(ServerRequestInterface $request): AuthenticationService
{
    $service = new AuthenticationService();

    $service->setConfig([
        'unauthenticatedRedirect' => Router::url([
            'controller' => 'Patients',
            'action' => 'login',
        ]),
        'queryParam' => 'redirect',
    ]);

    $fields = [
        'username' => 'email',
        'password' => 'password',
    ];

    // Separate session key for patients
    $service->loadAuthenticator('Authentication.Session', [
        'sessionKey' => 'PatientAuth',
    ]);

    $service->loadAuthenticator('Authentication.Form', [
        'fields' => $fields,
        'loginUrl' => Router::url([
            'controller' => 'Patients',
            'action' => 'login',
        ]),
    ]);

    $service->loadIdentifier('Authentication.Password', [
        'fields' => $fields,
        'resolver' => [
            'className' => 'Authentication.Orm',
            'userModel' => 'Patients',
            'finder' => 'active', // Only verified, active patients
        ],
    ]);

    return $service;
}

// Rename existing getAuthenticationService content to:
private function getAdminAuthenticationService(ServerRequestInterface $request): AuthenticationService
{
    // ... existing admin auth code ...
}
```

---

## Phase 6: Routes Configuration

Define all patient portal routes.

### Tasks

- [ ] Update routes.php with complete patient portal routes
- [ ] Configure route patterns for tokens (alphanumeric)
- [ ] Set POST-only method for cancel appointment action

### Technical Details

**Routes Configuration**

File: `config/routes.php`

Add/update in the main scope:

```php
// Patient Portal Routes
$builder->connect('/portal', ['controller' => 'Patients', 'action' => 'portal']);
$builder->connect('/portal/login', ['controller' => 'Patients', 'action' => 'login']);
$builder->connect('/portal/register', ['controller' => 'Patients', 'action' => 'register']);
$builder->connect('/portal/logout', ['controller' => 'Patients', 'action' => 'logout']);

$builder->connect('/portal/forgot-password', ['controller' => 'Patients', 'action' => 'forgotPassword']);

$builder->connect('/portal/reset-password/{token}', ['controller' => 'Patients', 'action' => 'resetPassword'])
    ->setPass(['token'])
    ->setPatterns(['token' => '[a-zA-Z0-9]+']);

$builder->connect('/portal/verify-email/{token}', ['controller' => 'Patients', 'action' => 'verifyEmail'])
    ->setPass(['token'])
    ->setPatterns(['token' => '[a-zA-Z0-9]+']);

$builder->connect('/portal/appointments', ['controller' => 'Patients', 'action' => 'appointments']);

$builder->connect('/portal/appointments/cancel/{id}', ['controller' => 'Patients', 'action' => 'cancelAppointment'])
    ->setPass(['id'])
    ->setPatterns(['id' => '\d+'])
    ->setMethods(['POST']);

$builder->connect('/portal/profile', ['controller' => 'Patients', 'action' => 'profile']);
```

---

## Phase 7: Patients Controller [complex]

Create the main controller handling all patient portal functionality.

### Tasks

- [ ] Create PatientsController with initialize() and beforeFilter() setup
- [ ] Implement register action (GET form, POST create patient + send verification)
- [ ] Implement login action (GET form, POST authenticate with rate limiting)
- [ ] Implement logout action
- [ ] Implement verifyEmail action (validate token, activate account)
- [ ] Implement forgotPassword action (GET form, POST send reset email)
- [ ] Implement resetPassword action (GET form with token, POST update password)
- [ ] Implement portal action (dashboard with appointment summary)
- [ ] Implement appointments action (list all patient appointments)
- [ ] Implement cancelAppointment action (POST cancel with validation)
- [ ] Implement profile action (GET view, POST update)

### Technical Details

**PatientsController Structure**

File: `src/Controller/PatientsController.php`

```php
<?php
declare(strict_types=1);

namespace App\Controller;

use App\Mailer\PatientMailer;
use App\Service\PatientAuthService;
use Cake\Event\EventInterface;

class PatientsController extends AppController
{
    private PatientAuthService $authService;

    public function initialize(): void
    {
        parent::initialize();
        $this->authService = new PatientAuthService();

        // Public actions (no auth required)
        $this->Authentication->allowUnauthenticated([
            'login',
            'register',
            'logout',
            'forgotPassword',
            'resetPassword',
            'verifyEmail',
        ]);
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        // Use portal layout for authenticated pages
        $action = $this->request->getParam('action');
        $publicActions = ['login', 'register', 'forgotPassword', 'resetPassword', 'verifyEmail'];
        if (!in_array($action, $publicActions)) {
            $this->viewBuilder()->setLayout('portal');
        }
    }

    // ... action implementations ...
}
```

**Key Action Logic:**

**register():**
1. GET: Display registration form
2. POST: Validate data, create patient with verification token, send email
3. Redirect to login with success message

**login():**
1. GET: Display login form (redirect if already logged in)
2. POST: Check rate limiting, attempt authentication
3. On success: clear attempts, update last login, redirect to portal
4. On failure: record attempt, show error

**Romanian Flash Messages:**
- Registration success: "Contul a fost creat. Verificați email-ul pentru activare."
- Login error: "Email sau parolă incorectă."
- Account locked: "Contul este blocat temporar."
- Email verified: "Email verificat cu succes. Vă puteți autentifica."
- Password reset sent: "Link-ul de resetare a fost trimis pe email."
- Password reset success: "Parola a fost schimbată cu succes."
- Profile updated: "Profilul a fost actualizat."
- Appointment cancelled: "Programarea a fost anulată."

---

## Phase 8: Portal Templates [complex]

Create all patient portal templates in Romanian.

### Tasks

- [ ] Create portal layout with navigation (Dashboard, Programări, Profil, Deconectare)
- [ ] Create login.php template with email/password form
- [ ] Create register.php template with full registration form
- [ ] Create forgot_password.php template
- [ ] Create reset_password.php template
- [ ] Create verify_email.php template (success/error states)
- [ ] Create portal.php dashboard template with appointment summary
- [ ] Create appointments.php template with list and cancel buttons
- [ ] Create profile.php template with view/edit form

### Technical Details

**Portal Layout**

File: `templates/layout/portal.php`

Navigation items:
- Panou de control (`/portal`)
- Programările mele (`/portal/appointments`)
- Profil (`/portal/profile`)
- Deconectare (`/portal/logout`)

**Form Labels (Romanian):**

| Field | Label |
|-------|-------|
| full_name | Nume complet |
| email | Adresă de email |
| phone | Număr de telefon |
| password | Parolă |
| password_confirm | Confirmă parola |

**Button Labels:**
- Autentificare (Login)
- Înregistrare (Register)
- Trimite (Submit)
- Anulează (Cancel)
- Salvează (Save)
- Înapoi (Back)

**Status Labels:**
- pending: În așteptare
- confirmed: Confirmat
- cancelled: Anulat
- completed: Finalizat
- no-show: Neprezentare

---

## Phase 9: Update Appointments Controller

Modify appointments to require patient authentication and link to patient_id.

### Tasks

- [ ] Remove `index` and `book` from allowUnauthenticated list
- [ ] Add beforeFilter check to redirect unauthenticated users to portal login
- [ ] Update book() action to get patient from session and auto-fill data
- [ ] Set patient_id on appointment creation
- [ ] Remove CAPTCHA validation and related methods (generateCaptcha, validateCaptcha)

### Technical Details

**AppointmentsController Changes**

File: `src/Controller/AppointmentsController.php`

```php
public function initialize(): void
{
    parent::initialize();

    // Only these actions are public (no patient auth)
    $this->Authentication->allowUnauthenticated([
        'checkAvailability',
        'getAvailableSlots',
        'confirm',      // Email confirmation link
        'success',      // Success page (session-protected)
    ]);
}

public function beforeFilter(EventInterface $event)
{
    parent::beforeFilter($event);

    $action = $this->request->getParam('action');

    // Require patient auth for booking
    if (in_array($action, ['index', 'book'])) {
        $identity = $this->Authentication->getIdentity();
        if (!$identity) {
            $this->Flash->error('Trebuie să fiți autentificat pentru a face o programare.');
            return $this->redirect(['controller' => 'Patients', 'action' => 'login']);
        }
    }
}

public function book()
{
    // Get authenticated patient
    $patient = $this->Authentication->getIdentity()->getOriginalData();

    // ... existing validation logic (remove CAPTCHA) ...

    // Auto-fill patient data
    $appointmentData = [
        'patient_id' => $patient->id,
        'patient_name' => $patient->full_name,
        'patient_email' => $patient->email,
        'patient_phone' => $patient->phone,
        // ... other fields from form ...
    ];

    $appointment = $this->Appointments->newEntity($appointmentData);
    // ... save and send emails ...
}
```

**Remove these methods:**
- `generateCaptcha()`
- Any CAPTCHA validation in `book()`

---

## Phase 10: Update Appointments Templates

Simplify the booking form since patient data is auto-filled.

### Tasks

- [ ] Update index.php to show logged-in patient info (read-only) instead of input fields
- [ ] Remove CAPTCHA section from Step 4
- [ ] Simplify to show: patient name, email, phone (non-editable) + notes field
- [ ] Update JavaScript to skip patient data validation (already in session)

### Technical Details

**Appointments/index.php Changes**

Replace Step 4 (Patient Details) content:

Before (guest booking):
```html
<input type="text" name="patient_name" required>
<input type="email" name="patient_email" required>
<input type="tel" name="patient_phone" required>
<!-- CAPTCHA -->
```

After (authenticated patient):
```html
<div class="patient-info-display">
    <p><strong>Nume:</strong> <?= h($patient->full_name) ?></p>
    <p><strong>Email:</strong> <?= h($patient->email) ?></p>
    <p><strong>Telefon:</strong> <?= h($patient->phone) ?></p>
</div>
<input type="hidden" name="patient_id" value="<?= $patient->id ?>">
<!-- Only notes field is editable -->
<textarea name="notes" placeholder="Observații (opțional)"></textarea>
```

**Pass patient to view in controller:**
```php
public function index()
{
    $patient = $this->Authentication->getIdentity()->getOriginalData();
    $this->set('patient', $patient);
    // ... rest of existing code ...
}
```

---

## Verification Plan

After implementation, verify each flow:

1. **Registration Flow**
   ```
   - Navigate to /portal/register
   - Fill form: name, email, phone, password
   - Submit → Check email received
   - Click verification link
   - Verify redirect to login with success message
   ```

2. **Login Flow**
   ```
   - Navigate to /portal/login
   - Enter email/password
   - Verify redirect to /portal dashboard
   - Test 3 wrong passwords → verify lockout message
   ```

3. **Password Reset**
   ```
   - Navigate to /portal/forgot-password
   - Enter email
   - Check email for reset link
   - Click link, set new password
   - Login with new password
   ```

4. **Appointment Booking**
   ```
   - Login as patient
   - Navigate to /appointments
   - Verify patient data shown (not editable)
   - Complete booking
   - Check appointment has patient_id set in database
   ```

5. **Appointment Management**
   ```
   - View appointments at /portal/appointments
   - Cancel an upcoming appointment
   - Verify cancellation in list
   ```

6. **Run Tests**
   ```bash
   composer check
   ```
