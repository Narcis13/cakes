# Implementation Plan: Security Hardening

## Overview

Comprehensive security hardening for a CakePHP 5.1 hospital management system. Addresses 13 identified vulnerabilities across login security, XSS protection, form/session security, file uploads, security headers, and configuration hardening.

---

## Phase 1: Login Security (Critical Priority)

Implement rate limiting, account lockout, and strong password validation - the user's top priority requirements.

### Tasks

- [ ] Create database migration for `login_attempts` table
- [ ] Create database migration to add lockout fields to `users` table
- [ ] Create `LoginAttempt` entity class
- [ ] Create `LoginAttemptsTable` model class
- [ ] Create `LoginSecurityService` for centralized rate limiting logic [complex]
  - [ ] Method to check if login is allowed (by email and IP)
  - [ ] Method to record login attempt (success/failure)
  - [ ] Method to clear attempts on successful login
  - [ ] Method to get lockout remaining time
- [ ] Modify `UsersController::login()` to integrate rate limiting
- [ ] Add strong password validation rules to `UsersTable`
- [ ] Update admin user add/edit templates with password requirement hints
- [ ] Remove PII from debug logs in `UsersController`

### Technical Details

**Database Schema - login_attempts table:**
```sql
CREATE TABLE login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT NULL,
    success TINYINT(1) DEFAULT 0,
    attempted_at DATETIME NOT NULL,
    INDEX idx_email_attempted (email, attempted_at),
    INDEX idx_ip_attempted (ip_address, attempted_at)
);
```

**Database Schema - users table additions:**
```sql
ALTER TABLE users ADD COLUMN failed_login_attempts INT DEFAULT 0;
ALTER TABLE users ADD COLUMN locked_until DATETIME NULL;
ALTER TABLE users ADD COLUMN last_login_at DATETIME NULL;
ALTER TABLE users ADD COLUMN last_login_ip VARCHAR(45) NULL;
```

**Files to Create:**
- `config/Migrations/YYYYMMDDHHMMSS_CreateLoginAttempts.php`
- `config/Migrations/YYYYMMDDHHMMSS_AddLockoutFieldsToUsers.php`
- `src/Model/Entity/LoginAttempt.php`
- `src/Model/Table/LoginAttemptsTable.php`
- `src/Service/LoginSecurityService.php`

**Files to Modify:**
- `src/Controller/Admin/UsersController.php` (lines 45-78)
- `src/Model/Table/UsersTable.php` (lines 60-65)
- `templates/Admin/Users/add.php`
- `templates/Admin/Users/edit.php`

**Rate Limiting Logic:**
```php
// In LoginSecurityService
const MAX_EMAIL_ATTEMPTS = 3;
const MAX_IP_ATTEMPTS = 10;
const LOCKOUT_DURATION = 3600; // 1 hour in seconds

public function isLoginAllowed(string $email, string $ip): bool
{
    $oneHourAgo = new DateTime('-1 hour');

    $emailAttempts = $this->LoginAttempts->find()
        ->where([
            'email' => $email,
            'success' => false,
            'attempted_at >=' => $oneHourAgo,
        ])->count();

    $ipAttempts = $this->LoginAttempts->find()
        ->where([
            'ip_address' => $ip,
            'success' => false,
            'attempted_at >=' => $oneHourAgo,
        ])->count();

    return $emailAttempts < self::MAX_EMAIL_ATTEMPTS
        && $ipAttempts < self::MAX_IP_ATTEMPTS;
}
```

**Password Validation:**
```php
// In UsersTable::validationDefault()
$validator
    ->scalar('password')
    ->maxLength('password', 255)
    ->requirePresence('password', 'create')
    ->notEmptyString('password')
    ->minLength('password', 12, 'Password must be at least 12 characters')
    ->add('password', 'uppercase', [
        'rule' => function ($value) {
            return (bool)preg_match('/[A-Z]/', $value);
        },
        'message' => 'Password must contain at least one uppercase letter'
    ])
    ->add('password', 'lowercase', [
        'rule' => function ($value) {
            return (bool)preg_match('/[a-z]/', $value);
        },
        'message' => 'Password must contain at least one lowercase letter'
    ])
    ->add('password', 'number', [
        'rule' => function ($value) {
            return (bool)preg_match('/[0-9]/', $value);
        },
        'message' => 'Password must contain at least one number'
    ])
    ->add('password', 'special', [
        'rule' => function ($value) {
            return (bool)preg_match('/[!@#$%^&*(),.?":{}|<>]/', $value);
        },
        'message' => 'Password must contain at least one special character'
    ]);
```

**CLI Commands:**
```bash
bin/cake bake migration CreateLoginAttempts
bin/cake bake migration AddLockoutFieldsToUsers
bin/cake migrations migrate
```

---

## Phase 2: XSS Protection (Critical Priority)

Sanitize CMS content and add security headers to prevent cross-site scripting attacks.

### Tasks

- [ ] Install HTML Purifier library via Composer
- [ ] Create `PurifierHelper` view helper class
- [ ] Create HTML Purifier configuration file
- [ ] Load `PurifierHelper` in `AppView`
- [ ] Fix XSS in `templates/Pages/page.php` (line 32)
- [ ] Fix XSS in `templates/News/view.php` (line 62)
- [ ] Fix XSS in `templates/Admin/News/view.php` (line 72)
- [ ] Create `SecurityHeadersMiddleware` class
- [ ] Add `SecurityHeadersMiddleware` to Application middleware queue

### Technical Details

**CLI Commands:**
```bash
composer require ezyang/htmlpurifier
```

**Files to Create:**
- `src/View/Helper/PurifierHelper.php`
- `config/html_purifier.php`
- `src/Middleware/SecurityHeadersMiddleware.php`

**Files to Modify:**
- `src/View/AppView.php` - Add `$this->addHelper('Purifier');`
- `templates/Pages/page.php` (line 32)
- `templates/News/view.php` (line 62)
- `templates/Admin/News/view.php` (line 72)
- `src/Application.php` (middleware queue)

**PurifierHelper Implementation:**
```php
<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;
use HTMLPurifier;
use HTMLPurifier_Config;

class PurifierHelper extends Helper
{
    private ?HTMLPurifier $purifier = null;

    public function clean(?string $html): string
    {
        if ($html === null) {
            return '';
        }

        if ($this->purifier === null) {
            $config = HTMLPurifier_Config::createDefault();
            $config->set('HTML.Allowed', 'p,br,strong,em,b,i,u,ul,ol,li,h2,h3,h4,h5,h6,a[href|title|target],img[src|alt|title],table,thead,tbody,tr,th,td,blockquote,code,pre,span,div');
            $config->set('HTML.TargetBlank', true);
            $config->set('URI.AllowedSchemes', ['http' => true, 'https' => true, 'mailto' => true]);
            $config->set('Attr.AllowedFrameTargets', ['_blank']);
            $this->purifier = new HTMLPurifier($config);
        }

        return $this->purifier->purify($html);
    }
}
```

**Template Fix Pattern:**
```php
// BEFORE (vulnerable):
<?= $component->content ?>

// AFTER (safe):
<?= $this->Purifier->clean($component->content) ?>
```

**SecurityHeadersMiddleware:**
```php
<?php
declare(strict_types=1);

namespace App\Middleware;

use Cake\Core\Configure;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SecurityHeadersMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        $response = $response
            ->withHeader('X-Frame-Options', 'SAMEORIGIN')
            ->withHeader('X-Content-Type-Options', 'nosniff')
            ->withHeader('X-XSS-Protection', '1; mode=block')
            ->withHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->withHeader('Permissions-Policy', 'geolocation=(), microphone=(), camera=()')
            ->withHeader('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline' cdn.tiny.cloud cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' fonts.googleapis.com cdn.jsdelivr.net; font-src 'self' fonts.gstatic.com; img-src 'self' data: blob:; frame-ancestors 'self';");

        // HSTS only in production
        if (!Configure::read('debug')) {
            $response = $response->withHeader(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains'
            );
        }

        return $response;
    }
}
```

---

## Phase 3: Form & Session Security (High Priority)

Enable form protection, secure session configuration, and fix mass assignment vulnerabilities.

### Tasks

- [ ] Enable FormProtection component in `AppController`
- [ ] Configure secure session settings in `config/app.php`
- [ ] Fix mass assignment in `Appointment` entity (set sensitive fields to false)
- [ ] Fix mass assignment in `User` entity (set role/timestamps to false)
- [ ] Review and fix mass assignment in other entities
- [ ] Fix open redirect vulnerability in `UsersController::login()`

### Technical Details

**Files to Modify:**
- `src/Controller/AppController.php` (line 54) - Uncomment FormProtection
- `config/app.php` (lines 412-414) - Session configuration
- `src/Model/Entity/Appointment.php` (lines 48-70)
- `src/Model/Entity/User.php`
- `src/Controller/Admin/UsersController.php` (lines 65-71)

**Enable FormProtection:**
```php
// In AppController::initialize()
$this->loadComponent('FormProtection');
```

**Secure Session Configuration:**
```php
// In config/app.php
'Session' => [
    'defaults' => 'php',
    'timeout' => 30,
    'cookie' => 'HOSPITAL_SESSION',
    'ini' => [
        'session.cookie_secure' => true,
        'session.cookie_httponly' => true,
        'session.cookie_samesite' => 'Strict',
        'session.use_strict_mode' => true,
        'session.use_only_cookies' => true,
        'session.gc_maxlifetime' => 1800,
    ],
],
```

**Appointment Entity Fix:**
```php
protected array $_accessible = [
    'patient_name' => true,
    'patient_phone' => true,
    'patient_email' => true,
    'patient_cnp' => true,
    'service_id' => true,
    'doctor_id' => true,
    'appointment_date' => true,
    'appointment_time' => true,
    'end_time' => true,
    'notes' => true,
    // SECURE: Set sensitive fields to false
    'status' => false,
    'confirmation_token' => false,
    'confirmed_at' => false,
    'cancelled_at' => false,
    'cancellation_reason' => false,
    'reminded_24h' => false,
    'reminded_2h' => false,
    'created' => false,
    'modified' => false,
    'service' => false,
    'doctor' => false,
];
```

**Open Redirect Fix:**
```php
// In UsersController::login()
private function validateRedirectUrl(mixed $redirect): array
{
    $default = [
        'controller' => 'Dashboard',
        'action' => 'index',
        'prefix' => 'Admin',
    ];

    if (is_array($redirect)) {
        // Only allow Admin prefix routes
        if (isset($redirect['prefix']) && $redirect['prefix'] === 'Admin') {
            return $redirect;
        }
    }

    if (is_string($redirect)) {
        // Only allow internal admin paths
        if (str_starts_with($redirect, '/smupa1881/')) {
            return $redirect;
        }
    }

    return $default;
}

// Usage in login():
$redirect = $this->validateRedirectUrl(
    $this->request->getQuery('redirect', $default)
);
return $this->redirect($redirect);
```

---

## Phase 4: File Upload & Transport Security (High Priority)

Implement server-side MIME validation and enable SSL verification.

### Tasks

- [ ] Replace client MIME validation with server-side `finfo_file()` in `FilesController`
- [ ] Add file extension to MIME type matching
- [ ] Enable SSL certificate verification in email transport config
- [ ] Add HTTPS enforcement middleware (disabled in debug mode)

### Technical Details

**Files to Modify:**
- `src/Controller/Admin/FilesController.php` (lines 288-290)
- `config/app_local.php` (lines 102-107)
- `src/Application.php` (middleware queue)

**Server-Side MIME Validation:**
```php
// In FilesController - replace getClientMediaType() usage
private function validateFileUpload(UploadedFileInterface $uploadedFile): array
{
    $stream = $uploadedFile->getStream();
    $tempPath = $stream->getMetadata('uri');

    // Server-side MIME detection
    $finfo = new \finfo(FILEINFO_MIME_TYPE);
    $actualMimeType = $finfo->file($tempPath);

    // Get file extension
    $originalName = $uploadedFile->getClientFilename();
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    // MIME to extension mapping
    $mimeToExtension = [
        'application/pdf' => ['pdf'],
        'image/jpeg' => ['jpg', 'jpeg'],
        'image/png' => ['png'],
        'image/gif' => ['gif'],
        'image/webp' => ['webp'],
        'image/svg+xml' => ['svg'],
        'application/msword' => ['doc'],
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => ['docx'],
    ];

    // Validate MIME type is allowed
    if (!isset($mimeToExtension[$actualMimeType])) {
        return ['success' => false, 'error' => 'File type not allowed: ' . $actualMimeType];
    }

    // Validate extension matches MIME type
    if (!in_array($extension, $mimeToExtension[$actualMimeType])) {
        return ['success' => false, 'error' => 'File extension does not match content type'];
    }

    return ['success' => true, 'mimeType' => $actualMimeType];
}
```

**Enable SSL Verification:**
```php
// In config/app_local.php - EmailTransport section
'context' => [
    'ssl' => [
        'verify_peer' => true,
        'verify_peer_name' => true,
        'allow_self_signed' => false,
    ]
],
```

**HTTPS Enforcement (optional middleware):**
```php
// In Application::middleware()
use Cake\Http\Middleware\HttpsEnforcerMiddleware;

if (!Configure::read('debug')) {
    $middlewareQueue->add(new HttpsEnforcerMiddleware([
        'redirect' => true,
        'statusCode' => 301,
        'disableOnDebug' => true,
    ]));
}
```

---

## Phase 5: Configuration Hardening (Critical Priority)

Secure application configuration and remove sensitive data from logs.

### Tasks

- [ ] Set debug mode default to `false` in `app_local.php`
- [ ] Remove hardcoded default values for sensitive config (security salt, API keys)
- [ ] Create `.env.example` template file
- [ ] Remove email addresses from login debug logs
- [ ] Remove patient PII from appointment logs
- [ ] **[MANUAL]** Rotate compromised security salt
- [ ] **[MANUAL]** Rotate compromised TinyMCE API key
- [ ] **[MANUAL]** Change database credentials from root/root

### Technical Details

**Files to Modify:**
- `config/app_local.php` (line 18, 28, 35)
- `src/Controller/Admin/UsersController.php` (lines 54-60)
- `src/Controller/AppointmentsController.php` (lines 322, 445)

**Files to Create:**
- `config/.env.example`

**Debug Mode Fix:**
```php
// In config/app_local.php line 18
// BEFORE:
'debug' => filter_var(env('DEBUG', true), FILTER_VALIDATE_BOOLEAN),

// AFTER:
'debug' => filter_var(env('DEBUG', false), FILTER_VALIDATE_BOOLEAN),
```

**Remove Hardcoded Fallbacks:**
```php
// In config/app_local.php
'Security' => [
    'salt' => env('SECURITY_SALT'), // No fallback - REQUIRED
],

'ApiKeys' => [
    'tinymce' => env('TINYMCE_API_KEY'), // No fallback - REQUIRED
],
```

**.env.example Template:**
```bash
# Security (REQUIRED - generate unique values)
SECURITY_SALT=__GENERATE_64_CHAR_HEX_STRING__

# Database
DATABASE_HOST=127.0.0.1
DATABASE_PORT=3306
DATABASE_USERNAME=hospital_app
DATABASE_PASSWORD=__SECURE_PASSWORD__
DATABASE_NAME=hospital_db

# Email
EMAIL_HOST=smtp.example.com
EMAIL_PORT=587
EMAIL_USERNAME=noreply@hospital.com
EMAIL_PASSWORD=__SECURE_PASSWORD__

# API Keys
TINYMCE_API_KEY=__YOUR_API_KEY__

# Debug (MUST be false in production)
DEBUG=false
```

**Remove PII from Logs:**
```php
// In UsersController::login() - REMOVE these lines:
// $this->log('Login attempt for: ' . ($data['email'] ?? 'no email'), 'debug');
// $this->log('Authentication result: ' . ($result ? $result->getStatus() : 'no result'), 'debug');
// $this->log('Result errors: ' . json_encode($result->getErrors()), 'debug');

// Replace with (if logging needed):
$this->log('Login attempt received', 'debug');
```

---

## Phase 6: Access Control Fixes (Medium Priority)

Protect against data enumeration and hide sensitive data from public endpoints.

### Tasks

- [ ] Protect appointment success page from ID enumeration
- [ ] Remove doctor email/phone from public AJAX responses
- [ ] Add authorization check for appointment confirmation access

### Technical Details

**Files to Modify:**
- `src/Controller/AppointmentsController.php` (lines 538-548, 209-218)

**Appointment Success Page Protection:**
```php
// In AppointmentsController::success()
public function success(?string $id = null)
{
    $session = $this->request->getSession();
    $validAppointmentId = $session->read('last_booked_appointment_id');
    $token = $this->request->getQuery('token');

    // Allow access if: 1) just booked (session), or 2) has valid token
    if ($id === (string)$validAppointmentId) {
        $appointment = $this->Appointments->get($id, [
            'contain' => ['Doctors' => ['Departments'], 'Services'],
        ]);
        $session->delete('last_booked_appointment_id');
    } elseif ($token) {
        $appointment = $this->Appointments->find()
            ->where([
                'id' => $id,
                'confirmation_token' => $token,
            ])
            ->contain(['Doctors' => ['Departments'], 'Services'])
            ->first();

        if (!$appointment) {
            throw new ForbiddenException('Access denied');
        }
    } else {
        throw new ForbiddenException('Access denied');
    }

    $this->set(compact('appointment'));
}
```

**Hide Sensitive Data from Public API:**
```php
// In AppointmentsController::checkAvailability() - line ~215
// BEFORE:
$availableDoctors[] = [
    'id' => $doctor->id,
    'name' => $doctor->first_name . ' ' . $doctor->last_name,
    'email' => $doctor->email,      // REMOVE
    'phone' => $doctor->phone,      // REMOVE
    'photo' => $doctor->photo,
    'specialization' => $doctor->specialization,
];

// AFTER:
$availableDoctors[] = [
    'id' => $doctor->id,
    'name' => $doctor->first_name . ' ' . $doctor->last_name,
    'photo' => $doctor->photo,
    'specialization' => $doctor->specialization,
];
```

---

## Phase 7: Audit Logging (Optional Enhancement)

Create audit trail for admin actions on sensitive data.

### Tasks

- [ ] Create database migration for `audit_logs` table
- [ ] Create `AuditLog` entity class
- [ ] Create `AuditLogsTable` model class
- [ ] Create `AuditableBehavior` for automatic logging
- [ ] Attach behavior to sensitive models (Users, Appointments, etc.)

### Technical Details

**Database Schema - audit_logs:**
```sql
CREATE TABLE audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action VARCHAR(50) NOT NULL,
    model VARCHAR(100) NOT NULL,
    record_id INT NULL,
    old_values JSON NULL,
    new_values JSON NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created DATETIME NOT NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_model_record (model, record_id),
    INDEX idx_created (created)
);
```

**Files to Create:**
- `config/Migrations/YYYYMMDDHHMMSS_CreateAuditLogs.php`
- `src/Model/Entity/AuditLog.php`
- `src/Model/Table/AuditLogsTable.php`
- `src/Model/Behavior/AuditableBehavior.php`

**CLI Commands:**
```bash
bin/cake bake migration CreateAuditLogs
bin/cake migrations migrate
```
